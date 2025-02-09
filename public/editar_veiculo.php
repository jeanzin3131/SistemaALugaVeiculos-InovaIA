<?php
session_start();
require_once '../config/db.php';

// Verificar se o usuário está logado e se é um administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

if (isset($_GET['id'])) {
    $veiculo_id = $_GET['id'];

    // Buscar o veículo a ser editado
    $query = "SELECT id, modelo, marca, ano, status FROM veiculos WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $veiculo_id]);
    $veiculo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$veiculo) {
        echo "Veículo não encontrado.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualizar os dados do veículo
    $modelo = $_POST['modelo'];
    $marca = $_POST['marca'];
    $ano = $_POST['ano'];
    $status = $_POST['status'];

    $query = "UPDATE veiculos SET modelo = :modelo, marca = :marca, ano = :ano, status = :status WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'modelo' => $modelo,
        'marca' => $marca,
        'ano' => $ano,
        'status' => $status,
        'id' => $veiculo_id
    ]);

    header("Location: veiculos_adm.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Veículo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Editar Veículo</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="modelo" class="form-label">Modelo</label>
            <input type="text" class="form-control" id="modelo" name="modelo" value="<?= htmlspecialchars($veiculo['modelo']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="marca" class="form-label">Marca</label>
            <input type="text" class="form-control" id="marca" name="marca" value="<?= htmlspecialchars($veiculo['marca']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="ano" class="form-label">Ano</label>
            <input type="text" class="form-control" id="ano" name="ano" value="<?= htmlspecialchars($veiculo['ano']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="aprovado" <?= $veiculo['status'] === 'aprovado' ? 'selected' : '' ?>>Aprovado</option>
                <option value="pendente" <?= $veiculo['status'] === 'pendente' ? 'selected' : '' ?>>Pendente</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Salvar Alterações</button>
    </form>
</div>

</body>
</html>