<!-- views/index.php -->


<?php
include("../config/database.php");
include("../models/ContaPagarModel.php");
$contaPagarModel = new ContaPagarModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inserir'])) {

    $id_empresa = $_POST['id_empresa'];
    $data_pagar = $_POST['data_pagar'];
    $valor = $_POST['valor'];

    try {
        // Inserir os dados usando o modelo
        if ($contaPagarModel->inserirContaPagar($id_empresa, $data_pagar, $valor)) {
           
        } else {
            echo "Erro ao inserir os dados.";
        }
    } catch(Exception $e) {
        echo $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Conta a Pagar</title>
</head>
<body>
    <h1>Adicionar Conta a Pagar</h1>
    <form action="index.php" method="POST">
        <label for="selectEmpresa">Empresa:</label>
        <select id="selectEmpresa" name="id_empresa" required>
            <?php
            $query = $conn->query("SELECT id_empresa, nome FROM tbl_empresa");
            $registros = $query->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php foreach ($registros as $empresa): ?>
                <option value=<?php echo $empresa['id_empresa']; ?>>
                    <?php echo ($empresa)['nome']; ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        
        <label for="data_pagar">Data a ser Pago:</label>
        <input type="date" id="data_pagar" name="data_pagar" required><br><br>
        <label for="valor">Valor:</label>
        <input type="text" id="valor" name="valor" required><br><br>
        <input type="submit" name="inserir" value="Inserir">
        <!-- Botão para voltar para a página de listagem -->
        <br>
        <br>
        <a href="ListarContasPagar.php" class="btn" style="display: inline-block; padding: 8px 16px; background-color: #007bff; color: #fff; text-decoration: none; border: none; cursor: pointer; border-radius: 4px; font-size: 14px;">Ir para a lista de contas a pagar</a>
        
    </form>
</body>
</html>
