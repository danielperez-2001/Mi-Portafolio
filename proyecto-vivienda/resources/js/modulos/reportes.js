import $ from "jquery";
import { mostrarExito } from "../utilidades/alertas";

export function iniciarReportes() {
    $("#btnImprimirReporte").on("click", function () {
        window.print();
    });

    $("#btnExportarDemo").on("click", function () {
        mostrarExito("Exportación a PDF / Excel preparada para la siguiente etapa con base de datos.");
    });
}
