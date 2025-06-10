<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Workers - Ordenamiento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .container {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .button {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }

        .button:hover {
            background: #0056b3;
        }

        .button:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }

        .loading {
            color: #007bff;
            font-weight: bold;
        }

        .error {
            color: #dc3545;
            background: #f8d7da;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }

        .success {
            color: #155724;
            background: #d4edda;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }

        .numbers-display {
            background: white;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <h1>Web Workers - Ordenamiento de Números</h1>

    <div class="container">
        <h3>Generador y Ordenador de Números</h3>
        <button id="generateBtn" class="button">Generar y Ordenar 100,000 números</button>
        <button id="stopBtn" class="button" disabled>Detener Worker</button>

        <div id="status"></div>
        <div id="results"></div>
    </div>

    <script src="{{ asset('js/workers-app.js') }}"></script>
</body>

</html>