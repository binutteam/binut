<?php
// Incluir a conexão com o banco de dados
include('conexao.php');

// Verificar se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cadastro de Cliente
    $nome = trim($_POST['nome_cliente']);
    $arroba = trim($_POST['arroba']);
    $email = trim($_POST['email_cliente']);
    $senha = $_POST['senha_cliente'];

    // Validar se os campos estão vazios
    if (empty($nome) || empty($email) || empty($senha) || empty($arroba)) {
        echo "Por favor, preencha todos os campos.";
        exit();
    }

    // Verificar se o email já está cadastrado
    $sql = "SELECT * FROM cliente WHERE email_cliente = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Esse email já está cadastrado. Tente outro.";
        exit();
    }

    // Criptografar a senha
    $senha_criptografada = password_hash($senha, PASSWORD_BCRYPT);

    // Inserir o novo cliente no banco de dados
    $sql = "INSERT INTO cliente (nome_cliente, arroba, email_cliente, senha_cliente) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro ao preparar a consulta de inserção: " . $conn->error);
    }
    $stmt->bind_param("ssss", $nome, $arroba, $email, $senha_criptografada);

    if ($stmt->execute()) {
        header("Location: login_conta.html");
        exit();
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    // Fechar a conexão
    $stmt->close();
    $conn->close();
} else {
    echo " Método de requisição inválido.";
}
?>