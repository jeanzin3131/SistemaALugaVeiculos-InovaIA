<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Documentos</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-file-upload"></i> Envio de Documentos</h2>

        <form action="processa_upload.php" method="POST" enctype="multipart/form-data" class="form-container">
            <label for="tipo_documento"><i class="fas fa-id-card"></i> Tipo de Documento:</label>
            <select name="tipo_documento" required>
                <option value="cnh">CNH</option>
                <option value="comprovante_residencia">Comprovante de Residência</option>
            </select>
            
            <label for="arquivo"><i class="fas fa-file"></i> Escolha o arquivo:</label>
            <input type="file" name="arquivo" accept="image/*,.pdf" required>
            
            <button type="submit"><i class="fas fa-upload"></i> Enviar</button>
        </form>
    </div>
</body>
</html>