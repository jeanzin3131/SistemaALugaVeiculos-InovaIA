<?php
session_start();
require_once '../config/db.php';

// Verificar se o usuário está logado e se é um administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veículos - Administração</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #198754;
            color: white;
            position: fixed;
            padding: 20px;
            transition: all 0.3s;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #146c43;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #198754;
            color: white;
            font-weight: bold;
        }
        .btn-custom {
            background-color: #198754;
            color: white;
            border-radius: 8px;
        }
        .btn-custom:hover {
            background-color: #146c43;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
            .table, .table th, .table td {
                display: none;
            }
            .card-deck {
                display: flex;
                flex-wrap: wrap;
                gap: 15px;
            }
            .card {
                flex: 1 1 calc(33.33% - 15px);
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3><i class="fas fa-cogs me-2"></i>Administração</h3>
        <a href="dashboard_admin.php"><i class="fas fa-home me-2"></i>Dashboard</a>
        <a href="veiculos_adm.php"><i class="fas fa-car me-2"></i>Veículos</a>
        <a href="usuarios_adm.php"><i class="fas fa-users me-2"></i>Usuários</a>
        <a href="financeiro_adm.php"><i class="fas fa-chart-line me-2"></i>Financeiro</a>
        <a href="configuracoes.php"><i class="fas fa-cog me-2"></i>Configurações</a>
        <a href="../php/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sair</a>
    </div>

    <div class="main-content">
        <h2><i class="fas fa-car me-2"></i>Veículos</h2>

        <a href="adicionar_veiculo.php" class="btn btn-custom mb-3"><i class="fas fa-plus me-2"></i>Adicionar Novo Veículo</a>
        
        <!-- Campo de pesquisa -->
        <form method="get" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Pesquisar por modelo, marca...">
                <button class="btn btn-custom" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <!-- Visualização de Veículos em Cards -->
        <div class="card-deck">
            <?php
            $query = "SELECT id, modelo, marca, ano, status FROM veiculos WHERE modelo LIKE :search OR marca LIKE :search";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['search' => "%" . $search . "%"]);
            $veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($veiculos as $veiculo) {
                echo "<div class='card'>";
                echo "<div class='card-header'>{$veiculo['modelo']}</div>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>{$veiculo['marca']} - {$veiculo['ano']}</h5>";
                echo "<p class='card-text'>";
                echo "<span class='badge bg-" . ($veiculo['status'] == 'aprovado' ? "success" : "warning") . "'>{$veiculo['status']}</span>";
                echo "</p>";
                echo "<a href='editar_veiculo.php?id={$veiculo['id']}' class='btn btn-sm btn-custom'><i class='fas fa-edit me-1'></i>Editar</a>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

</body>
</html>