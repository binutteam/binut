<?php

session_start();

// Conectar ao banco de dados
include_once('conexao.php');

// Consultar os nutricionistas cadastrados no banco de dados
$sql = "SELECT * FROM nutricionista";
$result = $conn->query($sql);

// Verificar se a consulta retornou algum nutricionista
if ($result->num_rows > 0) {
    $nutricionistas = $result->fetch_all(MYSQLI_ASSOC); // Armazenar todos os nutricionistas em um array
} else {
    $nutricionistas = []; // Caso não haja nutricionistas cadastrados
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Agendamento de Nutricionistas</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100">
  <header class="bg-green-700 text-white py-6 flex justify-between items-center px-6">
    <h3 class="text-xl">Consulte um de nossos Nutricionistas</h3>
    <a href="sair.php" class="sair">sair</a>
    
  </header>

  <div class="container mx-auto p-6">
    <div class="flex flex-wrap justify-center gap-6">
      <?php if (empty($nutricionistas)): ?>
        <p class="text-center text-lg text-gray-600 w-full">Nenhum nutricionista cadastrado no momento.</p>
      <?php else: ?>
        <?php foreach ($nutricionistas as $nutri): ?>
          <?php
            // Consultar a disponibilidade do nutricionista
            $disponibilidade_sql = "SELECT * FROM disponibilidade WHERE id_nutri = ?";
            $stmt = $conn->prepare($disponibilidade_sql);
            $stmt->bind_param("i", $nutri['id_nutri']);
            $stmt->execute();
            $result_disponibilidade = $stmt->get_result();
            $disponibilidade = $result_disponibilidade->fetch_assoc();
          ?>
          <div class="bg-white border border-gray-300 rounded-lg shadow-lg w-72 overflow-hidden transform transition-transform hover:-translate-y-2">
            <img alt="Foto de perfil de um nutricionista" class="w-full h-44 object-cover"  height="180" src="<?= !empty($nutri['foto']) ? htmlspecialchars($nutri['foto']) : 'default.jpg'; ?>" width="280"/>
            <div class="p-4 text-gray-800">
              <h4 class="text-green-700 text-lg font-semibold"><?= htmlspecialchars($nutri['nome_nutri']); ?></h4>
              <p class="mt-2">Nutricionista 
              <a class="text-green-700" href="opinioes.php?id_nutri=<?= $nutri['id_nutri']; ?>">Opinioes</a></p>
              <p class="mt-2">CRN <?= htmlspecialchars($nutri['CRN_nutri']); ?></p>
              <p class="mt-2">Teleconsulta R$ 
  <?= isset($nutri['preco_teleconsulta']) 
      ? number_format($nutri['preco_teleconsulta'], 2, ',', '.') 
      : 'Não informado'; ?>
</p>
              <p class="mt-2"> ConsultaPresencial R$<?= isset($nutri['con_preco']) 
      ? number_format($nutri['con_preco'], 2, ',', '.') 
      : 'Não informado'; ?></p>

              <button class="bg-green-700 text-white py-2 px-4 rounded mt-4 w-full"><a class="text-white" href="consulta.php?id_nutri=<?= $nutri['id_nutri']; ?>">Consultar</a></button>
            </div>
            <div class="bg-gray-100 p-4 text-center">
              <h5 class="text-gray-800 font-semibold">Disponivel</h5>
              <div class="bg-gray-100 p-4 text-center">
  <p class="text-gray-600">
    <?= isset($disponibilidade['disponibilidade']) 
        ? nl2br($disponibilidade['disponibilidade']) 
        : 'Sem disponibilidade registrada.'; ?>
  </p>
  <h5 class="text-gray-800 font-semibold mt-4">Indisponível</h5>
  <p class="text-gray-600">
    <?= isset($disponibilidade['indisponibilidade']) 
        ? nl2br($disponibilidade['indisponibilidade']) 
        : 'Sem dias indisponíveis registrados.'; ?>
  </p>
</div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
