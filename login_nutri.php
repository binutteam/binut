<?php
// Incluir a conexão com o banco de dados
include('conexao.php');

// Iniciar a sessão
session_start();

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar as variáveis do formulário para o nutricionista
    if (!empty($_POST['email_nutri']) && !empty($_POST['senha_nutri'])) {
        $email = trim($_POST['email_nutri']);
        $senha = $_POST['senha_nutri'];

        // SQL para buscar o nutricionista
        $sql = "SELECT * FROM nutricionista WHERE LOWER(email_nutri) = LOWER(?)";

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
            if (password_verify($senha, $user['senha_nutri'])) {
                // Armazenar as informações do usuário na sessão
                $_SESSION['user_id'] = $user['id_nutri'];
                $_SESSION['user_type'] = 'nutricionista';

                // Redirecionar o nutricionista para a página específica
                header("Location: nutricionistas_nutri_pov.php"); // Substitua por sua página de dashboard do nutricionista
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
