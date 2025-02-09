<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

require_once '../config/db.php';

// Consultar estatísticas para o painel administrativo
$query = "
    SELECT 
        (SELECT COUNT(*) FROM usuarios) AS total_usuarios,
        (SELECT COUNT(*) FROM veiculos) AS total_veiculos,
        (SELECT COUNT(*) FROM locacoes WHERE status = 'ativa') AS locacoes_ativas,
        (SELECT COUNT(*) FROM documentos_usuarios WHERE status = 'pendente') AS documentos_pendentes
";
$stmt = $pdo->prepare($query);
$stmt->execute();
$stats = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador - AlugaVale</title>
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
        padding: 15px;
    }
    .card:hover {
        transform: scale(1.03);
    }
    .btn-primary {
        background-color: #198754;
        border: none;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #146c43;
    }
    .icon {
        font-size: 40px;
        color: #198754;
        margin-bottom: 10px;
    }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">AlugaVale - Administrador</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="usuarios_adm.php"><i class="fas fa-users me-2"></i>Usuários</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gerenciar_documentos.php"><i class="fas fa-file-alt me-2"></i>Documentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gerenciar_veiculos.php"><i class="fas fa-car me-2"></i>Veículos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gerenciar_locacoes.php"><i class="fas fa-key me-2"></i>Locações</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="financeiro_adm.php"><i class="fas fa-money-bill-wave me-2"></i>Pagamentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../php/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="text-center mb-4">
            <i class="fas fa-user-tie icon"></i>
            <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome']); ?>!</h1>
            <p class="text-muted">Gerencie usuários, veículos, locações e documentos.</p>
        </div>
        <div class="row">
            <!-- Card: Total de Usuários -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-users icon"></i>
                        <h5 class="card-title">Total de Usuários</h5>
                        <p class="card-text"><?php echo $stats['total_usuarios']; ?> usuários cadastrados</p>
                        <a href="usuarios_adm.php" class="btn btn-primary w-100"><i class="fas fa-eye me-2"></i>Gerenciar</a>
                    </div>
                </div>
            </div>

            <!-- Card: Veículos Cadastrados -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-car icon"></i>
                        <h5 class="card-title">Veículos Cadastrados</h5>
                        <p class="card-text"><?php echo $stats['total_veiculos']; ?> veículos registrados</p>
                        <a href="veiculos_adm.php" class="btn btn-primary w-100"><i class="fas fa-car me-2"></i>Ver Veículos</a>
                    </div>
                </div>
            </div>

            <!-- Card: Locações Ativas -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-key icon"></i>
                        <h5 class="card-title">Locações Ativas</h5>
                        <p class="card-text"><?php echo $stats['locacoes_ativas']; ?> locações em andamento</p>
                        <a href="gerenciar_locacoes.php" class="btn btn-primary w-100"><i class="fas fa-key me-2"></i>Ver Locações</a>
                    </div>
                </div>
            </div>

            <!-- Card: Documentos Pendentes -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-file-alt icon"></i>
                        <h5 class="card-title">Documentos Pendentes</h5>
                        <p class="card-text"><?php echo $stats['documentos_pendentes']; ?> documentos aguardando verificação</p>
                        <a href="analisar_documento.php" class="btn btn-primary w-100"><i class="fas fa-file-alt me-2"></i>Verificar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>