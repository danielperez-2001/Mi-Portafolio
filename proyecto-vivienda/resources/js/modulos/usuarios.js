import $ from "jquery";
import { mostrarModalDemo } from "../utilidades/alertas";

export function iniciarUsuarios() {
    $("#btnNuevoUsuarioDemo").on("click", function () {
        mostrarModalDemo(
            "Gestión de Usuarios Multi-Ciudad",
            "La creación de cuentas de usuario con roles (Superadministrador, Gestor, Auditor) y tokens de sesión para acceso multi-ciudad se habilitará en la Etapa 2 con la base de datos central."
        );
    });
}
