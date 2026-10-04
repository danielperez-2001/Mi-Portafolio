import $ from "jquery";
import { mostrarModalDemo } from "../utilidades/alertas";

export function iniciarConfiguracion() {
    $("#formConfigDemo").on("submit", function (e) {
        e.preventDefault();
        mostrarModalDemo(
            "Configuración del Sistema",
            "Los parámetros generales (monedas de operación GTQ/USD, días de gracia, preaviso de vencimiento) se persistirán en la base de datos central en la Etapa 2."
        );
    });
}
