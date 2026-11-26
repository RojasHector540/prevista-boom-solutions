<?php
require_once "../../config/app.php";
require_once "../models/mainModel.php";
require_once "../models/usuarioModel.php";
require_once "../controllers/usuarioController.php";

use app\controllers\usuarioController;

header('Content-Type: application/json; charset=utf-8');

$insUsuario = new usuarioController();
$accion     = $_POST['accion'] ?? '';

switch ($accion) {
    case 'registrar':
        echo $insUsuario->registrarUsuarioControlador();
        break;
    case 'buscar_pregunta':
        echo $insUsuario->buscarPreguntaControlador();
        break;
    case 'recuperar':
        echo $insUsuario->recuperarCuentaControlador();
        break;
    case 'cambiar_estatus':
        echo $insUsuario->cambiarEstatusUsuarioControlador();
        break;
    case 'editar':
        echo $insUsuario->editarUsuarioControlador();
        break;
    case 'eliminar_logico':
        echo $insUsuario->eliminarUsuarioLogicoControlador();
        break;
    default:
        echo json_encode(["alerta" => "simple", "tipo" => "error", "texto" => "Acción no válida."]);
        break;
}