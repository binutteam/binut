<?php
// Incluir a conexão com o banco de dados
include('conexao.php');

// Iniciar a sessão
session_start();

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar as variáveis do formulário para o cliente
    if (!empty($_POST['email_cliente']) && !empty($_POST['senha_cliente'])) {
        $email = trim($_POST['email_cliente']);
        $senha = $_POST['senha_cliente'];

        // SQL para buscar o cliente
        $sql = "SELECT * FROM cliente WHERE LOWER(email_cliente) = LOWER(?)";

        // Preparar a consulta
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro ao preparar consulta: " . $conn->error);
        }

        // Executar a consulta
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar o resultado
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($senha, $user['senha_cliente'])) {
                // Armazenar as informações do usuário na sessão
                $_SESSION['user_id'] = $user['id_cliente'];  // Armazenando o ID do cliente
                $_SESSION['user_type'] = 'cliente';

                // Redirecionar o cliente para a página específica
                header("Location: nutricionistas_cliente_pov.php");  // Substitua pela página desejada
                exit();
            } else {
                echo "Senha incorreta.";
            }
        } else {
            echo "Email não encontrado.";
        }

        // Fechar a conexão
        $stmt->close();
        $conn->close();
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}
?>