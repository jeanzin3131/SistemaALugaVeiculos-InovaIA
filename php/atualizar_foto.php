<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $veiculo_id = $_POST['veiculo_id'];
    $nova_foto = $_FILES['nova_foto'];

    // Verifica se o arquivo foi enviado sem erros
    if ($nova_foto['error'] === 0) {
        $extensao = pathinfo($nova_foto['name'], PATHINFO_EXTENSION);
        $nome_arquivo = uniqid() . '.' . $extensao;
        $caminho_upload = "../uploads/veiculos/" . $nome_arquivo;

        if (move_uploaded_file($nova_foto['tmp_name'], $caminho_upload)) {
            // Atualiza o caminho da nova foto no banco de dados
            $query = "UPDATE veiculos SET foto = :foto WHERE id = :veiculo_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['foto' => $caminho_upload, 'veiculo_id' => $veiculo_id]);

            header("Location: ../public/meus_veiculos.php");
            exit();
        } else {
            die("Erro ao mover o arquivo para o servidor.");
        }
    } else {
        die("Erro no upload da foto.");
    }
} else {
    header("Location: ../public/meus_veiculos.php");
    exit();
}