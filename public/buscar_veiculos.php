<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'locatario') {
    header("Location: ../login.html");
    exit();
}

require_once '../config/db.php';

try {
    $sql = "SELECT 
            v.id, 
            v.modelo, 
            v.marca, 
            v.ano, 
            v.foto, 
            v.valor_diaria, 
            u.nome AS proprietario,
            (SELECT COUNT(*) FROM reservas r WHERE r.veiculo_id = v.id AND r.status_reserva = 'aceita') AS reservado
        FROM veiculos v
        JOIN usuarios u ON v.locador_id = u.id
        WHERE v.status = 'aprovado'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar veículos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Veículos - AlugaVale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #198754;
        }
        .navbar-brand, .navbar .nav-link {
            color: #fff;
        }
        .navbar .nav-link:hover {
            text-decoration: underline;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: scale(1.02);
        }
        .card img {
            max-height: 180px;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
        }
        .btn-primary {
            background-color: #198754;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #146c43;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
        .btn-secondary:disabled {
            background-color: #aaa;
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
        .icon {
            font-size: 40px;
            color: #198754;
            margin-bottom: 10px;
        }
        .no-vehicles {
            font-size: 1.2rem;
            color: #6c757d;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard_locatario.php"><i class="fas fa-car"></i> AlugaVale - Locatário</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="minhas_reservas.php"><i class="fas fa-bookmark me-2"></i>Minhas Reservas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../php/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo principal -->
    <div class="container my-5">
        <h1 class="text-center text-success"><i class="fas fa-search icon"></i> Buscar Veículos</h1>

        <div class="row">
            <?php if ($veiculos): ?>
                <?php foreach ($veiculos as $veiculo): ?>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <img src="<?= htmlspecialchars($veiculo['foto']); ?>" class="card-img-top" alt="Imagem do veículo">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-car"></i> <?= htmlspecialchars($veiculo['modelo']) . ' - ' . htmlspecialchars($veiculo['marca']); ?>
                                </h5>
                                <p class="card-text">
                                    <strong>Ano:</strong> <?= htmlspecialchars($veiculo['ano']); ?><br>
                                    <strong>Valor da Diária:</strong> R$ <?= number_format($veiculo['valor_diaria'], 2, ',', '.'); ?><br>
                                    <strong>Proprietário:</strong> <?= htmlspecialchars($veiculo['proprietario']); ?>
                                </p>
                                <?php if ($veiculo['reservado'] > 0): ?>
                                    <button class="btn btn-secondary w-100" disabled>
                                        <i class="fas fa-ban"></i> Reservado
                                    </button>
                                <?php else: ?>
                                    <a href="/public/pagamento.php?veiculo_id=<?= $veiculo['id']; ?>" class="btn btn-primary w-100">
                                        <i class="fas fa-check-circle me-2"></i>Reservar
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-vehicles"><i class="fas fa-info-circle"></i> Nenhum veículo disponível no momento.</p>
            <?php endif; ?>
        </div>

        <div class="text-center">
            <a href="dashboard_locatario.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Voltar</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>