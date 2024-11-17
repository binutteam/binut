<?php
// Inclui o arquivo de banco de dados
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Checa se é nutri ou cliente
    $isNutritionist = isset($_POST['nome_completo_nutricionista']) && !empty($_POST['nome_completo_nutricionista']);
    $isClient = isset($_POST['nome_completo_cliente']) && !empty($_POST['nome_completo_cliente']);

    // Vê se há erros
    $errors = [];

    // Validate nutritionist data
    if ($isNutritionist) {
        $nomeCompleto = htmlspecialchars(trim($_POST['nome_completo_nutricionista']));
        $nomeUsuario = htmlspecialchars(trim($_POST['nome_usuario_nutricionista']));
        $especialidade = htmlspecialchars(trim($_POST['especialidade']));
        $email = htmlspecialchars(trim($_POST['email_nutricionista']));
        $cnrInscricao = htmlspecialchars(trim($_POST['cnr_inscricao']));
        $senha = htmlspecialchars(trim($_POST['senha_nutricionista']));

        // validação
        if (empty($nomeCompleto) || empty($nomeUsuario) || empty($especialidade) || empty($email) || empty($cnrInscricao) || empty($senha)) {
            $errors[] = "Todos os campos são obrigatórios para nutricionistas.";
        }
    }

    // dados do cliente
    if ($isClient) {
        $nomeCompletoCliente = htmlspecialchars(trim($_POST['nome_completo_cliente']));
        $nomeUsuarioCliente = htmlspecialchars(trim($_POST['nome_usuario_cliente']));
        $emailCliente = htmlspecialchars(trim($_POST['email_cliente']));
        $senhaCliente = htmlspecialchars(trim($_POST['senha_cliente']));

        // Add validation logic as needed
        if (empty($nomeCompletoCliente) || empty($nomeUsuarioCliente) || empty($emailCliente) || empty($senhaCliente)) {
            $errors[] = "Todos os campos são obrigatórios para clientes.";
        }
    }

    // checa a politica de privacidade
    if (!isset($_POST['privacy-policy'])) {
        $errors[] = "Você deve concordar com a Política de Privacidade e Termos de Serviço.";
    }

    // se há erros
    if (empty($errors)) {
        if ($isNutritionist) {
            // Prepara a query de inserção
            $sql = "INSERT INTO nutricionistas (nome_completo, nome_usuario, especialidade, email, cnr_inscricao, senha)
                    VALUES (?, ?, ?, ?, ?, ?)";

            if ($stmt = $conn->prepare($sql)) {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt->bind_param("ssssss", $nomeCompleto, $nomeUsuario, $especialidade, $email, $cnrInscricao, $senhaHash);

                if ($stmt->execute()) {
                    echo "Nutricionista cadastrado com sucesso!";
                } else {
                    echo "Erro ao cadastrar nutricionista: " . $stmt->error;
                }
                $stmt->close();
            }
        }

        if ($isClient) {
            // Prepara a query de inserção
            $sql = "INSERT INTO clientes (nome_completo, nome_usuario, email, senha)
                    VALUES (?, ?, ?, ?)";

            if ($stmt = $conn->prepare($sql)) {
                $senhaHash = password_hash($senhaCliente, PASSWORD_DEFAULT);
                $stmt->bind_param("ssss", $nomeCompletoCliente, $nomeUsuarioCliente, $emailCliente, $senhaHash);

                if ($stmt->execute()) {
                    echo "Cliente cadastrado com sucesso!";
                } else {
                    echo "Erro ao cadastrar cliente: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    } else {
        // Mostra os erros
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }

    // Fecha a conexão
    $conn->close();
} else {
    echo "Método de requisição inválido.";
}
?>
