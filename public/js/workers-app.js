// public/js/workers-app.js
class NumberSorterApp {
    constructor() {
        this.worker = null;
        this.isProcessing = false;
        this.initializeElements();
        this.bindEvents();
        console.log("[App] Aplicación inicializada.");
    }

    initializeElements() {
        this.generateBtn = document.getElementById("generateBtn");
        this.stopBtn = document.getElementById("stopBtn");
        this.statusDiv = document.getElementById("status");
        this.resultsDiv = document.getElementById("results");
        this.sortOrderSelect = document.getElementById("sortOrder");
        this.numToGenerateInput = document.getElementById("numToGenerate");
        this.numToShowInput = document.getElementById("numToShow");
        console.log("[App] Elementos del DOM inicializados.");
    }

    bindEvents() {
        this.generateBtn.addEventListener("click", () => this.startSorting());
        this.stopBtn.addEventListener("click", () => this.stopWorker());
        console.log("[App] Eventos enlazados.");
    }

    generateRandomNumbers(count) {
        try {
            const numbers = [];
            for (let i = 0; i < count; i++) {
                numbers.push(Math.floor(Math.random() * (count + 1)));
            }
            console.log("[App] Números aleatorios generados.");
            return numbers;
        } catch (error) {
            throw new Error(
                `Error generando números aleatorios: ${error.message}`
            );
        }
    }

    startSorting() {
        try {
            if (this.isProcessing) {
                this.showMessage("Ya hay un proceso en ejecución", "error");
                return;
            }

            // Verificar soporte para Web Workers
            if (!window.Worker) {
                throw new Error("Tu navegador no soporta Web Workers");
            }

            this.isProcessing = true;
            this.updateButtons();

            // Obtener los valores de los campos de entrada
            const numToGenerate = parseInt(this.numToGenerateInput.value, 10);
            const numToShow = parseInt(this.numToShowInput.value, 10);

            // Validar los valores
            if (
                isNaN(numToGenerate) ||
                numToGenerate < 1 ||
                numToGenerate > 1000000
            ) {
                throw new Error(
                    "Cantidad de números a generar inválida. Debe estar entre 1 y 1,000,000."
                );
            }
            if (isNaN(numToShow) || numToShow < 1 || numToShow > 1000) {
                throw new Error(
                    "Cantidad de números a mostrar inválida. Debe estar entre 1 y 1,000."
                );
            }

            // Generar números aleatorios
            this.showMessage(
                `Generando ${numToGenerate.toLocaleString()} números aleatorios...`,
                "loading"
            );

            const numbers = this.generateRandomNumbers(numToGenerate);
            this.showMessage(
                `Números generados. Iniciando ordenamiento...`,
                "loading"
            );

            // Crear y configurar el Web Worker
            this.createWorker();

            // Obtener el orden seleccionado
            const sortOrder = this.sortOrderSelect.value;

            // Enviar datos al worker
            console.log("[App] Enviando datos al worker.");
            this.worker.postMessage({
                numbers: numbers,
                order: sortOrder,
                numToShow: numToShow,
            }); // Modificado
        } catch (error) {
            this.handleError(error);
        }
    }

    createWorker() {
        try {
            this.worker = new Worker("/js/sortWorker.js");
            console.log("[App] Worker creado.");

            this.worker.onmessage = (e) => {
                this.handleWorkerMessage(e.data);
            };

            this.worker.onerror = (error) => {
                this.handleError(
                    new Error(`Error en Web Worker: ${error.message}`)
                );
            };
        } catch (error) {
            throw new Error(`Error creando Web Worker: ${error.message}`);
        }
    }

    handleWorkerMessage(data) {
        try {
            if (data.success) {
                console.log("[App] Mensaje del worker recibido con éxito.");
                this.displayResults(data.data, data.numToShow, data.order);
                this.showMessage(data.message, "success");
            } else {
                throw new Error(data.error);
            }
        } catch (error) {
            this.handleError(error);
        } finally {
            this.finishProcessing();
        }
    }

    displayResults(sortedNumbers, numToShow, order = "asc") {
        try {
            // Mostrar solo los primeros N números como se requiere
            const firstN = sortedNumbers.slice(0, numToShow);

            // Determinar el número más pequeño y el más grande según el orden
            let min, max;
            if (order === "asc") {
                min = sortedNumbers[0];
                max = sortedNumbers[sortedNumbers.length - 1];
            } else {
                max = sortedNumbers[0];
                min = sortedNumbers[sortedNumbers.length - 1];
            }

            const resultsHTML = `
            <h4>Resultados del Ordenamiento</h4>
            <p><strong>Total de números ordenados:</strong> ${sortedNumbers.length.toLocaleString()}</p>
            <p><strong>Primeros ${numToShow.toLocaleString()} números ordenados:</strong></p>
            <div class="numbers-display">
                ${firstN
                    .map(
                        (num, index) =>
                            `<span style="margin-right: 10px;">${
                                index + 1
                            }: ${num.toLocaleString()}</span>`
                    )
                    .join("<br>")}
            </div>
            <p><strong>Número más pequeño:</strong> ${min.toLocaleString()}</p>
            <p><strong>Número más grande:</strong> ${max.toLocaleString()}</p>
        `;

            this.resultsDiv.innerHTML = resultsHTML;
            console.log("[App] Resultados mostrados.");
        } catch (error) {
            throw new Error(`Error mostrando resultados: ${error.message}`);
        }
    }

    stopWorker() {
        try {
            if (this.worker) {
                this.worker.terminate();
                this.worker = null;
                this.showMessage("Proceso detenido por el usuario", "error");
                this.finishProcessing();
            }
        } catch (error) {
            this.handleError(error);
        }
    }

    finishProcessing() {
        this.isProcessing = false;
        this.updateButtons();
        if (this.worker) {
            this.worker.terminate();
            this.worker = null;
        }
        console.log("[App] Proceso finalizado.");
    }

    updateButtons() {
        this.generateBtn.disabled = this.isProcessing;
        this.stopBtn.disabled = !this.isProcessing;
    }

    showMessage(message, type = "info") {
        const className = type === "loading" ? "loading" : type;
        this.statusDiv.innerHTML = `<div class="${className}">${message}</div>`;
    }

    handleError(error) {
        console.error("[App] Error:", error);
        this.showMessage(`Error: ${error.message}`, "error");
        this.finishProcessing();
    }
}

// Inicializar la aplicación cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", () => {
    try {
        new NumberSorterApp();
    } catch (error) {
        console.error("[App] Error inicializando la aplicación:", error);
        document.getElementById(
            "status"
        ).innerHTML = `<div class="error">Error inicializando la aplicación: ${error.message}</div>`;
    }
});
