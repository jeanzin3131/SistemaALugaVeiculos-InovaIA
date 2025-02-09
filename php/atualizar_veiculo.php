<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $acao = $_POST['acao'] ?? null;

    if ($id && $acao) {
        $status = $acao === 'aprovar' ? 'aprovado' : 'rejeitado';

        $query = "UPDATE veiculos SET status = :status WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            echo "Veículo $status com sucesso!";
        } else {
            echo "Erro ao atualizar o veículo.";
        }
    } else {
        echo "Dados inválidos.";
    }
} else {
    echo "Método inválido.";
}
?>