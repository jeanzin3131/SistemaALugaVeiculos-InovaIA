<?php
session_start();

// Verifica se o locatário está logado
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'locatario') {
    header("Location: ../login.html");
    exit();
}

require_once '../config/db.php';

// Verifica se o ID do veículo foi passado na URL
if (!isset($_GET['id'])) {
    header("Location: buscar_veiculos.php");
    exit();
}

$veiculo_id = intval($_GET['id']); // Garante que o ID seja um número inteiro

// Busca os detalhes do veículo no banco de dados
try {
    $sql = "SELECT id, modelo, marca, ano, foto, status, valor_diaria FROM veiculos WHERE id = :id AND status = 'aprovado'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $veiculo_id]);
    $veiculo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$veiculo) {
        echo "<script>
                alert('Veículo não encontrado ou indisponível para reserva.');
                window.location.href = 'buscar_veiculos.php';
              </script>";
        exit();
    }
} catch (PDOException $e) {
    die("Erro ao buscar veículo: " . $e->getMessage());
}

// Finaliza a reserva quando o formulário é enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Valida os dados enviados
    if (empty($_POST['data_inicio']) || empty($_POST['data_fim'])) {
        echo "<script>
                alert('Por favor, preencha as datas de início e fim da reserva.');
                window.history.back();
              </script>";
        exit();
    }

    try {
        $locatario_id = $_SESSION['usuario_id'];
        $data_inicio = $_POST['data_inicio'];
        $data_fim = $_POST['data_fim'];
        $data_reserva = date('Y-m-d H:i:s');

        // Calcula os dias da reserva
        $inicio = new DateTime($data_inicio);
        $fim = new DateTime($data_fim);
        $dias = $inicio->diff($fim)->days + 1; // Adiciona 1 para incluir o dia inicial
        $valor_total = $dias * $veiculo['valor_diaria'];

        // Insere a reserva no banco de dados
        $sql = "INSERT INTO reservas (veiculo_id, locatario_id, data_inicio, data_fim, data_reserva, status_reserva, valor_total) 
                VALUES (:veiculo_id, :locatario_id, :data_inicio, :data_fim, :data_reserva, 'pendente', :valor_total)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':veiculo_id' => $veiculo_id,
            ':locatario_id' => $locatario_id,
            ':data_inicio' => $data_inicio,
            ':data_fim' => $data_fim,
            ':data_reserva' => $data_reserva,
            ':valor_total' => $valor_total
        ]);

        echo "<script>
                alert('Reserva realizada com sucesso! Valor total: R$ " . number_format($valor_total, 2, ',', '.') . "');
                window.location.href = 'minhas_reservas.php';
              </script>";
        exit();
    } catch (PDOException $e) {
        die("Erro ao realizar reserva: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Veículo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #4CAF50;
        }
        .navbar .nav-link, .navbar-brand {
            color: #fff;
        }
        .navbar .nav-link:hover {
            text-decoration: underline;
        }
        .card {
            border: 1px solid #4CAF50;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        .card img {
            max-height: 300px;
            object-fit: cover;
            width: 100%;
            border-radius: 15px 15px 0 0;
        }
        .btn-success {
            background-color: #4CAF50;
            border: none;
        }
        .btn-success:hover {
            background-color: #45a049;
        }
        .btn-secondary {
            margin-left: 10px;
        }
        .form-label {
            color: #4CAF50;
        }
        .card-title i {
            color: #4CAF50;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard_locatario.php"><i class="fas fa-car"></i> AlugaVale - Locatário</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="buscar_veiculos.php"><i class="fas fa-search"></i> Buscar Veículos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="minhas_reservas.php"><i class="fas fa-calendar-check"></i> Minhas Reservas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../php/logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h1 class="mb-4 text-center text-success"><i class="fas fa-calendar-plus"></i> Reservar Veículo</h1>
        <div class="card mx-auto" style="max-width: 600px;">
            <img src="<?= htmlspecialchars($veiculo['foto']); ?>" class="card-img-top" alt="Imagem do veículo">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-car"></i> <?= htmlspecialchars($veiculo['modelo']) . ' - ' . htmlspecialchars($veiculo['marca']); ?>
                </h5>
                <p class="card-text">
                    <strong>Ano:</strong> <?= htmlspecialchars($veiculo['ano']); ?><br>
                    <strong>Status:</strong> <?= htmlspecialchars($veiculo['status']); ?><br>
                    <strong>Valor da Diária:</strong> R$ <?= number_format($veiculo['valor_diaria'], 2, ',', '.'); ?>
                </p>
                <form method="POST">
                    <div class="mb-3">
                        <label for="data_inicio" class="form-label">Data de Início</label>
                        <input type="date" id="data_inicio" name="data_inicio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="data_fim" class="form-label">Data de Fim</label>
                        <input type="date" id="data_fim" name="data_fim" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success"><i class="fas fa-check-circle"></i> Confirmar Reserva</button>
                    <a href="buscar_veiculos.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Cancelar</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>