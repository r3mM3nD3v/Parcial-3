<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Web Workers - Ordenamiento</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
    body {
      background-color: #f8f9fa;
      font-family: Arial, sans-serif;
    }

    .container {
      max-width: 700px;
      margin-top: 40px;
    }

    .card {
      border-radius: 1rem;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    #status {
      margin-top: 15px;
      font-weight: 600;
    }

    #results {
      margin-top: 20px;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 0.5rem;
      max-height: 200px;
      overflow-y: auto;
      padding: 15px;
      font-family: monospace;
      white-space: pre-wrap;
      word-wrap: break-word;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1 class="mb-4 text-center">Web Workers - Ordenamiento de Números</h1>

    <div class="card p-4 bg-white">
      <h3 class="mb-4">Generador y Ordenador de Números</h3>

      <div class="mb-3">
        <label for="numToGenerate" class="form-label">Cantidad de números a generar (máx. 1,000,000):</label>
        <input type="number" id="numToGenerate" class="form-control" value="100000" min="1" max="1000000" />
      </div>

      <div class="mb-3">
        <label for="numToShow" class="form-label">Cantidad de números a mostrar (máx. 1,000):</label>
        <input type="number" id="numToShow" class="form-control" value="50" min="1" max="1000" />
      </div>

      <div class="mb-4">
        <label for="sortOrder" class="form-label">Selecciona el orden:</label>
        <select id="sortOrder" class="form-select">
          <option value="asc">Ascendente (Menor a Mayor)</option>
          <option value="desc">Descendente (Mayor a Menor)</option>
        </select>
      </div>

      <div class="d-flex gap-3">
        <button id="generateBtn" class="btn btn-primary flex-grow-1">Generar y Ordenar Números</button>
        <button id="stopBtn" class="btn btn-secondary flex-grow-1" disabled>Detener Worker</button>
      </div>

      <div id="status" class="mt-3 text-primary"></div>
      <pre id="results"></pre>
    </div>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Tu script -->
  <script src="{{ asset('js/workers-app.js') }}"></script>
</body>

</html>
