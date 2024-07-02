<?php
include("../config/database.php");

header('Content-Type: application/json');

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM tbl_conta_pagar WHERE id_conta_pagar = :id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao excluir conta.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
