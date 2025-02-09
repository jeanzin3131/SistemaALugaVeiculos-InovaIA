<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.html");
    exit();
}

require_once '../config/db.php';

// Recupera o ID do locador
$locador_id = $_SESSION['usuario_id'];

try {
    // Consulta para buscar os veículos do locador
    $query = "SELECT id, modelo, marca, ano, foto, status, valor_diaria FROM veiculos WHERE locador_id = :locador_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['locador_id' => $locador_id]);
    $veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Erro ao buscar veículos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Veículos - AlugaVale</title>
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
        .status-active {
            color: #198754;
            font-weight: bold;
        }
        .status-inactive {
            color: #dc3545;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #198754;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #146c43;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #bb2d3b;
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
            <a class="navbar-brand" href="dashboard_locador.php"><i class="fas fa-car"></i> AlugaVale - Locador</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="cadastrar_veiculo.php"><i class="fas fa-plus-circle me-2"></i> Cadastrar Veículo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../php/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="container my-5">
        <div class="text-center mb-4">
            <i class="fas fa-car icon text-success"></i>
            <h1 class="fw-bold text-success">Meus Veículos</h1>
            <p class="text-muted">Gerencie seus veículos cadastrados, altere o valor da diária ou remova-os.</p>
        </div>

        <div class="row">
            <?php if (!empty($veiculos)): ?>
                <?php foreach ($veiculos as $veiculo): ?>
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="card">
                            <img src="<?= htmlspecialchars($veiculo['foto']) ?>" class="card-img-top" alt="Foto do veículo">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($veiculo['modelo']) ?> - <?= htmlspecialchars($veiculo['marca']) ?></h5>
                                <p class="card-text">
                                    <strong>Ano:</strong> <?= htmlspecialchars($veiculo['ano']) ?><br>
                                    <strong>Valor da Diária:</strong> R$ <?= number_format($veiculo['valor_diaria'], 2, ',', '.') ?><br>
                                    <strong>Status:</strong>
                                    <span class="<?= $veiculo['status'] === 'ativo' ? 'status-active' : 'status-inactive' ?>">
                                        <?= ucfirst($veiculo['status']) ?>
                                    </span>
                                </p>

                                <!-- Alterar Valor da Diária -->
                                <form action="../php/alterar_valor.php" method="POST" class="mb-3">
                                    <input type="hidden" name="veiculo_id" value="<?= $veiculo['id'] ?>">
                                    <div class="d-flex align-items-center">
                                        <input type="number" step="0.01" name="valor_diaria" class="form-control me-2" 
                                               value="<?= htmlspecialchars($veiculo['valor_diaria']) ?>" placeholder="R$">
                                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i></button>
                                    </div>
                                </form>

                                <!-- Atualizar Foto -->
                                <form action="../php/atualizar_foto.php" method="POST" enctype="multipart/form-data" class="mb-3">
                                    <input type="hidden" name="veiculo_id" value="<?= $veiculo['id'] ?>">
                                    <div class="d-flex align-items-center">
                                        <input type="file" name="nova_foto" class="form-control me-2" accept="image/*" required>
                                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-upload"></i></button>
                                    </div>
                                </form>

                                <!-- Apagar Veículo -->
                                <form action="../php/apagar_veiculo.php" method="POST">
                                    <input type="hidden" name="veiculo_id" value="<?= $veiculo['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('Tem certeza que deseja apagar este veículo?')">
                                        <i class="fas fa-trash"></i> Apagar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-muted"><i class="fas fa-info-circle"></i> Nenhum veículo cadastrado.</p>
            <?php endif; ?>
        </div>

        <!-- Botão de Voltar -->
        <div class="text-center">
            <a href="dashboard_locador.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Voltar</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; <?= date('Y') ?> AlugaVale - Todos os direitos reservados</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>