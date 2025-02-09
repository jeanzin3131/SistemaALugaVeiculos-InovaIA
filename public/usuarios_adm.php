<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

$query = "SELECT id, nome, email, tipo_usuario FROM usuarios WHERE nome LIKE :search OR email LIKE :search";
$stmt = $pdo->prepare($query);
$stmt->execute(['search' => '%' . $search . '%']);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários - Administração</title>
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
            .card {
                margin-bottom: 15px;
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
        <h2><i class="fas fa-users me-2"></i>Usuários</h2>

        <!-- Campo de Pesquisa -->
        <form action="usuarios_adm.php" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Pesquisar por nome ou email" aria-label="Pesquisar">
                <button class="btn btn-custom" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <!-- Exibição de Usuários como Cards (para dispositivos móveis) -->
        <div class="row">
            <?php if (empty($usuarios)): ?>
                <p>Nenhum usuário encontrado.</p>
            <?php else: ?>
                <?php foreach ($usuarios as $usuario): ?>
                    <div class="col-12 col-md-4 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title"><?php echo htmlspecialchars($usuario['nome']); ?></h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
                                <p class="card-text"><strong>Tipo:</strong> <?php echo htmlspecialchars($usuario['tipo_usuario']); ?></p>
                                <a href="editar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-custom"><i class="fas fa-edit me-1"></i>Editar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>