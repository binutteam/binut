<?php
session_start();

// Verificar se a sessão está correta (apenas para depuração)
var_dump($_SESSION);  // Verifica se o user_id está presente na sessão

// Conectar ao banco de dados
include_once('conexao.php');

// Verificar se o nutricionista foi passado como parâmetro
if (!isset($_GET['id_nutri'])) {
    die("Nutricionista não selecionado.");
}

$id_nutri = $_GET['id_nutri']; 

// Consultar as opiniões para o nutricionista
$sql = "SELECT opiniao_nutricionista.*, cliente.nome_cliente FROM opiniao_nutricionista 
        JOIN cliente ON opiniao_nutricionista.id_cliente = cliente.id_cliente 
        WHERE id_nutri = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_nutri);
$stmt->execute();
$result = $stmt->get_result();
$opiniao_nutri = $result->fetch_all(MYSQLI_ASSOC);

// Verificar se o formulário de adicionar opinião foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {  // Use 'user_id' aqui
    $id_cliente = $_SESSION['user_id'];  // ID de quem logou seja nutri ou cliente
    $opiniao = $_POST['opiniao'];
    
    // Inserir nova opinião no banco de dados
    $sql = "INSERT INTO opiniao_nutricionista (id_cliente, id_nutri, opiniao) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $id_cliente, $id_nutri, $opiniao);
    $stmt->execute();
    
    // Redirecionar após o envio
    header("Location: opinioes.php?id_nutri=$id_nutri");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opiniões sobre Nutricionista</title>
    <link rel="stylesheet" href="nutri.css">
    <style>
        /* Estilo geral */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #318549;
            color: white;
            padding: 15px 0;
            text-align: center;
        }

        header h3 {
            margin: 0;
            font-size: 24px;
        }

        .opiniao-container {
            max-width: 800px;
            margin: 30px auto;
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h4 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }

        /* Estilo das opiniões */
        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background-color: #f1f1f1;
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        li p {
            margin: 5px 0;
            color: #555;
        }

        .opiniao-container textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            resize: vertical;
        }

        .opiniao-container button {
            background-color: #318549;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .opiniao-container button:hover {
            background-color: #276c3c;
        }

        footer {
            background-color: #318549;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        footer span {
            font-size: 14px;
        }

        .no-opinioes {
            text-align: center;
            font-size: 16px;
            color: #888;
        }

        .cliente-info {
            font-weight: bold;
            color: #318549;
        }

    </style>
</head>
<body>
    <header>
        <h3>Opiniões sobre o Nutricionista</h3>
    </header>

    <div class="opiniao-container">
        <h4>Opiniões de outros clientes</h4>
        
        <?php if (empty($opiniao_nutri)): ?>
            <p class="no-opinioes">Não há opiniões para este nutricionista ainda.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($opiniao_nutri as $opiniao): ?>
                    <li>
                        <p><span class="cliente-info"><?= htmlspecialchars($opiniao['nome_cliente']); ?>:</span></p>
                        <p><?= htmlspecialchars($opiniao['opiniao']); ?></p>
                        <p><small>Postado em: <?= $opiniao['data_postagem']; ?></small></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_id'])): ?> 
            <h4>Deixe sua opinião</h4>
            <form action="opinioes.php?id_nutri=<?= $id_nutri; ?>" method="POST">
                <textarea name="opiniao" rows="4" required placeholder="Deixe aqui sua opnião sobre o atendimento desse nutricionista..."></textarea><br><br>
                <button type="submit">Enviar Opinião</button>
            </form>
        <?php else: ?>
            <p>Você precisa estar logado como para deixar uma opinião.</p>
        <?php endif; ?>
    </div>

    <footer>
        <span>BiNUT</span> | &copy; 2024 todos os direitos reservados
    </footer>
</body>
</html>
