<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Web Workers - Ordenamiento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        button { margin-right: 10px; padding: 10px 15px; }
        #estado { margin-top: 15px; font-weight: bold; color: #007bff; }
        #resultados { margin-top: 20px; white-space: pre-wrap; }
    </style>
</head>
<body>
    <h1>Ordenamiento con Web Workers</h1>

    <button id="iniciarBtn">Generar y Ordenar</button>
    <button id="detenerBtn" disabled>Detener Worker</button>

    <p id="estado">Esperando acción...</p>

    <div id="resultados"></div>

    <script src="{{ asset('js/workers-app.js') }}"></script>
</body>
</html>
