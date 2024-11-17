<?php
include('config.php');

if (isset($_POST['submit'])) {
    $isNutritionista = isset($_POST['nome_nutri']) && !empty($_POST['nome_user_nutri']);
    $isCliente = isset($_POST['nome_cliente']) && !empty($_POST['nome_cliente']);
    $errors = [];

    if ($isNutritionista) {
        $nomeCompleto = htmlspecialchars(trim($_POST['nome_nutri']));
        $nomeUsuario = htmlspecialchars(trim($_POST['nome_user_nutri']));
        $especialidade = htmlspecialchars(trim($_POST['nutri_especialidade']));
        $email = htmlspecialchars(trim($_POST['email_nutri']));
        $CNR_nutri = htmlspecialchars(trim($_POST['CNR_nutri']));
        $senha = htmlspecialchars(trim($_POST['senha_nutri']));
        $idEndereco = isset($_POST['id_endereco']) && !empty($_POST['id_endereco']) ? $_POST['id_endereco'] : NULL;

        // Validação
        if (empty($nomeCompleto) || empty($nomeUsuario) || empty($especialidade) || empty($email) || empty($CNR_nutri) || empty($senha)) {
            $errors[] = "Todos os campos são obrigatórios para nutricionistas.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email inválido.";
        }
    }

    if ($isCliente) {
        $nomeCompletoCliente = htmlspecialchars(trim($_POST['nome_cliente']));
        $nomeUsuarioCliente = htmlspecialchars(trim($_POST['nome_user_cliente']));
        $emailCliente = htmlspecialchars(trim($_POST['email_cliente']));
        $senhaCliente = htmlspecialchars(trim($_POST['senha_cliente']));
        $idEnderecoCliente = isset($_POST['id_endereco_cliente']) && !empty($_POST['id_endereco_cliente']) ? $_POST['id_endereco_cliente'] : NULL;

        // Validação
        if (empty($nomeCompletoCliente) || empty($nomeUsuarioCliente) || empty($emailCliente) || empty($senhaCliente)) {
            $errors[] = "Todos os campos são obrigatórios para clientes.";
        } elseif (!filter_var($emailCliente, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email inválido.";
        }
    }

    if (!isset($_POST['privacy-policy'])) {
        $errors[] = "Você deve concordar com a Política de Privacidade e Termos de Serviço.";
    }

    if (empty($errors)) {
        if ($isNutritionista) {
            // Verifica se já existe um nutricionista com o mesmo email
            $checkEmail = $conn->prepare("SELECT * FROM nutricionista WHERE email_nutri = ?");
            $checkEmail->bind_param("s", $email);
            $checkEmail->execute();
            $result = $checkEmail->get_result();
            if ($result->num_rows > 0) {
                $errors[] = "Já existe um nutricionista cadastrado com este email.";
            } else {
                $sql = "INSERT INTO nutricionista (nome_nutri, email_nutri, CRN_nutri, senha_nutri, id_endereco, data_cadastro)
                        VALUES (?, ?, ?, ?, ?, NOW())";
                if ($stmt = $ conn->prepare($sql)) {
                    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                    $stmt->bind_param("sssss", $nomeCompleto, $email, $CNR_nutri, $senhaHash, $idEndereco);
                    if ($stmt->execute()) {
                        echo "Nutricionista cadastrado com sucesso!";
                    } else {
                        echo "Erro ao cadastrar nutricionista: " . $stmt->error;
                    }
                    $stmt->close();
                }
            }
            $checkEmail->close();
        }

        if ($isCliente) {
            // Verifica se já existe um cliente com o mesmo email
            $checkEmailCliente = $conn->prepare("SELECT * FROM cliente WHERE email_cliente = ?");
            $checkEmailCliente->bind_param("s", $emailCliente);
            $checkEmailCliente->execute();
            $resultCliente = $checkEmailCliente->get_result();
            if ($resultCliente->num_rows > 0) {
                $errors[] = "Já existe um cliente cadastrado com este email.";
            } else {
                $sql = "INSERT INTO cliente (nome_cliente, email_cliente, senha_cliente, id_endereco, data_cadastro)
                        VALUES (?, ?, ?, ?, NOW())";
                if ($stmt = $conn->prepare($sql)) {
                    $senhaHashCliente = password_hash($senhaCliente, PASSWORD_DEFAULT);
                    $stmt->bind_param("ssss", $nomeCompletoCliente, $emailCliente, $senhaHashCliente, $idEnderecoCliente);
                    if ($stmt->execute()) {
                        echo "Cliente cadastrado com sucesso!";
                    } else {
                        echo "Erro ao cadastrar cliente: " . $stmt->error;
                    }
                    $stmt->close();
                }
            }
            $checkEmailCliente->close();
        }
    } else {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }

    $conn->close();
} else {
    echo "Método de requisição inválido.";
}
?>
