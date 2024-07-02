<?php
include("../config/database.php");
class ContaPagarModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    private function aplicarRegrasNegocio($contas) {
        $hoje = new DateTime();
        foreach ($contas as &$conta) {
            $dataPagar = new DateTime($conta['data_pagar']);
            if ($conta['pago']) {
                $dataPagamento = new DateTime($conta['data_pagar']); //data_pagamento no banco de dados
                if ($dataPagamento < $dataPagar) {
                    $conta['valor'] *= 0.95; // 5% de desconto
                } elseif ($dataPagamento > $dataPagar) {
                    $conta['valor'] *= 1.10; // 10% de acréscimo
                }
            }
        }
        return $contas;
    }

    public function inserirContaPagar($id_empresa, $data_pagar, $valor) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO tbl_conta_pagar (id_empresa, data_pagar, valor) VALUES (:id_empresa, :data_pagar, :valor)");
            $stmt->bindParam(':id_empresa', $id_empresa);
            $stmt->bindParam(':data_pagar', $data_pagar);
            $stmt->bindParam(':valor', $valor);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch(PDOException $e) {
            throw new Exception("Erro: " . $e->getMessage());
        }
    }

    public function listarEmpresas() {
        $query = "SELECT id_empresa, nome FROM tbl_empresa";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionarContaPagar($id_empresa, $valor, $data_pagar) {
        $query = "INSERT INTO tbl_conta_pagar (id_empresa, valor, data_pagar, pago) 
                  VALUES (:id_empresa, :valor, :data_pagar, 0)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);
        $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
        $stmt->bindParam(':data_pagar', $data_pagar, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function listarContasPagar($filtros = []) {
        try {
            $sql = "SELECT * FROM tbl_conta_pagar WHERE 1=1";

            if (!empty($filtros['nome_empresa'])) {
                $sql .= " AND id_empresa IN (SELECT id_empresa FROM tbl_empresa WHERE nome LIKE :nome_empresa)";
            }
            if (!empty($filtros['valor_condicao']) && !empty($filtros['valor'])) {
                switch ($filtros['valor_condicao']) {
                    case 'maior':
                        $sql .= " AND valor > :valor";
                        break;
                    case 'menor':
                        $sql .= " AND valor < :valor";
                        break;
                    case 'igual':
                        $sql .= " AND valor = :valor";
                        break;
                }
            }
            if (!empty($filtros['data_pagamento'])) {
                $sql .= " AND data_pagar = :data_pagamento";
            }

            $stmt = $this->conn->prepare($sql);

            if (!empty($filtros['nome_empresa'])) {
                $stmt->bindValue(':nome_empresa', '%' . $filtros['nome_empresa'] . '%');
            }
            if (!empty($filtros['valor_condicao']) && !empty($filtros['valor'])) {
                $stmt->bindValue(':valor', $filtros['valor']);
            }
            if (!empty($filtros['data_pagamento'])) {
                $stmt->bindValue(':data_pagamento', $filtros['data_pagamento']);
            }

            $stmt->execute();
            $contas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $this->aplicarRegrasNegocio($contas);
        } catch(PDOException $e) {
            throw new Exception("Erro: " . $e->getMessage());
        }
    }
}


?>
