<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'locatario') {
    header("Location: ../login.html");
    exit();
}

require_once '../config/db.php';

// Verificar se o ID da reserva foi passado pela URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: minhas_reservas.php");
    exit();
}

$reserva_id = $_GET['id'];
$locatario_id = $_SESSION['usuario_id'];

// Buscar os detalhes da reserva no banco de dados
try {
    $sql = "SELECT r.id AS reserva_id, r.data_reserva, r.status_reserva, r.data_inicio, r.data_fim, 
                   v.modelo, v.marca, v.ano, v.foto, v.status AS status_veiculo, 
                   u.nome AS locador_nome, u.email AS locador_email, u.telefone AS locador_telefone
            FROM reservas r
            INNER JOIN veiculos v ON r.veiculo_id = v.id
            INNER JOIN usuarios u ON v.locador_id = u.id
            WHERE r.id = :reserva_id AND r.locatario_id = :locatario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':reserva_id' => $reserva_id, ':locatario_id' => $locatario_id]);
    $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reserva) {
        header("Location: minhas_reservas.php");
        exit();
    }
} catch (PDOException $e) {
    die("Erro ao buscar detalhes da reserva: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #198754; /* Tema verde */
        }
        .btn-secondary {
            background-color: #198754; /* Botão verde */
            border: none;
        }
        .btn-secondary:hover {
            background-color: #146c43; /* Verde mais escuro */
        }
        .card img {
            max-height: 300px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .card img:hover {
            transform: scale(1.05);
        }
        .card {
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard_locatario.php"><i class="fas fa-car"></i> DirigeAí - Locatário</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="buscar_veiculos.php"><i class="fas fa-search"></i> Buscar Veículos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="minhas_reservas.php"><i class="fas fa-list"></i> Minhas Reservas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../php/logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h1 class="mb-4 text-success"><i class="fas fa-info-circle"></i> Detalhes da Reserva</h1>
        <div class="card">
            <img src="<?= htmlspecialchars($reserva['foto']); ?>" class="card-img-top" alt="Imagem do veículo">
            <div class="card-body">
                <h5 class="card-title text-success"><?= htmlspecialchars($reserva['modelo']) . ' - ' . htmlspecialchars($reserva['marca']); ?></h5>
                <p class="card-text">
                    <strong><i class="fas fa-calendar-alt"></i> Ano:</strong> <?= htmlspecialchars($reserva['ano']); ?><br>
                    <strong><i class="fas fa-info-circle"></i> Status do Veículo:</strong> <?= htmlspecialchars($reserva['status_veiculo']); ?><br>
                    <strong><i class="fas fa-check-circle"></i> Status da Reserva:</strong> <?= htmlspecialchars($reserva['status_reserva']); ?><br>
                    <strong><i class="fas fa-clock"></i> Data da Reserva:</strong> <?= date('d/m/Y H:i', strtotime($reserva['data_reserva'])); ?><br>
                    <strong><i class="fas fa-calendar-week"></i> Período:</strong> <?= date('d/m/Y', strtotime($reserva['data_inicio'])) . ' - ' . date('d/m/Y', strtotime($reserva['data_fim'])); ?>
                </p>
                <hr>
                <h5 class="text-success"><i class="fas fa-user"></i> Informações do Locador</h5>
                <p class="card-text">
                    <strong><i class="fas fa-user"></i> Nome:</strong> <?= htmlspecialchars($reserva['locador_nome']); ?><br>
                    <strong><i class="fas fa-envelope"></i> E-mail:</strong> <?= htmlspecialchars($reserva['locador_email']); ?><br>
                    <strong><i class="fas fa-phone"></i> Telefone:</strong> <?= htmlspecialchars($reserva['locador_telefone']); ?>
                </p>
                <a href="minhas_reservas.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>