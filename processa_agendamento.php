<?php
session_start();
include_once('conexao.php');

// Verificar se o cliente está logado antes de tentar acessar o ID
if (!isset($_SESSION['user_id'])) {
    echo "Você precisa estar logado como cliente para agendar.";
    exit();
}

// Verificar se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_nutri'], $_POST['horario_agendado'])) {
    $id_nutri = intval($_POST['id_nutri']);
    $horario_agendado = $_POST['horario_agendado'];
    $id_cliente = $_SESSION['user_id']; // Usando 'user_id' aqui para pegar o ID do cliente

    // Inserir agendamento no banco de dados
    $agendamento_sql = "INSERT INTO agendamentos (id_nutri, id_cliente, horario_agendado) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($agendamento_sql);
    $stmt->bind_param("iis", $id_nutri, $id_cliente, $horario_agendado);

    if ($stmt->execute()) {
        header("Location: sucesso_agendamento.php");
        exit();
    } else {
        echo "Erro ao agendar. Tente novamente.";
    }
} else {
    echo "Dados inválidos.";
}
?>
