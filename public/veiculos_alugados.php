<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'locador') {
    header("Location: ../login.html");
    exit();
}

require_once '../config/db.php';

try {
    $sql = "SELECT r.id AS reserva_id, r.data_reserva, r.data_inicio, r.data_fim, r.status_reserva, 
                   v.modelo, v.marca, v.ano, v.foto, l.nome AS nome_locatario
            FROM reservas r
            INNER JOIN veiculos v ON r.veiculo_id = v.id
            INNER JOIN usuarios l ON r.locatario_id = l.id
            WHERE v.locador_id = :locador_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':locador_id' => $_SESSION['usuario_id']]);
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar veículos alugados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veículos Alugados - DirigeAí</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #198754;
        }
        .navbar .nav-link, .navbar-brand {
            color: #fff;
        }
        .container {
            margin-top: 40px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: scale(1.03);
        }
        .card img {
            max-height: 200px;
            object-fit: cover;
            width: 100%;
            border-radius: 15px 15px 0 0;
        }
        .card-title {
            color: #198754;
            font-weight: bold;
        }
        .status-ativo {
            color: #198754;
            font-weight: bold;
        }
        .status-inativo {
            color: #dc3545;
            font-weight: bold;
        }
        .btn-back {
            margin-top: 20px;
            background-color: #6c757d;
            border: none;
            color: white;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
        footer {
            margin-top: 40px;
            padding: 15px 0;
            background-color: #343a40;
            color: #fff;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard_locador.php"><i class="fas fa-car-side"></i> DirigeAí</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard_locador.php"><i class="fas fa-home"></i> Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../php/logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="container">
        <div class="text-center mb-4">
            <i class="fas fa-clipboard-list icon text-success"></i>
            <h1 class="fw-bold text-success">Veículos Alugados</h1>
            <p class="text-muted">Visualize os veículos que estão atualmente alugados.</p>
        </div>

        <div class="row">
            <?php if (count($reservas) > 0): ?>
                <?php foreach ($reservas as $reserva): ?>
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="card">
                            <img src="<?= htmlspecialchars($reserva['foto']); ?>" class="card-img-top" alt="Imagem do veículo">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($reserva['modelo']) . ' - ' . htmlspecialchars($reserva['marca']); ?></h5>
                                <p class="card-text">
                                    <strong>Ano:</strong> <?= htmlspecialchars($reserva['ano']); ?><br>
                                    <strong>Locatário:</strong> <?= htmlspecialchars($reserva['nome_locatario']); ?><br>
                                    <strong>Status:</strong> 
                                    <span class="<?= $reserva['status_reserva'] === 'ativo' ? 'status-ativo' : 'status-inativo' ?>">
                                        <?= ucfirst($reserva['status_reserva']); ?>
                                    </span><br>
                                    <strong>Data de Início:</strong> <?= date('d/m/Y', strtotime($reserva['data_inicio'])); ?><br>
                                    <strong>Data de Fim:</strong> <?= date('d/m/Y', strtotime($reserva['data_fim'])); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted"><i class="fas fa-info-circle"></i> Nenhum veículo alugado encontrado.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Botão de Voltar -->
        <div class="text-center">
            <a href="dashboard_locador.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Voltar</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; <?= date('Y'); ?> DirigeAí - Todos os direitos reservados</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>