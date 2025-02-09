<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];
    
    // Busca o usuário no banco de dados
    $query = "SELECT id, nome, email, tipo_usuario FROM usuarios WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $usuario_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuario) {
        header("Location: usuarios_adm.php");
        exit();
    }
} else {
    header("Location: usuarios_adm.php");
    exit();
}

// Atualiza os dados do usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // Validações simples
    if (empty($nome) || empty($email) || empty($tipo_usuario)) {
        $error = "Todos os campos são obrigatórios.";
    } else {
        // Atualiza os dados no banco
        $updateQuery = "UPDATE usuarios SET nome = :nome, email = :email, tipo_usuario = :tipo_usuario WHERE id = :id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([
            'nome' => $nome,
            'email' => $email,
            'tipo_usuario' => $tipo_usuario,
            'id' => $usuario_id
        ]);

        header("Location: usuarios_adm.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário - Administração</title>
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
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3><i class="fas fa-cogs me-2"></i>Administração</h3>
        <a href="dashboard.php"><i class="fas fa-home me-2"></i>Dashboard</a>
        <a href="veiculos_adm.php"><i class="fas fa-car me-2"></i>Veículos</a>
        <a href="usuarios_adm.php"><i class="fas fa-users me-2"></i>Usuários</a>
        <a href="financeiro_adm.php"><i class="fas fa-chart-line me-2"></i>Financeiro</a>
        <a href="configuracoes.php"><i class="fas fa-cog me-2"></i>Configurações</a>
        <a href="../php/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sair</a>
    </div>

    <div class="main-content">
        <h2><i class="fas fa-edit me-2"></i>Editar Usuário</h2>

        <!-- Exibe erro se houver -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Formulário de Edição -->
        <form action="editar_usuario.php?id=<?php echo $usuario['id']; ?>" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="tipo_usuario" class="form-label">Tipo de Usuário</label>
                <select class="form-select" id="tipo_usuario" name="tipo_usuario" required>
                    <option value="locador" <?php echo $usuario['tipo_usuario'] == 'locador' ? 'selected' : ''; ?>>Locador</option>
                    <option value="locatario" <?php echo $usuario['tipo_usuario'] == 'locatario' ? 'selected' : ''; ?>>Locatário</option>
                    <option value="admin" <?php echo $usuario['tipo_usuario'] == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-custom">Salvar Alterações</button>
        </form>
    </div>

</body>
</html>