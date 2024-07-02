<?php
include("../config/database.php");

try {
    // Criar a conexão com o banco de dados
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar se o formulário foi submetido
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $data_pagar = $_POST['data_pagar'];
        $valor = $_POST['valor'];

        // Atualizar a conta no banco de dados
        $stmt = $conn->prepare("UPDATE tbl_conta_pagar SET data_pagar = :data_pagar, valor = :valor WHERE id_conta_pagar = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':data_pagar', $data_pagar);
        $stmt->bindParam(':valor', $valor);

        if ($stmt->execute()) {
            echo "Conta atualizada com sucesso.";
        } else {
            echo "Erro ao atualizar conta.";
        }
    } else {
        // Obter os detalhes da conta a ser editada
        $id = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM tbl_conta_pagar WHERE id_conta_pagar = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $conta = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Conta a Pagar</title>
</head>
<body>
    <h1>Editar Conta a Pagar</h1>
    <form method="POST" action="EditarContaPagar.php">
        <input type="hidden" name="id" value="<?php echo $conta['id_conta_pagar']; ?>">
        <label for="data_pagar">Data a ser Pago:</label>
        <input type="date" id="data_pagar" name="data_pagar" value="<?php echo $conta['data_pagar']; ?>" required><br><br>
        <label for="valor">Valor:</label>
        <input type="text" id="valor" name="valor" value="<?php echo $conta['valor']; ?>" required><br><br>
        <input type="submit" value="Atualizar">
        <br>
        <br>
        <a href="ListarContasPagar.php" class="btn" style="display: inline-block; padding: 8px 16px; background-color: #007bff; color: #fff; text-decoration: none; border: none; cursor: pointer; border-radius: 4px; font-size: 14px;">Ir para a lista de contas a pagar</a>
        
    </form>
</body>
</html>
