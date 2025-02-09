<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['usuario_id'])) {
    die("Acesso negado.");
}

$usuario_id = $_SESSION['usuario_id'];
$admin = $_SESSION['admin'] ?? 0; // Verifica se é administrador

$stmt = $pdo->prepare("SELECT * FROM documentos_usuarios WHERE usuario_id = ?");
$stmt->execute([$usuario_id]);
$documentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Documentos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Meus Documentos</h2>
        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Arquivo</th>
                    <th>Status</th>
                    <?php if ($admin) echo "<th>Ações</th>"; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documentos as $doc): ?>
                    <tr>
                        <td><?= ucfirst(str_replace('_', ' ', $doc['tipo_documento'])) ?></td>
                        <td><a href="<?= $doc['caminho_arquivo'] ?>" target="_blank">Visualizar</a></td>
                        <td><?= ucfirst($doc['status']) ?></td>
                        <?php if ($admin): ?>
                            <td>
                                <button onclick="atualizarStatus(<?= $doc['id'] ?>, 'aprovado')">Aprovar</button>
                                <button onclick="atualizarStatus(<?= $doc['id'] ?>, 'rejeitado')">Rejeitar</button>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function atualizarStatus(id, status) {
            fetch('atualiza_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id, status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'sucesso') location.reload();
                else alert(data.mensagem);
            })
            .catch(error => console.error(error));
        }
    </script>
</body>
</html>