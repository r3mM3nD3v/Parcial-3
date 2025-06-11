<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>APIs: Canvas y Geolocalización</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <!-- FontAwesome -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

  <style>
    body {
      background-color: #f8f9fa;
    }
    .canvas-card {
      border-radius: 1rem;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      background-color: #fff;
    }
   #miCanvas {
  background-color: #fff;
  cursor: crosshair;
  max-width: 100%;
  height: auto;
  aspect-ratio: 1 / 1;
  display: block;
  margin: 0 auto;
  border: 1px solid #ccc;
}

    #map {
      height: 250px;
      width: 100%;
    }
  </style>
</head>
<body>

<div class="container py-4">
  <!-- Navegación por pestañas -->
  <ul class="nav nav-tabs" id="apisTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="geo-tab" data-bs-toggle="tab" data-bs-target="#geo" type="button" role="tab">Geolocalización</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="canvas-tab" data-bs-toggle="tab" data-bs-target="#canvas" type="button" role="tab">Canvas</button>
    </li>
  </ul>

  <!-- Contenido de pestañas -->
  <div class="tab-content pt-4" id="apisTabContent">
    
    <!-- Geolocalización -->
    <div class="tab-pane fade show active" id="geo" role="tabpanel">
      <div class="card canvas-card p-4 mb-4">
        <h2 class="h4 mb-3">Mi Ubicación actual</h2>
        <p>Latitud: <span id="latitud">Cargando...</span></p>
        <p>Longitud: <span id="longitud">Cargando...</span></p>
        <div id="map" class="border rounded"></div>
      </div>
    </div>

    <!-- Canvas -->
    <div class="tab-pane fade" id="canvas" role="tabpanel">
      <div class="card canvas-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h2 class="h4 m-0">Dibujo con Canvas</h2>
          <div class="d-flex align-items-center">
            <label for="tool" class="form-label me-2 mb-0">Herramienta:</label>
            <select id="tool" class="form-select d-inline-block w-auto me-3">
              <option value="pencil">Dibujo libre</option>
              <option value="line">Línea</option>
              <option value="rect">Rectángulo</option>
              <option value="circle">Círculo</option>
            </select>
            <button id="btnGuardar" class="btn btn-primary">
              <i class="fas fa-download me-1"></i> Guardar JPG
            </button>
          </div>
        </div>
        <div class="border rounded">
          <canvas id="miCanvas" width="600" height="1000"></canvas>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Script de Geolocalización -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;

        document.getElementById('latitud').textContent = lat.toFixed(6);
        document.getElementById('longitud').textContent = lng.toFixed(6);

        const map = L.map('map').setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19,
          attribution: '© OpenStreetMap'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
          .bindPopup('¡Estás aquí!')
          .openPopup();
      }, function (error) {
        alert("Error obteniendo ubicación: " + error.message);
      });
    } else {
      alert("Tu navegador no soporta geolocalización.");
    }
  });
</script>

<!-- Script de Canvas -->
<script>
  const canvas = document.getElementById("miCanvas");
  const ctx = canvas.getContext("2d");
  const selector = document.getElementById("tool");



  function clearBackground() {
    ctx.fillStyle = '#fff';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
  }

  clearBackground();

  let tool = selector.value;
  let drawing = false;
  let startX = 0, startY = 0;

  selector.addEventListener("change", e => tool = e.target.value);

  function getCoords(e) {
    const rect = canvas.getBoundingClientRect();
    const x = (e.clientX - rect.left) * (canvas.width / rect.width);
    const y = (e.clientY - rect.top) * (canvas.height / rect.height);
    return { x, y };
  }

  canvas.addEventListener("mousedown", e => {
    const { x, y } = getCoords(e);
    startX = x; startY = y;
    if (tool === 'pencil') {
      drawing = true;
      ctx.beginPath();
      ctx.moveTo(x, y);
    }
  });

  canvas.addEventListener("mousemove", e => {
    if (!drawing || tool !== 'pencil') return;
    const { x, y } = getCoords(e);
    ctx.lineWidth = 2; ctx.strokeStyle = '#000';
    ctx.lineTo(x, y);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(x, y);
  });

  canvas.addEventListener("mouseup", e => {
    const { x: endX, y: endY } = getCoords(e);
    if (tool === 'pencil') {
      drawing = false;
    } else {
      ctx.beginPath();
      if (tool === 'line') {
        ctx.moveTo(startX, startY);
        ctx.lineTo(endX, endY);
      } else if (tool === 'rect') {
        ctx.rect(startX, startY, endX - startX, endY - startY);
      } else if (tool === 'circle') {
        const r = Math.hypot(endX - startX, endY - startY);
        ctx.arc(startX, startY, r, 0, 2 * Math.PI);
      }
      ctx.stroke();
    }
  });

  document.getElementById('btnGuardar').addEventListener('click', () => {
    const dataURL = canvas.toDataURL('image/jpeg', 0.9);
    const link = document.createElement('a');
    link.href = dataURL;
    link.download = 'mi_dibujo.jpg';
    link.click();
  });
</script>

</body>
</html>
