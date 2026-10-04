import Swal from "sweetalert2";
import toastr from "toastr";

// Configuración general de toastr en español
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-top-right",
    timeOut: 4500,
    extendedTimeOut: 1500
};

export function mostrarExito(mensaje, titulo = "Operación Exitosa") {
    toastr.success(mensaje, titulo);
}

export function mostrarAviso(mensaje, titulo = "Atención") {
    toastr.warning(mensaje, titulo);
}

export function mostrarError(mensaje, titulo = "Error") {
    toastr.error(mensaje, titulo);
}

export function mostrarModalDemo(titulo, mensaje, detallesHtml = "") {
    Swal.fire({
        icon: "info",
        title: titulo,
        html: `
            <div class="text-start">
                <p class="mb-2">${mensaje}</p>
                <div class="alert alert-warning py-2 px-3 small border mb-3">
                    <strong>Modo Demostración:</strong> Ninguna información fue guardada en la base de datos (Etapa 1).
                </div>
                ${detallesHtml}
            </div>
        `,
        confirmButtonText: "Entendido",
        confirmButtonColor: "#3b82f6"
    });
}
