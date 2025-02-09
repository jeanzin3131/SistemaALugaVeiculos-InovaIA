<?php
require_once '../config/db.php'; // Certifique-se de que o caminho do arquivo está correto
session_start();

// Verifica se o usuário está autenticado e é um locador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'locador') {
    header("Location: ../login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modelo = $_POST['modelo'] ?? null;
    $marca = $_POST['marca'] ?? null;
    $ano = $_POST['ano'] ?? null;
    $valor_diaria = $_POST['valor_diaria'] ?? null; // Campo para o valor da diária
    $locador_id = $_SESSION['usuario_id']; // Obtém o ID do locador autenticado
    $status = 'pendente';

    // Validação do valor da diária
    if (!is_numeric($valor_diaria) || $valor_diaria <= 0) {
        echo "<script>alert('O valor da diária deve ser um número positivo.'); window.history.back();</script>";
        exit();
    }

    // Upload da foto
    $foto = $_FILES['foto']['name'];
    $fotoTemp = $_FILES['foto']['tmp_name'];
    $fotoSize = $_FILES['foto']['size'];
    $fotoError = $_FILES['foto']['error'];
    
    // Upload do documento
    $documento = $_FILES['documento']['name'];
    $documentoTemp = $_FILES['documento']['tmp_name'];
    $documentoSize = $_FILES['documento']['size'];
    $documentoError = $_FILES['documento']['error'];

    // Definindo as permissões e tamanhos máximos dos arquivos
    $fotoMaxSize = 5 * 1024 * 1024; // 5 MB
    $documentoMaxSize = 10 * 1024 * 1024; // 10 MB
    $fotoAllowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $documentoAllowedTypes = ['application/pdf'];

    try {
        // Verificando se os arquivos foram enviados corretamente
        if ($fotoError !== UPLOAD_ERR_OK) {
            throw new Exception('Erro ao enviar a foto do veículo.');
        }

        if ($documentoError !== UPLOAD_ERR_OK) {
            throw new Exception('Erro ao enviar o documento do veículo.');
        }

        // Verificando o tipo e tamanho dos arquivos de foto
        if (!in_array(mime_content_type($fotoTemp), $fotoAllowedTypes)) {
            throw new Exception('Formato de foto inválido. Permita apenas JPEG, PNG ou GIF.');
        }

        if ($fotoSize > $fotoMaxSize) {
            throw new Exception('A foto do veículo ultrapassou o tamanho máximo permitido (5MB).');
        }

        // Verificando o tipo e tamanho dos arquivos de documento
        if (!in_array(mime_content_type($documentoTemp), $documentoAllowedTypes)) {
            throw new Exception('Formato de documento inválido. Permita apenas PDF.');
        }

        if ($documentoSize > $documentoMaxSize) {
            throw new Exception('O documento do veículo ultrapassou o tamanho máximo permitido (10MB).');
        }

        // Movendo os arquivos para o diretório de upload
        $caminhoFoto = '../uploads/fotos/' . uniqid() . '_' . $foto;
        $caminhoDocumento = '../uploads/documentos/' . uniqid() . '_' . $documento;

        if (!move_uploaded_file($fotoTemp, $caminhoFoto)) {
            throw new Exception('Erro ao salvar a foto do veículo.');
        }

        if (!move_uploaded_file($documentoTemp, $caminhoDocumento)) {
            throw new Exception('Erro ao salvar o documento do veículo.');
        }

        // Inserção no banco
        $sql = "INSERT INTO veiculos (modelo, marca, ano, foto, documento, status, valor_diaria, locador_id) 
                VALUES (:modelo, :marca, :ano, :foto, :documento, :status, :valor_diaria, :locador_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':modelo' => $modelo,
            ':marca' => $marca,
            ':ano' => $ano,
            ':foto' => $caminhoFoto,
            ':documento' => $caminhoDocumento,
            ':status' => $status,
            ':valor_diaria' => $valor_diaria,
            ':locador_id' => $locador_id
        ]);

        echo "<script>alert('Veículo cadastrado com sucesso!'); window.location.href = '../public/dashboard_locador.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Erro ao cadastrar veículo: " . $e->getMessage() . "');</script>";
    } catch (Exception $e) {
        echo "<script>alert('" . $e->getMessage() . "');</script>";
    }
} else {
    header("Location: ../public/");
    exit();
}
?>