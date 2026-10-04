import $ from "jquery";
import { mostrarError } from "./alertas";

export function peticionAjax(url, data, opciones = {}) {
    var boton = opciones.boton || null;
    var textoOriginal = "";

    if (boton) {
        var $btn = $(boton);
        textoOriginal = $btn.html();
        $btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Procesando...');
    }

    return $.ajax({
        url: url,
        type: "POST",
        data: data,
        dataType: "json"
    }).always(function () {
        if (boton) {
            $(boton).prop("disabled", false).html(textoOriginal);
        }
    }).fail(function (xhr) {
        var mensaje = "No se pudo completar la solicitud al servidor.";
        if (xhr.responseJSON && xhr.responseJSON.message) {
            mensaje = xhr.responseJSON.message;
        } else if (xhr.status === 404) {
            mensaje = "El recurso solicitado no fue encontrado (404).";
        } else if (xhr.status === 405) {
            mensaje = "Método HTTP no permitido (405).";
        } else if (xhr.status >= 500) {
            mensaje = "Ocurrió un error interno en el servidor (500).";
        }

        mostrarError(mensaje);
    });
}
