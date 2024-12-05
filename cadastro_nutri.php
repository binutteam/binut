<?php
// Incluir a conexão com o banco de dados
include('conexao.php');

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receber os dados do formulário
    $nome = trim($_POST['nome_nutri']);
    $arroba = trim($_POST['arroba_nutri']);
    $email = trim($_POST['email_nutri']);
    $especialidade = trim($_POST['especialidade']);
    $CRN_nutri = trim($_POST['CRN_nutri']);
    $senha = $_POST['senha_nutri'];

    // Validar se os campos não estão vazios
    if (empty($nome) || empty($email) || empty($senha) || empty($arroba) || 
        empty($especialidade) || empty($CRN_nutri)) {
        echo "Por favor, preencha todos os campos.";
        exit();
    }

    // Verificar se o email já está cadastrado
    $sql = "SELECT * FROM nutricionista WHERE email_nutri = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Esse email já está cadastrado. Tente outro.";
        exit();
    }

    // Criptografar a senha
    $senha_criptografada = password_hash($senha, PASSWORD_BCRYPT);

    // Inserir o novo nutricionista no banco de dados
    $sql = "INSERT INTO nutricionista (nome_nutri, arroba_nutri, especialidade, CRN_nutri, email_nutri, senha_nutri) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nome, $arroba, $especialidade, $CRN_nutri, $email, $senha_criptografada);

    if ($stmt->execute()) {
        // Redirecionar para a página de login após o cadastro
        header("Location: login_conta.html");
        exit();
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    // Fechar a conexão
    $stmt->close();
    $conn->close();
}
?>
