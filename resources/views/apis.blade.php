<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Canvas Profesional</title>
  <!-- Bootstrap CSS vía CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Estilos personalizados -->
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
      width: 100%;
      height: auto;
      cursor: crosshair;
    }
  </style>
</head>
<body>
    <section class="conten">
  <div class="container py-5">
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
        <canvas id="miCanvas" width="800" height="450"></canvas>
      </div>
    </div>
  </div>

  <!-- FontAwesome (para iconos) -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</section>
  <!-- Script de Canvas mejorado -->
  <script>
    const canvas = document.getElementById("miCanvas");
    const ctx = canvas.getContext("2d");
    const selector = document.getElementById("tool");

    // Ajustar canvas al ancho del contenedor
    function resizeCanvas() {
      const parentWidth = canvas.parentElement.clientWidth;
      canvas.style.width = parentWidth + 'px';
      canvas.style.height = (parentWidth * canvas.height / canvas.width) + 'px';
    }
    window.addEventListener('load', resizeCanvas);
    window.addEventListener('resize', resizeCanvas);

    // Pintar fondo blanco
    function clearBackground() {
      ctx.fillStyle = '#fff';
      ctx.fillRect(0, 0, canvas.width, canvas.height);
    }
    clearBackground();

    let tool = selector.value;
    let drawing = false;
    let startX = 0, startY = 0;

    selector.addEventListener("change", e => tool = e.target.value);

    // Mapeo de coordenadas del ratón a coordenadas del canvas interno
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

