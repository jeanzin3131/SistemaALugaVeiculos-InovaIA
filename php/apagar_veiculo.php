<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $veiculo_id = $_POST['veiculo_id'];

    $query = "DELETE FROM veiculos WHERE id = :veiculo_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['veiculo_id' => $veiculo_id]);

    header("Location: ../public/meus_veiculos.php");
    exit();
}