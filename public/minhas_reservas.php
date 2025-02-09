<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'locatario') {
    header("Location: ../login.html");
    exit();
}

require_once '../config/db.php';

$locatario_id = $_SESSION['usuario_id'];

// Busca as reservas do locatário no banco de dados
try {
    $sql = "SELECT r.id AS reserva_id, r.data_reserva, v.modelo, v.marca, v.ano, v.foto, v.status
            FROM reservas r
            INNER JOIN veiculos v ON r.veiculo_id = v.id
            WHERE r.locatario_id = :locatario_id
            ORDER BY r.data_reserva DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':locatario_id' => $locatario_id]);
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar reservas: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #198754; /* Verde principal */
        }
        .btn-primary {
            background-color: #198754; /* Verde principal */
            border: none;
        }
        .btn-primary:hover {
            background-color: #146c43; /* Verde mais escuro */
        }
        .card img {
            max-height: 200px;
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
            <a class="navbar-brand" href="dashboard_locatario.php"><i class="fas fa-car"></i> AlugaVale - Locatário</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="buscar_veiculos.php"><i class="fas fa-search"></i> Buscar Veículos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="minhas_reservas.php"><i class="fas fa-list"></i> Minhas Reservas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../php/logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h1 class="mb-4 text-success"><i class="fas fa-list"></i> Minhas Reservas</h1>

        <?php if (empty($reservas)): ?>
            <p class="text-muted">Você ainda não realizou nenhuma reserva.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($reservas as $reserva): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="<?= htmlspecialchars($reserva['foto']); ?>" class="card-img-top" alt="Imagem do veículo">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($reserva['modelo']) . ' - ' . htmlspecialchars($reserva['marca']); ?></h5>
                                <p class="card-text">
                                    <strong><i class="fas fa-calendar-alt"></i> Ano:</strong> <?= htmlspecialchars($reserva['ano']); ?><br>
                                    <strong><i class="fas fa-info-circle"></i> Status:</strong> <?= htmlspecialchars($reserva['status']); ?><br>
                                    <strong><i class="fas fa-clock"></i> Data da Reserva:</strong> <?= date('d/m/Y H:i', strtotime($reserva['data_reserva'])); ?>
                                </p>
                                <a href="detalhes_reserva.php?id=<?= $reserva['reserva_id']; ?>" class="btn btn-primary"><i class="fas fa-info-circle"></i> Ver Detalhes</a>
                            </div>
                            <div class="card-footer text-center">
                                <a href="dashboard_locatario.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>