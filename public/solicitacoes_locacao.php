<?php if (isset($mensagem_sucesso)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($mensagem_sucesso); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'locador') {
    header("Location: ../login.html");
    exit();
}

require_once '../config/db.php';

$locador_id = $_SESSION['usuario_id'];

try {
    // Buscar solicitações de locação pendentes
    $sql = "SELECT r.id AS reserva_id, r.data_reserva, r.data_inicio, r.data_fim, r.status_reserva,
                   v.modelo, v.marca, v.ano, 
                   u.nome AS locatario_nome, u.email AS locatario_email, u.telefone AS locatario_telefone
            FROM reservas r
            INNER JOIN veiculos v ON r.veiculo_id = v.id
            INNER JOIN usuarios u ON r.locatario_id = u.id
            WHERE v.locador_id = :locador_id AND r.status_reserva = 'pendente'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':locador_id' => $locador_id]);
    $solicitacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar solicitações: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitações de Locação - DirigeAí</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #198754; /* Tema verde */
        }
        .navbar-brand {
            font-weight: bold;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-accept {
            background-color: #198754; /* Verde para aceitar */
            border: none;
        }
        .btn-accept:hover {
            background-color: #146c43; /* Verde escuro */
        }
        .btn-reject {
            background-color: #dc3545; /* Vermelho para rejeitar */
            border: none;
        }
        .btn-reject:hover {
            background-color: #b02a37; /* Vermelho escuro */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard_locador.php">DirigeAí - Locador</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard_locador.php"><i class="fas fa-home me-2"></i>Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../php/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h1 class="mb-4">Solicitações de Locação</h1>
        <?php if (empty($solicitacoes)): ?>
            <p class="text-muted">Nenhuma solicitação pendente no momento.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($solicitacoes as $solicitacao): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($solicitacao['modelo']) . ' - ' . htmlspecialchars($solicitacao['marca']); ?></h5>
                                <p class="card-text">
                                    <strong>Locatário:</strong> <?= htmlspecialchars($solicitacao['locatario_nome']); ?><br>
                                    <strong>E-mail:</strong> <?= htmlspecialchars($solicitacao['locatario_email']); ?><br>
                                    <strong>Telefone:</strong> <?= htmlspecialchars($solicitacao['locatario_telefone']); ?><br>
                                    <strong>Período:</strong> <?= date('d/m/Y', strtotime($solicitacao['data_inicio'])) . ' - ' . date('d/m/Y', strtotime($solicitacao['data_fim'])); ?><br>
                                    <strong>Data da Solicitação:</strong> <?= date('d/m/Y H:i', strtotime($solicitacao['data_reserva'])); ?>
                                </p>
                                <div class="d-flex justify-content-between">
                                    <form method="POST" action="../php/processar_solicitacao.php">
                                        <input type="hidden" name="reserva_id" value="<?= $solicitacao['reserva_id']; ?>">
                                        <input type="hidden" name="acao" value="aceitar">
                                        <button type="submit" class="btn btn-accept"><i class="fas fa-check me-2"></i>Aceitar</button>
                                    </form>
                                    <form method="POST" action="../php/processar_solicitacao.php">
                                        <input type="hidden" name="reserva_id" value="<?= $solicitacao['reserva_id']; ?>">
                                        <input type="hidden" name="acao" value="rejeitar">
                                        <button type="submit" class="btn btn-reject"><i class="fas fa-times me-2"></i>Rejeitar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>