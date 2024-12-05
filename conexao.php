<?php
$host = "localhost"; 
$user = "root";     
$password = "";          
$dbname = "binut_empresa"; 

// Criar a conexão
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>