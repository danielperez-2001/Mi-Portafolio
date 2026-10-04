import $ from "jquery";
import { mostrarExito } from "../utilidades/alertas";

export function iniciarPanel() {
    $("#btnRefrescarPanel").on("click", function () {
        mostrarExito("Panel de control actualizado con datos demostrativos 2026.");
    });
}
