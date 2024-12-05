<?php
session_start();

include ('conexao.php');

// Suponhamos que o ID do cliente esteja armazenado na sessão
$id_cliente = $_SESSION['id_cliente']; 

// Recuperar dados do cliente
$query = "SELECT nome_cliente, email_cliente, arroba, localizacao, descricao, foto_perfil FROM cliente WHERE id_cliente = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id_cliente);
$stmt->execute();
$result = $stmt->get_result();
$cliente = $result->fetch_assoc();

// Definir variáveis para o perfil
$nome_cliente = $cliente['nome_cliente'];
$email_cliente = $cliente['email_cliente'];
$arroba = $cliente['arroba'];
$localizacao = $cliente['localizacao'];
$descricao = $cliente['descricao'];
$profile_pic = !empty($cliente['foto_perfil']) ? $cliente['foto_perfil'] : 'default-profile.png';

// Recuperar consultas do cliente
$consultas_query = "SELECT con_desc, con_horario FROM consulta WHERE id_cliente = ?";
$stmt = $conn->prepare($consultas_query);
$stmt->bind_param('i', $id_cliente);
$stmt->execute();
$consultas_result = $stmt->get_result();
$consultas = $consultas_result->fetch_all(MYSQLI_ASSOC);

// Fechar a conexão com o banco
$stmt->close();
$conn->close();
?>

<?php
// Configuração do upload da imagem
$target_dir = "uploads/"; // Pasta onde as imagens serão salvas
$target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Verifica se o arquivo é uma imagem
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "O arquivo não é uma imagem.";
        $uploadOk = 0;
    }
}

// Verifica se o arquivo já existe
if (file_exists($target_file)) {
    echo "Desculpe, o arquivo já existe.";
    $uploadOk = 0;
}

// Limita o tamanho do arquivo (por exemplo, 500KB)
if ($_FILES["profile_pic"]["size"] > 500000) {
    echo "Desculpe, o arquivo é muito grande.";
    $uploadOk = 0;
}

// Verifica o formato do arquivo (apenas JPG, PNG e JPEG permitidos)
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    echo "Desculpe, apenas arquivos JPG, JPEG e PNG são permitidos.";
    $uploadOk = 0;
}

// Se o arquivo for válido, faz o upload
if ($uploadOk == 0) {
    echo "Desculpe, seu arquivo não pode ser enviado.";
} else {
    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
        echo "O arquivo " . htmlspecialchars(basename($_FILES["profile_pic"]["name"])) . " foi enviado.";
    } else {
        echo "Desculpe, houve um erro ao enviar o seu arquivo.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente</title>
    <style>
              body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }

        .profile-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: auto;
        }

        .profile-image {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-image img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .location, .description {
            margin-top: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .consultas {
            margin-top: 20px;
        }

        .consultas h2 {
            margin: 0;
        }

        .consultas ul {
            list-style: none;
            padding: 0;
        }

        .consultas li {
            background: #e9ecef;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h1>Perfil do Usuário</h1>
        <div class="profile-image">
            <img id="profilePic" src="<?php echo $profile_pic; ?>" alt="Foto de Perfil">
            <input type="file" id="fileInput" accept="image/*" hidden>
            <button id="uploadBtn">Trocar Foto</button>
        </div>
        <button id="saveBtn">Salvar</button>
        
        <div class="location">
            <label for="location">Localização:</label>
            <input type="text" id="location" value="<?php echo $localizacao; ?>" placeholder="Digite sua localização">
        </div>

        <div class="description">
            <label for="description">Descrição:</label>
            <textarea id="description" rows="4" placeholder="Escreva uma breve descrição sobre você"><?php echo $descricao; ?></textarea>
        </div>

        <div class="consultas">
            <h2>Consultas Marcadas</h2>
            <ul id="consultasList">
                <?php
                    // Exibir as consultas
                    if (!empty($consultas)) {
                        foreach ($consultas as $consulta) {
                            echo "<li>{$consulta['con_desc']} - {$consulta['con_horario']}</li>";
                        }
                    } else {
                        echo "<li>Nenhuma consulta agendada.</li>";
                    }
                ?>
            </ul>
        </div>
    </div>
    
    <script>
        document.getElementById('uploadBtn').addEventListener('click', function() {
            document.getElementById('fileInput').click();
        });

        document.getElementById('fileInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePic').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('saveBtn').addEventListener('click', function() {
            const location = document.getElementById('location').value;
            const description = document.getElementById('description').value;

            alert(`Foto de perfil salva com sucesso!\nLocalização: ${location}\nDescrição: ${description}`);
            
            // Aqui você implementa a lógica de envio para o servidor
            // (ex: usando fetch ou AJAX para salvar os dados no servidor)
        });
    </script>
</body>
</html>

