<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    die(json_encode(["status" => "erro", "mensagem" => "Acesso negado."]));
}

// Receber os dados da requisição
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;
$status = $data['status'] ?? null;

if (!$id || !in_array($status, ['aprovado', 'rejeitado'])) {
    die(json_encode(["status" => "erro", "mensagem" => "Dados inválidos."]));
}

// Atualiza o status no banco de dados
$stmt = $pdo->prepare("UPDATE documentos_usuarios SET status = ? WHERE id = ?");
if ($stmt->execute([$status, $id])) {
    echo json_encode(["status" => "sucesso", "mensagem" => "Status atualizado."]);
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Erro ao atualizar."]);
}
?>