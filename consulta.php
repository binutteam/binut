<?php
// Iniciar a sessão
session_start();

// Conectar ao banco de dados
include_once('conexao.php');



$id_nutri = intval($_GET['id_nutri']); // Sanitizar o ID recebido

// Consultar informações do nutricionista e horários disponíveis
$sql = "SELECT nome_nutri, CRN_nutri, disponibilidade, preco_teleconsulta, con_preco 
        FROM nutricionista 
        LEFT JOIN disponibilidade ON nutricionista.id_nutri = disponibilidade.id_nutri 
        WHERE nutricionista.id_nutri = ?";

$stmt = $conn->prepare($sql);

// Verificar se a preparação da query foi bem-sucedida
if (!$stmt) {
    die("Erro ao preparar consulta: " . $conn->error);
}

// Vincular o parâmetro e executar a query
$stmt->bind_param("i", $id_nutri);
$stmt->execute();
$result = $stmt->get_result();

// Verificar se o nutricionista existe
if ($result->num_rows === 0) {
    die("Erro: Nutricionista não encontrado.");
}

$nutricionista = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta - <?= htmlspecialchars($nutricionista['nome_nutri']); ?></title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-green-700 mb-4">Agendar Consulta</h1>
        <div class="bg-white p-6 rounded shadow-lg">
            <img src="<?= !empty($nutricionista['foto']) ? htmlspecialchars($nutricionista['foto']) : 'default.jpg'; ?>" 
                 alt="Foto do nutricionista" class="w-32 h-32 rounded-full mx-auto mb-4">
            <h2 class="text-lg font-bold text-gray-800"><?= htmlspecialchars($nutricionista['nome_nutri']); ?></h2>
            <p class="text-gray-600">CRN: <?= htmlspecialchars($nutricionista['CRN_nutri']); ?></p>
            <p class="mt-2">Teleconsulta: R$ 
                <?= isset($nutricionista['preco_teleconsulta']) 
                    ? number_format($nutricionista['preco_teleconsulta'], 2, ',', '.') 
                    : 'Não informado'; ?>
            </p>
            <p class="mt-2">Consulta Presencial: R$ 
                <?= isset($nutricionista['con_preco']) 
                    ? number_format($nutricionista['con_preco'], 2, ',', '.') 
                    : 'Não informado'; ?>
            </p>
            <h3 class="text-lg font-bold mt-4">Horários Disponíveis:</h3>
            <p class="text-gray-600">
                <?= !empty($nutricionista['disponibilidade']) 
                    ? nl2br($nutricionista['disponibilidade']) 
                    : 'Sem horários disponíveis no momento.'; ?>
            </p>
            <a href="marcar_consulta.php?id_nutri=<?= $id_nutri; ?>" 
               class="block bg-green-700 text-white text-center py-2 px-4 rounded mt-4">Agendar</a>
        </div>
    </div>
</body>
</html>
