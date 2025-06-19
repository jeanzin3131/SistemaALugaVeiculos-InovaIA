<?php

// Diretórios para armazenar os arquivos
define('SELFIE_UPLOAD_DIR', __DIR__ . '/uploads/selfies/');
define('CNH_UPLOAD_DIR', __DIR__ . '/uploads/cnhs/');

// Garantir que os diretórios existam
if (!is_dir(SELFIE_UPLOAD_DIR)) {
    mkdir(SELFIE_UPLOAD_DIR, 0777, true);
}

if (!is_dir(CNH_UPLOAD_DIR)) {
    mkdir(CNH_UPLOAD_DIR, 0777, true);
}

// Função para verificar se o arquivo é válido
function isValidImage($file) {
    $validMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    return in_array($file['type'], $validMimeTypes);
}

function isValidPDF($file) {
    return $file['type'] === 'application/pdf';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se a selfie foi enviada
    if (isset($_FILES['selfie'])) {
        $selfie = $_FILES['selfie'];
        if ($selfie['error'] === UPLOAD_ERR_OK && isValidImage($selfie)) {
            // Gerar nome único para o arquivo da selfie
            $selfieName = uniqid('selfie_', true) . '.jpg';
            $selfiePath = SELFIE_UPLOAD_DIR . $selfieName;

            // Mover o arquivo para o diretório de uploads
            if (move_uploaded_file($selfie['tmp_name'], $selfiePath)) {
                echo json_encode(['status' => 'success', 'message' => 'Selfie enviada com sucesso!', 'file' => $selfieName]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Falha ao salvar a selfie.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Arquivo de selfie inválido.']);
        }
    }
    
    // Verificar se a CNH foi enviada
    if (isset($_FILES['cnh'])) {
        $cnh = $_FILES['cnh'];
        if ($cnh['error'] === UPLOAD_ERR_OK && (isValidImage($cnh) || isValidPDF($cnh))) {
            // Gerar nome único para o arquivo da CNH
            $cnhName = uniqid('cnh_', true);
            $cnhExtension = pathinfo($cnh['name'], PATHINFO_EXTENSION);
            $cnhPath = CNH_UPLOAD_DIR . $cnhName . '.' . $cnhExtension;

            // Mover o arquivo para o diretório de uploads
            if (move_uploaded_file($cnh['tmp_name'], $cnhPath)) {
                echo json_encode(['status' => 'success', 'message' => 'CNH enviada com sucesso!', 'file' => $cnhName . '.' . $cnhExtension]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Falha ao salvar a CNH.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Arquivo de CNH inválido.']);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método HTTP inválido.']);
}

?>