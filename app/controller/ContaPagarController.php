<?php
// controllers/ContaPagarController.php


include("../config/database.php");
include("../models/ContaPagarModel.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);




class ContaPagarController {
    
    private $model;

    public function __construct() {
        $this->model = new ContaPagarModel(); // Instanciar o modelo
    }

    public function adicionar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inserir'])) {
            // Obter dados do formulário
            $id_empresa = $_POST['id_empresa'];
            $valor = $_POST['valor'];
            $data_pagar = $_POST['data_pagar'];

            // Chamar método do modelo para adicionar conta a pagar
            $adicionou = $this->model->adicionarContaPagar($id_empresa, $valor, $data_pagar);

            if ($adicionou) {
                echo "Conta a pagar adicionada com sucesso!";
            } else {
                echo "Erro ao adicionar conta a pagar.";
            }
        }

        // Carregar lista de empresas para o formulário
        $empresas = $this->model->listarEmpresas();

        // Incluir a view para adicionar conta a pagar
        require_once '../views/index.php';
    }

    public function listar() {
        // Obter todas as contas a pagar
        $contasPagar = $this->model->listarContasPagar();

        // Incluir a view para listar contas a pagar
        require_once '../views/ListarContasPagar.php';
    }
}
?>
