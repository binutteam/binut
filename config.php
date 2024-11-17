<?php
// Configuração do banco de dados
define('DB_SERVER', 'localhost');  // Servidor local
define('DB_USERNAME', 'root');     // Usuário do banco de dados
define('DB_PASSWORD', '');         // Senha do banco de dados
define('DB_DATABASE', 'binut_empresa');  // Nome do banco de dados

// Criação da conexão com o banco de dados
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>