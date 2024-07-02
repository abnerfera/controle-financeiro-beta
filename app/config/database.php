<?php
// Configurações do banco de dados

$host = 'localhost'; 
$dbname = 'controle_financeiro';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    echo "Conexão falhou: " . $e->getMessage();
}
?>