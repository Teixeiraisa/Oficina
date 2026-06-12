<?php
$host = "localhost";      // servidor
$user = "root";           // usuário padrão do XAMPP/Laragon
$password = "";           // senha padrão vazia
$database = "oficina";    // nome do banco

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

session_start(); // para login da gerência

function moeda($valor) {
    return "R$ " . number_format((float)$valor, 2, ',', '.');
}
?>