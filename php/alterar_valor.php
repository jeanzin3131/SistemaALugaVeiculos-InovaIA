<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $veiculo_id = $_POST['veiculo_id'];
    $valor_diaria = $_POST['valor_diaria'];

    $query = "UPDATE veiculos SET valor_diaria = :valor_diaria WHERE id = :veiculo_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['valor_diaria' => $valor_diaria, 'veiculo_id' => $veiculo_id]);

    header("Location: ../public/meus_veiculos.php");
    exit();
}