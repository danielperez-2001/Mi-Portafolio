import $ from "jquery";
import { peticionAjax } from "../utilidades/ajax";
import { mostrarAviso, mostrarModalDemo } from "../utilidades/alertas";

export function iniciarAuth() {
    $("#formLoginDemo").on("submit", function (e) {
        e.preventDefault();

        var $form = $(this);
        var urlLogin = $form.attr("data-url") || $form.attr("action");
        var btnSubmit = $form.find("button[type='submit']")[0];

        var usuario = $.trim($("#txtUsuario").val());
        var clave = $("#txtClave").val();

        if (!usuario) {
            mostrarAviso("Por favor ingrese su nombre de usuario.");
            return;
        }
        if (!clave) {
            mostrarAviso("Por favor ingrese su contraseña.");
            return;
        }

        peticionAjax(urlLogin, $form.serialize(), { boton: btnSubmit }).done(function (respuesta) {
            if (respuesta.status) {
                var d = respuesta.datos;
                var htmlDetalles = `
                    <div class="card bg-light border-0 p-3">
                        <p class="mb-1"><strong>Usuario simulado:</strong> ${escapeHtml(d.usuario)}</p>
                        <p class="mb-0"><strong>Rol simulado:</strong> ${escapeHtml(d.rol)}</p>
                    </div>
                `;

                mostrarModalDemo(
                    "Simulación de Acceso",
                    "Esta pantalla es una preparación visual. En la Etapa 2 se implementará autenticación centralizada con sesiones seguras.",
                    htmlDetalles
                );
            }
        });
    });
}

function escapeHtml(texto) {
    if (!texto) return "";
    return String(texto)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
