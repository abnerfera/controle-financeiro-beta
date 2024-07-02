<!-- views/ListarContasPagar.php -->
<?php 
include("../config/database.php");
require_once "../models/ContaPagarModel.php";

$contaPagarModel = new ContaPagarModel($conn);
$contasPagar = $contaPagarModel->listarContasPagar();


$contaPagarModel = new ContaPagarModel($conn);

$filtros = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($_GET['nome_empresa'])) {
        $filtros['nome_empresa'] = $_GET['nome_empresa'];
    }
    if (!empty($_GET['valor_condicao']) && !empty($_GET['valor'])) {
        $filtros['valor_condicao'] = $_GET['valor_condicao'];
        $filtros['valor'] = $_GET['valor'];
    }
    if (!empty($_GET['data_pagamento'])) {
        $filtros['data_pagamento'] = $_GET['data_pagamento'];
    }
}

$contasPagar = $contaPagarModel->listarContasPagar($filtros);

// Função para obter o nome da empresa pelo ID
function obterNomeEmpresa($conn, $id_empresa) {
    $stmt = $conn->prepare("SELECT nome FROM tbl_empresa WHERE id_empresa = :id");
    $stmt->bindParam(':id', $id_empresa);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['nome'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Contas a Pagar</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Lista de Contas a Pagar</h1>


    <a href="index.php" class="btn" style="display: inline-block; padding: 8px 16px; background-color: #007bff; color: #fff; text-decoration: none; border: none; cursor: pointer; border-radius: 4px; font-size: 14px;">Voltar para adicionar contas a pagar</a>
    <br>
    <br>

    <form method="GET" action="ListarContasPagar.php">
        <label for="nome_empresa">Nome da Empresa:</label>
        <input type="text" id="nome_empresa" name="nome_empresa">
        
        <label for="valor_condicao">Valor:</label>
        <select id="valor_condicao" name="valor_condicao">
            <option value="">Selecione</option>
            <option value="maior">Maior que</option>
            <option value="menor">Menor que</option>
            <option value="igual">Igual a</option>
        </select>
        <input type="number" step="0.01" id="valor" name="valor">

        <label for="data_pagamento">Data de Pagamento:</label>
        <input type="date" id="data_pagamento" name="data_pagamento">

        <button type="submit">Filtrar</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Empresa</th>
                <th>Data a ser Pago</th>
                <th>Valor</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($contasPagar)): ?>
                <?php foreach ($contasPagar as $conta): ?>
                    <tr>
                        <td><?php echo obterNomeEmpresa($conn, $conta['id_empresa']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($conta['data_pagar'])); ?></td>
                        <td>R$ <?php echo number_format($conta['valor'], 2, ',', '.'); ?></td>
                        <td><?php echo $conta['pago'] ? '<span style="color: green;">Pago</span>' : 'Pendente'; ?></td>
                        <td>
                            <button onclick="editarConta(<?php echo $conta['id_conta_pagar']; ?>)">Editar</button>
                            <button onclick="excluirConta(<?php echo $conta['id_conta_pagar']; ?>)">Excluir</button>
                            <?php if (!$conta['pago']): ?>
                                <button onclick="marcarComoPago(<?php echo $conta['id_conta_pagar']; ?>)">Marcar como Pago</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Não há contas a pagar cadastradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
        function editarConta(id) {
            console.log('Editar conta com ID:', id);
            // Redirecionar para a página de edição
            window.location.href = 'EditarContaPagar.php?id=' + id;
        }

        function excluirConta(id) {
            console.log('Excluir conta com ID:', id);
            if (confirm('Tem certeza de que deseja excluir esta conta?')) {
                fetch('ExcluirContaPagar.php?id=' + id, { method: 'GET' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Conta excluída com sucesso.');
                            window.location.reload();
                        } else {
                            alert('Erro ao excluir conta: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao excluir conta:', error);
                        alert('Erro ao excluir conta. Verifique o console para mais detalhes.');
                    });
            }
        }

        function marcarComoPago(id) {
            console.log('Marcar como pago conta com ID:', id);
            if (confirm('Tem certeza de que deseja marcar esta conta como paga?')) {
                fetch('MarcarComoPago.php?id=' + id, { method: 'GET' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Conta marcada como paga com sucesso.');
                            window.location.reload();
                        } else {
                            alert('Erro ao marcar conta como paga: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao marcar conta como paga:', error);
                        alert('Erro ao marcar conta como paga. Verifique o console para mais detalhes.');
                    });
            }
        }
    </script>
</body>
</html>