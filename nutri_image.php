<?php

include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Gerencia o envio de arquivos para a foto de perfil
    if (isset($_FILES['profilePicture'])) {
        $errors = [];
        $file_name = $_FILES['profilePicture']['name'];
        $file_size = $_FILES['profilePicture']['size'];
        $file_tmp = $_FILES['profilePicture']['tmp_name'];
        $file_type = $_FILES['profilePicture']['type'];
        $file_parts = explode('.', $_FILES['profilePicture']['name']);
        $file_ext = strtolower(end($file_parts));

        $extensions = ["jpeg", "jpg", "png", "gif"];
        
        if (in_array($file_ext, $extensions) === false) {
            $errors[] = "Extensão não permitida, escolha uma imagem JPEG, PNG ou GIF.";
        }
        
        if ($file_size > 2097152) {
            $errors[] = 'O tamanho do arquivo deve ser menor que 2 MB';
        }
        
        if (empty($errors)) {
            $upload_dir = 'uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $unique_file_name = uniqid() . '_' . $file_name;
            if (move_uploaded_file($file_tmp, $upload_dir . $unique_file_name)) {
                echo "Imagem enviada com sucesso: " . $unique_file_name;
            } else {
                echo "Erro ao enviar imagem: " . error_get_last()['message'];
            }
        } else {
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
        }
    }

    // Experiencia do nutricionista sendo salva
    if (isset($_POST['experiencia'])) {
        $experiencia = $_POST['experiencia'];
        
        echo "Experiência salva: " . htmlspecialchars($experiencia);
    }

    // Pega a imagem enviada
    if (isset($_FILES['formacaoImage'])) {
        // Salva a imagem no servidor
        $errors = [];
        $file_name = $_FILES['formacaoImage']['name'];
        $file_size = $_FILES['formacaoImage']['size'];
        $file_tmp = $_FILES['formacaoImage']['tmp_name'];
        $file_type = $_FILES['formacaoImage']['type'];
        $file_parts = explode('.', $_FILES['formacaoImage']['name']);
        $file_ext = strtolower(end($file_parts));

        $extensions = ["jpeg", "jpg", "png", "gif"];
        
        if (in_array($file_ext, $extensions) === false) {
            $errors[] = "Extensão não permitida, escolha uma imagem JPEG, PNG ou GIF.";
        }
        
        if ($file_size > 2097152) {
            $errors[] = 'O tamanho do arquivo deve ser menor que 2 MB';
        }
        
        if (empty($errors)) {
            $upload_dir = 'uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $unique_file_name = uniqid() . '_' . $file_name;
            if (move_uploaded_file($file_tmp, $upload_dir . $unique_file_name)) {
                echo "Imagem de formação enviada com sucesso: " . $unique_file_name;
            } else {
                echo "Erro ao enviar imagem de formação: " . error_get_last()['message'];
            }
        } else {
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
        }
    }
} else {    echo "Método de requisição inválido.";
}