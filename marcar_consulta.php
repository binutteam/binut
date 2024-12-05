<?php
session_start();
include_once('conexao.php');

// Verificar se o nutricionista foi passado na URL
if (!isset($_GET['id_nutri'])) {
    die("Nutricionista não selecionado.");
}

// Obter o ID do nutricionista da URL
$id_nutri = $_GET['id_nutri'];

// Consultar o nutricionista no banco de dados
$nutri_sql = "SELECT * FROM nutricionista WHERE id_nutri = ?";
$stmt = $conn->prepare($nutri_sql);

// Verificar se a consulta foi preparada corretamente
if ($stmt === false) {
    die("Erro na preparação da consulta: " . $conn->error);
}

$stmt->bind_param("i", $id_nutri);
$stmt->execute();
$result_nutri = $stmt->get_result();

// Verificar se o nutricionista foi encontrado
if ($result_nutri->num_rows === 0) {
    die("Nutricionista não encontrado.");
}

$nutri = $result_nutri->fetch_assoc();

// Consultar a disponibilidade do nutricionista
$disponibilidade_sql = "SELECT * FROM disponibilidade WHERE id_nutri = ?";
$stmt = $conn->prepare($disponibilidade_sql);

// Verificar se a consulta foi preparada corretamente
if ($stmt === false) {
    die("Erro na preparação da consulta: " . $conn->error);
}

$stmt->bind_param("i", $id_nutri);
$stmt->execute();
$result_disponibilidade = $stmt->get_result();

// Verificar se a disponibilidade foi encontrada
if ($result_disponibilidade->num_rows === 0) {
    $disponibilidade = null; // Se não houver disponibilidade, definimos como null
} else {
    $disponibilidade = $result_disponibilidade->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcar Consulta</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right, #f7f9fc, #e9f1f8);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 40px;
            width: 100%;
            max-width: 600px;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            color: #318549;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
            font-size: 16px;
        }

        input[type="datetime-local"], button {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="datetime-local"]:focus, button:focus {
            outline: none;
            border-color: #318549;
            box-shadow: 0 0 4px rgba(49, 133, 73, 0.4);
        }

        button {
            background: #318549;
            color: white;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        button:hover {
            background: #27693c;
        }

        .message {
            text-align: center;
            font-size: 14px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Marcar Consulta</h1>
        <form action="processa_agendamento.php" method="POST">
            <input type="hidden" name="id_nutri" value="<?php echo htmlspecialchars($id_nutri); ?>">
            
            <!-- Exibindo a disponibilidade do nutricionista -->
            <div class="bg-gray-100 p-4 text-center">
                <h5 class="text-gray-800 font-semibold">Disponível</h5>
                <p class="text-gray-600">
                    <?= isset($disponibilidade['disponibilidade']) 
                        ? nl2br(htmlspecialchars($disponibilidade['disponibilidade'])) 
                        : 'Sem disponibilidade registrada.'; ?>
                </p>
                <p class="mt-2">Teleconsulta R$ 
                    <?= isset($nutri['preco_teleconsulta']) 
                        ? number_format($nutri['preco_teleconsulta'], 2, ',', '.') 
                        : 'Não informado'; ?>
                </p>
                <p class="mt-2">Consulta Presencial R$ 
                    <?= isset($nutri['con_preco']) 
                        ? number_format($nutri['con_preco'], 2, ',', '.') 
                        : 'Não informado'; ?>
                </p>
            </div>

            <!-- Formulário para agendar horário -->
            <div class="form-group">
                <label for="horario_agendado">Escolha o Horário:</label>
                <input type="datetime-local" id="horario_agendado" name="horario_agendado" required>
            </div>
            <button type="submit">Confirmar Agendamento</button>
        </form>

        <div class="message">
            <?php
            if (isset($_GET['error'])) {
                echo htmlspecialchars($_GET['error']);
            }
            ?>
        </div>
    </div>
</body>
</html>
