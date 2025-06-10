self.onmessage = function (e) {
    console.log("[Worker] Mensaje recibido del hilo principal.");
    try {
        const numbers = e.data;

        // Validar que los datos recibidos sean un array
        if (!Array.isArray(numbers)) {
            throw new Error("Los datos recibidos no son un array válido");
        }

        console.log("[Worker] Datos recibidos y validados.");

        // Simular un proceso de ordenamiento (usando quicksort para arrays grandes)
        const sortedNumbers = quickSort([...numbers]);

        console.log("[Worker] Números ordenados.");

        // Enviar resultado de vuelta al hilo principal
        self.postMessage({
            success: true,
            data: sortedNumbers,
            message: "Números ordenados exitosamente",
        });

        console.log("[Worker] Resultado enviado al hilo principal.");
    } catch (error) {
        // Manejo de errores
        console.error("[Worker] Error:", error.message); // Modificado para mostrar en consola
        self.postMessage({
            success: false,
            error: error.message,
            message: "Error al procesar los números",
        });
    }
};

// Implementación de QuickSort para mejor rendimiento con arrays grandes
function quickSort(arr) {
    try {
        if (arr.length <= 1) {
            return arr;
        }

        const pivot = arr[Math.floor(arr.length / 2)];
        const left = [];
        const right = [];
        const equal = [];

        for (let element of arr) {
            if (element < pivot) {
                left.push(element);
            } else if (element > pivot) {
                right.push(element);
            } else {
                equal.push(element);
            }
        }

        return [...quickSort(left), ...equal, ...quickSort(right)];
    } catch (error) {
        throw new Error(
            `Error en el algoritmo de ordenamiento: ${error.message}`
        );
    }
}

// Manejo de errores globales del worker
self.onerror = function (error) {
    console.error("[Worker] Error global:", error.message);
    self.postMessage({
        success: false,
        error: error.message,
        message: "Error crítico en el Web Worker",
    });
};
