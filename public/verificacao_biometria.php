<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de Biometria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .circle {
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            border: 2px dashed #198754;
            width: 200px;
            height: 200px;
            margin: 20px auto;
            overflow: hidden;
            position: relative;
        }

        .circle video {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            object-fit: cover;
            width: 100%;
            height: 100%;
        }

        .camera-container {
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #198754;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #146c43;
        }

        .selfie-preview, .document-preview {
            width: 100%;
            margin-top: 10px;
        }

        .preview-container {
            display: none;
            text-align: center;
            margin-top: 10px;
        }

        .status-message {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #198754;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <!-- Passo 1: Captura da selfie -->
        <div id="step1" class="active">
            <h3 class="text-center">Posicione seu rosto no círculo e clique em "Capturar Selfie"</h3>
            <div class="camera-container">
                <div class="circle">
                    <video id="video" autoplay></video>
                </div>
            </div>
            <button id="captureButton" class="btn btn-primary w-100">Capturar Selfie</button>
            <div class="preview-container" id="selfiePreviewContainer">
                <img id="selfiePreview" class="selfie-preview" style="display: none;">
                <input type="hidden" id="selfieInput">
            </div>
            <button id="verifyButton" class="btn btn-primary w-100" style="display: none;">Verificar Selfie</button>
        </div>

        <!-- Passo 2: Upload da CNH -->
        <div id="step2" class="status-message" style="display: none;">
            <h3 class="text-center">Faça o upload da CNH (Imagem ou PDF)</h3>
            <div class="camera-container">
                <input type="file" id="uploadCNH" class="form-control" accept="image/*,application/pdf">
            </div>
            <button id="uploadButton" class="btn btn-primary w-100">Enviar CNH</button>
            <div class="preview-container" id="documentPreviewContainer" style="display: none;">
                <img id="documentPreview" class="document-preview" style="display: none;">
                <input type="hidden" id="documentInput">
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const videoElement = document.getElementById('video');
        const selfiePreview = document.getElementById('selfiePreview');
        const selfieInput = document.getElementById('selfieInput');
        const captureButton = document.getElementById('captureButton');
        const verifyButton = document.getElementById('verifyButton');
        const selfiePreviewContainer = document.getElementById('selfiePreviewContainer');
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');

        const uploadCNH = document.getElementById('uploadCNH');
        const uploadButton = document.getElementById('uploadButton');
        const documentPreview = document.getElementById('documentPreview');
        const documentInput = document.getElementById('documentInput');
        const documentPreviewContainer = document.getElementById('documentPreviewContainer');

        // Acessar a câmera para captura da selfie
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
            .then(stream => {
                videoElement.srcObject = stream;
            })
            .catch(err => {
                alert('Não foi possível acessar a câmera frontal.');
            });

        // Captura da selfie
        captureButton.addEventListener('click', () => {
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.width = videoElement.videoWidth;
            canvas.height = videoElement.videoHeight;
            context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
            const dataUrl = canvas.toDataURL('image/jpeg');
            selfiePreview.src = dataUrl;
            selfiePreview.style.display = 'block';
            selfieInput.value = dataUrl;
            verifyButton.style.display = 'block';
        });

        // Verificar selfie e passar para a próxima etapa
        verifyButton.addEventListener('click', () => {
            step1.style.display = 'none';
            step2.style.display = 'block';
        });

        // Função para exibir a visualização do arquivo de CNH
        function previewDocument(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const fileType = file.type;

                // Se for uma imagem, mostrar a pré-visualização
                if (fileType.startsWith('image')) {
                    documentPreview.src = e.target.result;
                    documentPreview.style.display = 'block';
                }
                // Se for um PDF, exibir uma mensagem
                else if (fileType === 'application/pdf') {
                    documentPreview.src = ''; // Não mostramos a pré-visualização do PDF aqui
                    alert('PDF enviado com sucesso!');
                }
                documentInput.value = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        // Lidar com o evento de upload de CNH
        uploadCNH.addEventListener('change', () => {
            const file = uploadCNH.files[0];
            if (file) {
                previewDocument(file);
                documentPreviewContainer.style.display = 'block';
            }
        });



// Função para enviar os arquivos para o servidor
function uploadFiles() {
    const selfieInput = document.getElementById('selfieInput').value;
    const documentInput = document.getElementById('documentInput').value;
    
    const formData = new FormData();

    // Adicionar a selfie ao FormData
    const selfieFile = dataURLtoFile(selfieInput, 'selfie.jpg');
    formData.append('selfie', selfieFile);

    // Adicionar o arquivo da CNH ao FormData
    const cnhFile = document.getElementById('uploadCNH').files[0];
    formData.append('cnh', cnhFile);

    // Enviar para o servidor
    fetch('../api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao enviar arquivos.');
    });
}

// Função auxiliar para converter base64 para arquivo
function dataURLtoFile(dataurl, filename) {
    const arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1];
    const bstr = atob(arr[1]);
    let n = bstr.length;
    const u8arr = new Uint8Array(n);
    while(n--) u8arr[n] = bstr.charCodeAt(n);
    return new File([u8arr], filename, { type: mime });
}

// Adicionar evento para o botão de upload
uploadButton.addEventListener('click', () => {
    uploadFiles();
});
        
        
        
    </script>
</body>
</html>