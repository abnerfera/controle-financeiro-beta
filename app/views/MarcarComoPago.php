<?php
include("../config/database.php");

header('Content-Type: application/json');

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $_GET['id'];
    $data_pagar = date('Y-m-d');

    $stmt = $conn->prepare("UPDATE tbl_conta_pagar SET pago = 1, data_pagar = :data_pagar WHERE id_conta_pagar = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':data_pagar', $data_pagar);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao marcar conta como paga.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
