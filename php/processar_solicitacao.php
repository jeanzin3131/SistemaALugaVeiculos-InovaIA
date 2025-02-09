<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'locador') {
    header("Location: ../login.html");
    exit();
}

require_once '../config/db.php';

// Verificar se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserva_id'], $_POST['acao'])) {
    $reserva_id = $_POST['reserva_id'];
    $acao = $_POST['acao'];
    $locador_id = $_SESSION['usuario_id'];

    try {
        // Verificar se a reserva pertence a um veículo do locador
        $sql = "SELECT r.id 
                FROM reservas r
                INNER JOIN veiculos v ON r.veiculo_id = v.id
                WHERE r.id = :reserva_id AND v.locador_id = :locador_id AND r.status_reserva = 'pendente'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':reserva_id' => $reserva_id, ':locador_id' => $locador_id]);
        $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reserva) {
            header("Location: ver_solicitacoes.php?erro=nao_autorizado");
            exit();
        }

        // Processar a ação (aceitar ou rejeitar)
        if ($acao === 'aceitar') {
            $sql = "UPDATE reservas SET status_reserva = 'aceita' WHERE id = :reserva_id";
        } elseif ($acao === 'rejeitar') {
            $sql = "UPDATE reservas SET status_reserva = 'rejeitada' WHERE id = :reserva_id";
        } else {
            header("Location: ver_solicitacoes.php?erro=acao_invalida");
            exit();
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':reserva_id' => $reserva_id]);

        // Redirecionar com sucesso
        header("Location: ver_solicitacoes.php?sucesso=acao_realizada");
        exit();
    } catch (PDOException $e) {
        die("Erro ao processar solicitação: " . $e->getMessage());
    }
} else {
    header("Location: ver_solicitacoes.php?erro=dados_invalidos");
    exit();
}