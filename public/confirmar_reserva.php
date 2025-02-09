<?php
session_start();
require_once '../config/db.php'; // Conexão com o banco de dados

// Verifica se o locador está logado
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'locador') {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não autorizado.']);
    exit();
}

// Verifica se foi enviado o ID da reserva via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserva_id'])) {
    $reserva_id = intval($_POST['reserva_id']);
    $locador_id = intval($_SESSION['usuario_id']);

    try {
        // Verifica se a reserva pertence a um veículo do locador
        $sqlVerificar = "
            SELECT r.id 
            FROM reservas r
            JOIN veiculos v ON r.veiculo_id = v.id
            WHERE r.id = :reserva_id AND v.locador_id = :locador_id
        ";
        $stmtVerificar = $pdo->prepare($sqlVerificar);
        $stmtVerificar->execute([':reserva_id' => $reserva_id, ':locador_id' => $locador_id]);
        $reserva = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

        if (!$reserva) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Reserva não encontrada ou não pertence a você.']);
            exit();
        }

        // Atualiza o status da reserva para 'confirmada'
        $sqlConfirmar = "UPDATE reservas SET status_reserva = 'confirmada' WHERE id = :reserva_id";
        $stmtConfirmar = $pdo->prepare($sqlConfirmar);
        $stmtConfirmar->execute([':reserva_id' => $reserva_id]);

        echo json_encode(['status' => 'sucesso', 'mensagem' => 'Reserva confirmada com sucesso.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao confirmar reserva: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Dados inválidos.']);
}
?>