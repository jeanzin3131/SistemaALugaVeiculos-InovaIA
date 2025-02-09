<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'locatario') {
    header("Location: ../login.html");
    exit();
}

require_once '../config/db.php';

// Verificar se os documentos foram verificados (biometria)
$stmt = $pdo->prepare("SELECT documentos_verificados FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['usuario_id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$documentos_verificados = $user['documentos_verificados'] ?? 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Locatário - AlugaVale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #198754;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
            margin-bottom: 20px;
        }
        .card:hover {
            transform: scale(1.02);
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #198754;
        }
        .card-text {
            font-size: 14px;
            color: #6c757d;
        }
        .btn-primary {
            background-color: #198754;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #146c43;
        }
        .welcome {
            text-align: center;
            margin-bottom: 20px;
        }
        .icon {
            font-size: 40px;
            color: #198754;
            margin-bottom: 10px;
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
            <a class="navbar-brand" href="#"><i class="fas fa-car-side"></i> AlugaVale - Locatário</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Verifica se o locatário passou pela verificação de biometria -->
                    <?php if ($documentos_verificados == 1): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="buscar_veiculos.php"><i class="fas fa-search me-2"></i>Procurar Veículos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="minhas_reservas.php"><i class="fas fa-bookmark me-2"></i>Minhas Reservas</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <span class="nav-link text-muted"><i class="fas fa-search me-2"></i>Procurar Veículos (Biometria pendente)</span>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link text-muted"><i class="fas fa-bookmark me-2"></i>Minhas Reservas (Biometria pendente)</span>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../php/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo principal -->
    <div class="container my-5">
        <div class="welcome">
            <i class="fas fa-user-circle icon"></i>
            <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['nome']); ?>!</h1>
            <p class="text-muted">Explore os veículos disponíveis para aluguel e acompanhe suas reservas.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-search icon"></i>
                        <h5 class="card-title">Procurar Veículos</h5>
                        <p class="card-text">Encontre veículos disponíveis para aluguel na sua região.</p>
                        <!-- Bloqueio de acesso ao botão -->
                        <?php if ($documentos_verificados == 1): ?>
                            <a href="buscar_veiculos.php" class="btn btn-primary w-100"><i class="fas fa-search me-2"></i>Buscar</a>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100" disabled><i class="fas fa-search me-2"></i>Buscar (Biometria Pendentes)</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-bookmark icon"></i>
                        <h5 class="card-title">Minhas Reservas</h5>
                        <p class="card-text">Visualize e gerencie suas reservas de veículos.</p>
                        <!-- Bloqueio de acesso ao botão -->
                        <?php if ($documentos_verificados == 1): ?>
                            <a href="minhas_reservas.php" class="btn btn-primary w-100"><i class="fas fa-bookmark me-2"></i>Acessar</a>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100" disabled><i class="fas fa-bookmark me-2"></i>Minhas Reservas (Biometria Pendentes)</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-user-check icon"></i>
                        <h5 class="card-title">Verificação de Biometria</h5>
                        <p class="card-text">Realize a verificação de biometria para completar seu cadastro.</p>
                        <!-- Link para a página de verificação biométrica -->
                        <a href="verificacao_biometria.php" class="btn btn-primary"><i class="fas fa-check-circle me-2"></i>Verificar Biometria</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; <?= date('Y') ?> AlugaVale - Todos os direitos reservados.</p>
    </footer>

    <!-- Bootstrap e Font Awesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>