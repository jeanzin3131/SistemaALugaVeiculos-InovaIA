<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprovação de Veículos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background: #fff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #198754;
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        .table thead {
            background-color: #198754;
            color: white;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(25, 135, 84, 0.1);
        }
        .btn {
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
        }
        .btn-approve {
            background-color: #198754;
            color: white;
        }
        .btn-approve:hover {
            background-color: #146c43;
        }
        .btn-reject {
            background-color: #dc3545;
            color: white;
        }
        .btn-reject:hover {
            background-color: #bb2d3b;
        }
        .img-thumbnail {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        .text-center a {
            color: #198754;
            font-weight: bold;
        }
        .text-center a:hover {
            text-decoration: underline;
        }
        /* Oculta tabela em telas pequenas */
        @media (max-width: 768px) {
            .tabela-veiculos {
                display: none;
            }
        }
        /* Oculta os cards no desktop */
        @media (min-width: 769px) {
            .cards-veiculos {
                display: none;
            }
        }
        /* Estilização dos cards */
        .card-veiculo {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 15px;
        }
        .card-veiculo img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .card-veiculo .btn {
            width: 100%;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2><i class="fas fa-check-circle me-2"></i>Aprovação de Veículos</h2>

        <!-- Exibição em tabela para DESKTOP -->
        <div class="tabela-veiculos">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Modelo</th>
                            <th>Marca</th>
                            <th>Ano</th>
                            <th>Foto</th>
                            <th>Documento</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once '../config/db.php';

                        $query = "SELECT id, modelo, marca, ano, foto, documento FROM veiculos WHERE status = 'pendente'";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute();
                        $veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($veiculos) {
                            foreach ($veiculos as $veiculo) {
                                echo "<tr>";
                                echo "<td>{$veiculo['id']}</td>";
                                echo "<td>{$veiculo['modelo']}</td>";
                                echo "<td>{$veiculo['marca']}</td>";
                                echo "<td>{$veiculo['ano']}</td>";
                                echo "<td><img src='../uploads/{$veiculo['foto']}' alt='Foto' class='img-thumbnail'></td>";
                                echo "<td class='text-center'><a href='../uploads/{$veiculo['documento']}' target='_blank'><i class='fas fa-file-alt me-1'></i>Ver Documento</a></td>";
                                echo "<td class='text-center'>
                                        <button class='btn btn-approve' onclick='aprovarVeiculo({$veiculo['id']})'><i class='fas fa-check me-1'></i>Aprovar</button>
                                        <button class='btn btn-reject' onclick='rejeitarVeiculo({$veiculo['id']})'><i class='fas fa-times me-1'></i>Rejeitar</button>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center text-muted'>Nenhum veículo pendente para aprovação.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Exibição em CARDS para MOBILE -->
        <div class="cards-veiculos">
            <?php
            if ($veiculos) {
                foreach ($veiculos as $veiculo) {
                    echo "<div class='card-veiculo'>";
                    echo "<img src='../uploads/{$veiculo['foto']}' alt='Foto do veículo'>";
                    echo "<h5>{$veiculo['modelo']} - {$veiculo['marca']}</h5>";
                    echo "<p>Ano: <strong>{$veiculo['ano']}</strong></p>";
                    echo "<p><a href='../uploads/{$veiculo['documento']}' target='_blank'><i class='fas fa-file-alt me-1'></i>Ver Documento</a></p>";
                    echo "<button class='btn btn-approve' onclick='aprovarVeiculo({$veiculo['id']})'><i class='fas fa-check me-1'></i>Aprovar</button>";
                    echo "<button class='btn btn-reject' onclick='rejeitarVeiculo({$veiculo['id']})'><i class='fas fa-times me-1'></i>Rejeitar</button>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-center text-muted'>Nenhum veículo pendente para aprovação.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        async function aprovarVeiculo(id) {
            if (confirm('Deseja aprovar este veículo?')) {
                const response = await fetch(`../php/atualizar_veiculo.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}&acao=aprovar`
                });
                const result = await response.text();
                alert(result);
                window.location.reload();
            }
        }

        async function rejeitarVeiculo(id) {
            if (confirm('Deseja rejeitar este veículo?')) {
                const response = await fetch(`../php/atualizar_veiculo.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}&acao=rejeitar`
                });
                const result = await response.text();
                alert(result);
                window.location.reload();
            }
        }
    </script>
</body>
</html>