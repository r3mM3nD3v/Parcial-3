<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>API de Video</title>

    <!-- BOOTSTRAP 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Sección API de Video -->
<div class="container py-4">
    <h2 class="text-center mb-5">API de Video</h2>

    <div class="d-flex flex-column align-items-center">
        <!-- Vista previa de cámara -->
        <div class="border border-secondary rounded shadow mb-4 bg-dark" style="max-width: 600px; width: 100%;">
            <video id="video" class="w-100 rounded" autoplay playsinline style="max-height: 400px;"></video>
        </div>

        <!-- Botones -->
        <div class="mb-3 text-center">
            <button id="snap" class="btn btn-primary me-2">📸 Tomar Foto</button>
            <a id="downloadLink" class="btn btn-success" style="display: none;" download="captura.jpg">⬇ Descargar Foto</a>
        </div>

        <!-- Imagen capturada -->
        <div class="mt-3">
            <img id="photo" class="img-thumbnail" alt="Tu captura aparecerá aquí" style="max-width: 600px;">
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const video = document.getElementById('video');
    const canvas = document.createElement('canvas');
    const photo = document.getElementById('photo');
    const snap = document.getElementById('snap');
    const downloadLink = document.getElementById('downloadLink');

    // Solicitar acceso a la cámara
    async function iniciarCamara() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
        } catch (err) {
            console.error("Permiso denegado o error al acceder a la cámara: ", err);
            alert("No se pudo acceder a la cámara. Asegúrate de conceder permisos.");
        }
    }

    iniciarCamara();

    // Tomar la foto
    snap.addEventListener("click", () => {
        try {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = canvas.toDataURL("image/jpeg");
            photo.src = imageData;
            downloadLink.href = imageData;
            downloadLink.style.display = "inline-block";
        } catch (e) {
            console.error("Error al capturar la imagen: ", e);
        }
    });
});
</script>

</body>
</html>