<?php
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

    // se há errrps
    if (empty($errors)) {
        if ($isNutritionist) {
 echo "Nutricionista cadastrado com sucesso:<br>";
            echo "Nome Completo: $nomeCompleto<br>";
            echo "Nome de Usuário: $nomeUsuario<br>";
            echo "Especialidade: $especialidade<br>";
            echo "Email: $email<br>";
            echo "CNR-5 Inscrição: $cnrInscricao<br>";
            echo "Senha: " . password_hash($senha, PASSWORD_DEFAULT) . "<br>";
        }

        if ($isClient) {
            echo "Cliente cadastrado com sucesso:<br>";
            echo "Nome Completo: $nomeCompletoCliente<br>";
            echo "Nome de Usuário: $nomeUsuarioCliente<br>";
            echo "Email: $emailCliente<br>";
            echo "Senha: " . password_hash($senhaCliente, PASSWORD_DEFAULT) . "<br>";
        }
    } else {
        // Mostra os erros
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
} else {
    echo "Método de requisição inválido.";
}