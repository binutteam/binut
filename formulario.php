<?php
// Iniciar a sessão
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    die("Usuário não autenticado.");
}

// Conectar ao banco de dados
include_once('conexao.php');

// A partir de agora, estamos usando o 'user_id' que está na sessão
$id_nutri = $_SESSION['user_id'];  // Alterado de $_SESSION['id_nutri'] para $_SESSION['user_id']

// Processar o formulário de disponibilidade
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar os dados do formulário
    $disponibilidade = $_POST['disponibilidade']; // Dias e horários selecionados
    $indisponibilidade = $_POST['indisponibilidade']; // Dias indisponíveis

    // Limpar dados para evitar SQL injection
    $disponibilidade = $conn->real_escape_string($disponibilidade);
    $indisponibilidade = $conn->real_escape_string($indisponibilidade);

    // Verificar se a disponibilidade foi preenchida
    if (empty($disponibilidade) || empty($indisponibilidade)) {
        $msg = "Por favor, preencha todos os campos de disponibilidade!";
    } else {
        // Inserir os dados de disponibilidade no banco de dados
        $sql = "INSERT INTO disponibilidade (id_nutri, disponibilidade, indisponibilidade) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("iss", $id_nutri, $disponibilidade, $indisponibilidade);
            
            if ($stmt->execute()) {
                $msg = "Disponibilidade registrada com sucesso!";
            } else {
                $msg = "Erro ao registrar disponibilidade: " . $stmt->error;
            }
            $stmt->close();  // Fechar a declaração preparada
        } else {
            $msg = "Erro na preparação da consulta: " . $conn->error;
        }
    }
}

// Fechar a conexão
$conn->close();
?>

<!-- Exibição da mensagem de sucesso ou erro -->
<?php if (isset($msg)): ?>
    <p class="mt-4 text-lg <?= (strpos($msg, 'sucesso') !== false) ? 'text-green-600' : 'text-red-600' ?>">
        <?= $msg; ?>
    </p>
<?php endif; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Disponibilidade</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold text-gray-800">Registrar Disponibilidade de Atendimento</h2>
        <form method="POST" class="mt-4">
            <div class="mb-4">
                <label class="block text-gray-700">Dias e Horários Disponíveis</label>
                <input type="text" id="disponibilidade" name="disponibilidade" class="w-full p-2 border border-gray-300 rounded" placeholder="Selecione as datas e horários">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Dias Indisponíveis</label>
                <input type="text" id="indisponibilidade" name="indisponibilidade" class="w-full p-2 border border-gray-300 rounded" placeholder="Selecione as datas e horários">
            </div>
            <button type="submit" class="bg-green-700 text-white py-2 px-4 rounded">Salvar</button>
        </form>

        <?php if (isset($msg)): ?>
            <p class="mt-4 text-lg <?= (strpos($msg, 'sucesso') !== false) ? 'text-green-600' : 'text-red-600' ?>">
                <?= $msg; ?>
            </p>
        <?php endif; ?>
    </div>

    <script>
        flatpickr("#disponibilidade", {
            mode: "multiple",
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            locale: {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                    longhand: ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'],
                },
                months: {
                    shorthand: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                    longhand: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                },
            }
        });

        flatpickr("#indisponibilidade", {
            mode: "multiple",
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            locale: {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                    longhand: ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'],
                },
                months: {
                    shorthand: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                    longhand: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                },
            }
        });
    </script>
</body>
</html>
