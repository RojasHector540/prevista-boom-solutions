<?php
namespace app\controllers;

use app\models\mainModel;
use app\models\usuarioModel;

class usuarioController extends mainModel {
    private usuarioModel $modeloUsuario;

    public function __construct() {
        $this->modeloUsuario = new usuarioModel();
    }

    private function iniciarSesionSiNoExiste(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(['name' => APP_SESSION_NAME]);
        }
    }

    private function esAdmin(): bool {
        $this->iniciarSesionSiNoExiste();
        return isset($_SESSION['id'], $_SESSION['rol']) && strtolower(trim((string)$_SESSION['rol'])) === 'admin';
    }

    public function listarPreguntas(): array {
        return $this->modeloUsuario->obtenerPreguntasActivas();
    }

    public function listarRolesRegistro(): array {
        return $this->modeloUsuario->obtenerRolesRegistro();
    }

    public function listarTodosRoles(): array {
        return $this->modeloUsuario->obtenerTodosRoles();
    }

    public function registrarUsuarioControlador(): string {
        $data = [
            "nombre"      => $this->limpiarCadena($_POST['nombre'] ?? ''),
            "apellido"    => $this->limpiarCadena($_POST['apellido'] ?? ''),
            "usuario"     => $this->limpiarCadena($_POST['usuario'] ?? ''),
            "correo"      => $this->limpiarCadena($_POST['correo'] ?? ''),
            "rol_id"      => intval($_POST['rol_id'] ?? 0),
            "clave"       => $_POST['contraseña'] ?? '',
            "clave2"      => $_POST['contraseña_confirmar'] ?? '',
            "pregunta_id" => intval($_POST['pregunta_id'] ?? 0),
            "respuesta"   => $this->limpiarCadena($_POST['respuesta_seguridad'] ?? '')
        ];

        return json_encode($this->modeloUsuario->registrarUsuario($data));
    }

    public function buscarPreguntaControlador(): string {
        $tipoOlvido    = $this->limpiarCadena($_POST['tipo_olvido'] ?? 'ambos');
        $usuarioBuscar = $this->limpiarCadena($_POST['usuario_buscar'] ?? '');
        $correoBuscar  = $this->limpiarCadena($_POST['correo_buscar'] ?? '');

        return json_encode(
            $this->modeloUsuario->buscarPreguntaRecuperacion($tipoOlvido, $usuarioBuscar, $correoBuscar)
        );
    }

    public function recuperarCuentaControlador(): string {
        $userId       = intval($_POST['user_id'] ?? 0);
        $respuesta    = $this->limpiarCadena($_POST['respuesta_seguridad'] ?? '');
        $tipoOlvido   = $this->limpiarCadena($_POST['tipo_olvido'] ?? 'ambos');
        $nuevoUsuario = $this->limpiarCadena($_POST['nuevo_usuario'] ?? '');
        $nuevaClave   = $_POST['nueva_clave'] ?? '';

        return json_encode(
            $this->modeloUsuario->recuperarCuenta($userId, $respuesta, $tipoOlvido, $nuevoUsuario, $nuevaClave)
        );
    }

    public function listarUsuariosControlador(): array {
        if (!$this->esAdmin()) {
            return [];
        }
        return $this->modeloUsuario->listarUsuariosActivosYInactivos();
    }

    public function cambiarEstatusUsuarioControlador(): string {
        if (!$this->esAdmin()) {
            return json_encode(["ok" => false, "tipo" => "error", "texto" => "Acceso denegado."]);
        }

        $id       = intval($_POST['id'] ?? 0);
        $sesionId = intval($_SESSION['id'] ?? 0);

        return json_encode($this->modeloUsuario->alternarEstatusUsuario($id, $sesionId));
    }

    public function editarUsuarioControlador(): string {
        if (!$this->esAdmin()) {
            return json_encode(["ok" => false, "tipo" => "error", "texto" => "Acceso denegado."]);
        }

        $id = intval($_POST['id'] ?? 0);
        $data = [
            "id"       => $id,
            "nombre"   => $this->limpiarCadena($_POST['nombre'] ?? ''),
            "apellido" => $this->limpiarCadena($_POST['apellido'] ?? ''),
            "usuario"  => $this->limpiarCadena($_POST['usuario'] ?? ''),
            "correo"   => $this->limpiarCadena($_POST['correo'] ?? ''),
            "rol_id"   => intval($_POST['rol_id'] ?? 0),
            "clave"    => $_POST['contraseña'] ?? ''
        ];

        $resultado = $this->modeloUsuario->actualizarUsuario($data);

        if (!empty($resultado['ok']) && !empty($resultado['datos']) && $id === intval($_SESSION['id'] ?? 0)) {
            $_SESSION['nombre']   = $resultado['datos']['nombre'];
            $_SESSION['apellido'] = $resultado['datos']['apellido'];
            $_SESSION['usuario']  = $resultado['datos']['usuario'];
            $_SESSION['rol']      = strtolower(trim((string)$resultado['datos']['rol']));
        }

        return json_encode($resultado);
    }

    public function eliminarUsuarioLogicoControlador(): string {
        if (!$this->esAdmin()) {
            return json_encode(["ok" => false, "tipo" => "error", "texto" => "Acceso denegado."]);
        }

        $id       = intval($_POST['id'] ?? 0);
        $sesionId = intval($_SESSION['id'] ?? 0);

        return json_encode($this->modeloUsuario->eliminarUsuarioLogico($id, $sesionId));
    }
}