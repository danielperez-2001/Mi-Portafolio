<?php

namespace App\Controllers;

use App\Core\Controller;

class UsuarioController extends Controller
{
    public function usuarios(): void
    {
        // Usuarios de demostración (preparados para la etapa 2 de autenticación y roles)
        $usuarios = [
            [
                'id' => 1,
                'nombre' => 'Administrador General (Demo)',
                'usuario' => 'admin.laestanza',
                'email' => 'admin@laestanza.local',
                'rol' => 'Superadministrador',
                'ciudad' => 'Ciudad de Guatemala',
                'estado' => 'activo',
                'ultimo_acceso' => '2026-10-02 18:30:00'
            ],
            [
                'id' => 2,
                'nombre' => 'Administradora de Operaciones (Demo)',
                'usuario' => 'operaciones.remoto',
                'email' => 'operaciones@laestanza.local',
                'rol' => 'Gestor Administrativo',
                'ciudad' => 'Quetzaltenango (Acceso Remoto)',
                'estado' => 'activo',
                'ultimo_acceso' => '2026-10-02 19:15:00'
            ],
            [
                'id' => 3,
                'nombre' => 'Auditor Externo (Demo)',
                'usuario' => 'auditoria.demo',
                'email' => 'auditoria@laestanza.local',
                'rol' => 'Solo Lectura',
                'ciudad' => 'Antigua Guatemala',
                'estado' => 'activo',
                'ultimo_acceso' => '2026-09-28 11:20:00'
            ]
        ];

        $this->render('usuarios/index', [
            'titulo' => 'Control de Usuarios y Accesos Multi-Ciudad',
            'usuarios' => $usuarios,
            'tipoOperacion' => 'USU'
        ]);
    }
}
