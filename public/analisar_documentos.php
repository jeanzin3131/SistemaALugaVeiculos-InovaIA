<?php
session_start();
require_once '../config/db.php'; // Conexão com o banco de dados

// Verificar se o usuário está logado e se é um administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

// Obter o ID do usuário
$id_usuario = $_GET['id'] ?? null;
if (!$id_usuario) {
    header("Location: usuarios_adm.php"); // Se não for um ID válido, redireciona
    exit();
}

// Buscar informações sobre o usuário e documentos
$query = "SELECT * FROM usuarios WHERE id = :id_usuario";
$stmt = $pdo->prepare($query);
$stmt->execute(['id_usuario' => $id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuário não encontrado.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes de Documentos - <?php echo htmlspecialchars($usuario['nome']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h3>Detalhes dos Documentos de <?php echo htmlspecialchars($usuario['nome']); ?></h3>
    <p><strong>Nome:</strong> <?php echo htmlspecialchars($usuario['nome']); ?></p>
    <p><strong>Status dos Documentos:</strong> <?php echo $usuario['documentos_verificados'] ? 'Aprovados' : 'Não Aprovados'; ?></p>
    <p><strong>Motivo de Reprovação:</strong> <?php echo $usuario['documentos_verificados'] == 0 ? htmlspecialchars($usuario['motivo_reprovacao']) : 'N/A'; ?></p>

    <!-- Formulário para alterar o status ou motivo -->
    <form action="atualizar_documento.php" method="POST">
        <input type="hidden" name="id_usuario" value="<?php echo $usuario['id']; ?>">
        <div class="mb-3">
            <label for="motivo_reprovacao" class="form-label">Motivo de Reprovação</label>
            <textarea class="form-control" id="motivo_reprovacao" name="motivo_reprovacao" rows="3"><?php echo htmlspecialchars($usuario['motivo_reprovacao']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>

</body>
</html>