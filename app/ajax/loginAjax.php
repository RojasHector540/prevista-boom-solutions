<?php
require_once "../../config/app.php";
require_once "../models/mainModel.php";
require_once "../models/usuarioModel.php";
require_once "../controllers/loginController.php";

use app\controllers\loginController;

header('Content-Type: application/json; charset=utf-8');

$insLogin = new loginController();

if (isset($_POST['usuario']) && isset($_POST['contraseña'])) {
    echo $insLogin->iniciarSesionControlador();
} elseif (isset($_GET['logout'])) {
    $insLogin->cerrarSesionControlador();
} else {
    echo json_encode([
        "alerta" => "simple",
        "texto"  => "Petición no válida."
    ]);
}