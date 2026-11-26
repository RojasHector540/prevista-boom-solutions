<?php
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: " . APP_URL . "dashboard/");
    exit();
}

$contenidoVista = __DIR__ . "/partials/usuarios-body.php";
require_once __DIR__ . "/../inc/admin-layout.php";