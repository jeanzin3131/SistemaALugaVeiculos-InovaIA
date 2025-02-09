<?php
session_start();
require_once '../config/db.php';

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
    <title>Financeiro - Administração</title>
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
    <h2><i class="fas fa-chart-line me-2"></i>Financeiro</h2>
    
    <table class="table table-hover table-bordered">
        <thead class="table-success">
            <tr>
                <th>ID</th>
                <th>Valor</th>
                <th>Status</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT id, valor, status, data_pagamento FROM pagamentos";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $pagamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($pagamentos as $pagamento) {
                echo "<tr>";
                echo "<td>{$pagamento['id']}</td>";
                echo "<td>R$ " . number_format($pagamento['valor'], 2, ',', '.') . "</td>";
                echo "<td><span class='badge bg-".($pagamento['status'] == 'pago' ? "success" : "warning")."'>{$pagamento['status']}</span></td>";
                echo "<td>{$pagamento['data_pagamento']}</td>";
                echo "<td><a href='detalhes_pagamento.php?id={$pagamento['id']}' class='btn btn-sm btn-custom'><i class='fas fa-info-circle me-1'></i>Detalhes</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>