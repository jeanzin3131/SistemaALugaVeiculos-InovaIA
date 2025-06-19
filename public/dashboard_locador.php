<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'locador') {
    header("Location: ../login.html");
    exit();
}

// Verificar se o locador tem os documentos verificados
require_once '../config/db.php';
$usuario_id = $_SESSION['usuario_id'];

// Buscar o status dos documentos do locador
$query = "SELECT documentos_verificados FROM usuarios WHERE id = :usuario_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o locador tem os documentos verificados
$documentos_verificados = $result['documentos_verificados'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Locador - DirigeAí</title>
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
        transition: transform 0.2s ease-in-out;
        margin-bottom: 20px; /* Espaçamento entre as cards */
        padding: 15px; /* Reduzido o tamanho interno */
    }
    .card-body {
        padding: 15px; /* Reduzindo o tamanho da body */
    }
    .card-title {
        font-size: 18px; /* Tamanho menor da fonte */
    }
    .card-text {
        font-size: 14px; /* Tamanho menor da descrição */
    }
    .card:hover {
        transform: scale(1.03); /* Reduzido o efeito de hover */
    }
    .btn-primary {
        background-color: #198754; /* Botão verde */
        border: none;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #146c43; /* Verde mais escuro */
    }
    .welcome {
        margin-bottom: 20px;
        text-align: center;
    }
    .welcome h1 {
        font-weight: bold;
    }
    .icon {
        font-size: 40px; /* Ícones menores */
        color: #198754;
        margin-bottom: 10px;
    }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">DirigeAí - Locador</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <?php if ($documentos_verificados == 1): ?>
                            <a class="nav-link" href="cadastrar_veiculo.php"><i class="fas fa-plus-circle me-2"></i>Cadastrar Veículo</a>
                        <?php else: ?>
                            <a class="nav-link disabled" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Envie seus documentos para poder cadastrar um veículo."><i class="fas fa-plus-circle me-2"></i>Cadastrar Veículo</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="meus_veiculos.php"><i class="fas fa-car me-2"></i>Meus Veículos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="solicitacoes_locacao.php"><i class="fas fa-envelope me-2"></i>Solicitações</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="editar_perfil.php"><i class="fas fa-user-edit me-2"></i>Editar Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../php/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="mensagemUpload" class="alert d-none" role="alert"></div>

    <div class="container my-5">
        <div class="welcome">
            <i class="fas fa-user-circle icon"></i>
            <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome']); ?>!</h1>
            <p class="text-muted">Gerencie seus veículos cadastrados e acompanhe as solicitações de locação.</p>
        </div>
        <div class="row">
            <!-- Card: Cadastrar Novo Veículo -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-car-side icon"></i>
                        <h5 class="card-title">Cadastrar Novo Veículo</h5>
                        <p class="card-text">Cadastre um novo veículo para disponibilizá-lo para aluguel.</p>
                        <?php if ($documentos_verificados == 1): ?>
                            <a href="cadastrar_veiculo.php" class="btn btn-primary"><i class="fas fa-plus-circle me-2"></i>Cadastrar</a>
                        <?php else: ?>
                            <a href="#" class="btn btn-secondary" disabled><i class="fas fa-lock me-2"></i>Documentos Pendentes</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Card: Meus Veículos -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-clipboard-list icon"></i>
                        <h5 class="card-title">Meus Veículos</h5>
                        <p class="card-text">Veja e gerencie os veículos já cadastrados no sistema.</p>
                        <a href="meus_veiculos.php" class="btn btn-primary"><i class="fas fa-tools me-2"></i>Gerenciar</a>
                    </div>
                </div>
            </div>

            <!-- Card: Solicitações de Locação -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-envelope icon"></i>
                        <h5 class="card-title">Solicitações de Locação</h5>
                        <p class="card-text">Visualize e gerencie as solicitações de locação pendentes.</p>
                        <a href="solicitacoes_locacao.php" class="btn btn-primary"><i class="fas fa-eye me-2"></i>Ver Solicitações</a>
                    </div>
                </div>
            </div>

            <!-- Card: Veículos Alugados -->
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-bookmark icon"></i>
                        <h5 class="card-title">Veículos Alugados</h5>
                        <p class="card-text">Visualize e gerencie seus veículos alugados.</p>
                        <a href="veiculos_alugados.php" class="btn btn-primary w-100"><i class="fas fa-bookmark me-2"></i>Acessar</a>
                    </div>
                </div>
            </div>

            <!-- Card: Enviar Documentos -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-file-upload icon"></i>
                        <h5 class="card-title">Enviar Documentos</h5>
                        <p class="card-text">Faça o upload dos documentos obrigatórios para alugar veículos.</p>
                        <a href="upload_documentos_locador.php" class="btn btn-primary"><i class="fas fa-upload me-2"></i>Enviar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>