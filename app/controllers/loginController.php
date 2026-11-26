<?php
namespace app\controllers;

use app\models\mainModel;
use app\models\usuarioModel;

class loginController extends mainModel {
    private usuarioModel $modeloUsuario;

    public function __construct() {
        $this->modeloUsuario = new usuarioModel();
    }

    public function iniciarSesionControlador(): string {
        $usuario = $this->limpiarCadena($_POST['usuario'] ?? '');
        $clave   = $_POST['contraseña'] ?? '';

        if ($usuario === "" || $clave === "") {
            return json_encode([
                "alerta" => "simple",
                "texto"  => "Por favor ingresa tu usuario y contraseña."
            ]);
        }

        $resultado = $this->modeloUsuario->autenticarUsuario($usuario, $clave);

        if (!empty($resultado['ok']) && !empty($resultado['usuario'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start(['name' => APP_SESSION_NAME]);
            }

            foreach ($resultado['usuario'] as $claveSesion => $valorSesion) {
                $_SESSION[$claveSesion] = $valorSesion;
            }

            return json_encode([
                "alerta" => "redireccionar",
                "url"    => APP_URL . "dashboard/"
            ]);
        }

        return json_encode([
            "alerta" => $resultado['alerta'] ?? "simple",
            "texto"  => $resultado['texto'] ?? "No se pudo iniciar sesión."
        ]);
    }

    public function cerrarSesionControlador(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(['name' => APP_SESSION_NAME]);
        }
        session_unset();
        session_destroy();
        header("Location: " . APP_URL . "login/");
        exit();
    }
}