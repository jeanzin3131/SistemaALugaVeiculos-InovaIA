<?php
session_start();
require_once '../config/db.php';

// Verificar se o usuário está logado e se é um administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: ../login.html");
    exit();
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
        /* Mesmos estilos do painel */
    </style>
</head>
<body>

<!-- Sidebar (Mesma do painel) -->

<div class="main-content">
    <h2><i class="fas fa-car me-2"></i>Veículos</h2>

    <a href="adicionar_veiculo.php" class="btn btn-custom mb-3"><i class="fas fa-plus me-2"></i>Adicionar Novo Veículo</a>
    
    <!-- Tabela de Veículos -->
    <table class="table table-hover table-bordered">
        <thead class="table-success">
            <tr>
                <th>ID</th>
                <th>Modelo</th>
                <th>Marca</th>
                <th>Ano</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT id, modelo, marca, ano, status FROM veiculos";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($veiculos as $veiculo) {
                echo "<tr>";
                echo "<td>{$veiculo['id']}</td>";
                echo "<td>{$veiculo['modelo']}</td>";
                echo "<td>{$veiculo['marca']}</td>";
                echo "<td>{$veiculo['ano']}</td>";
                echo "<td><span class='badge bg-".($veiculo['status'] == 'aprovado' ? "success" : "warning")."'>{$veiculo['status']}</span></td>";
                echo "<td><a href='editar_veiculo.php?id={$veiculo['id']}' class='btn btn-sm btn-custom'><i class='fas fa-edit me-1'></i>Editar</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>