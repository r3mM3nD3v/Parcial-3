// public/js/workers-app.js
class NumberSorterApp {
    constructor() {
        this.worker = null;
        this.isProcessing = false;
        this.initializeElements();
        this.bindEvents();
    }

    initializeElements() {
        this.generateBtn = document.getElementById("generateBtn");
        this.stopBtn = document.getElementById("stopBtn");
        this.statusDiv = document.getElementById("status");
        this.resultsDiv = document.getElementById("results");
    }

    bindEvents() {
        this.generateBtn.addEventListener("click", () => this.startSorting());
        this.stopBtn.addEventListener("click", () => this.stopWorker());
    }

    generateRandomNumbers(count) {
        try {
            const numbers = [];
            for (let i = 0; i < count; i++) {
                numbers.push(Math.floor(Math.random() * 1000000));
            }
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

            // Generar números aleatorios
            this.showMessage(
                "Generando 100,000 números aleatorios...",
                "loading"
            );

            const numbers = this.generateRandomNumbers(100000);
            this.showMessage(
                `Números generados. Iniciando ordenamiento...`,
                "loading"
            );

            // Crear y configurar el Web Worker
            this.createWorker();

            // Enviar datos al worker
            this.worker.postMessage(numbers);
        } catch (error) {
            this.handleError(error);
        }
    }

    createWorker() {
        try {
            this.worker = new Worker("/js/sortWorker.js");

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
                this.displayResults(data.data);
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

    displayResults(sortedNumbers) {
        try {
            // Mostrar solo los primeros 50 números como se requiere
            const first50 = sortedNumbers.slice(0, 50);

            const resultsHTML = `
                <h4>Resultados del Ordenamiento</h4>
                <p><strong>Total de números ordenados:</strong> ${sortedNumbers.length.toLocaleString()}</p>
                <p><strong>Primeros 50 números ordenados:</strong></p>
                <div class="numbers-display">
                    ${first50
                        .map(
                            (num, index) =>
                                `<span style="margin-right: 10px;">${
                                    index + 1
                                }: ${num.toLocaleString()}</span>`
                        )
                        .join("<br>")}
                </div>
                <p><strong>Número más pequeño:</strong> ${sortedNumbers[0].toLocaleString()}</p>
                <p><strong>Número más grande:</strong> ${sortedNumbers[
                    sortedNumbers.length - 1
                ].toLocaleString()}</p>
            `;

            this.resultsDiv.innerHTML = resultsHTML;
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
        console.error("Error:", error);
        this.showMessage(`Error: ${error.message}`, "error");
        this.finishProcessing();
    }
}

// Inicializar la aplicación cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", () => {
    try {
        new NumberSorterApp();
    } catch (error) {
        console.error("Error inicializando la aplicación:", error);
        document.getElementById(
            "status"
        ).innerHTML = `<div class="error">Error inicializando la aplicación: ${error.message}</div>`;
    }
});
