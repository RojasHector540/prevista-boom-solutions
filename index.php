<?php
require_once "./config/app.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start(['name' => APP_SESSION_NAME]);
}

$url   = isset($_GET['views']) ? explode("/", trim($_GET['views'], "/")) : ["login"];
$vista = $url[0] !== "" ? $url[0] : "login";

$vistasPublicas = ["login", "registro", "recuperar"];

if (!isset($_SESSION['id']) && !in_array($vista, $vistasPublicas)) {
    header("Location: " . APP_URL . "login/");
    exit();
}

if (isset($_SESSION['id']) && in_array($vista, $vistasPublicas)) {
    header("Location: " . APP_URL . "dashboard/");
    exit();
}

if (isset($_SESSION['id']) && $vista === "usuarios" && ($_SESSION['rol'] ?? '') !== "admin") {
    header("Location: " . APP_URL . "dashboard/");
    exit();
}

$archivoVista = "./app/views/content/" . $vista . "-view.php";

spl_autoload_register(function ($clase) {
    $archivo = __DIR__ . "/" . str_replace("\\", "/", $clase) . ".php";
    if (file_exists($archivo)) {
        require_once $archivo;
    }
});
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php
    if (file_exists($archivoVista)) {
        require_once $archivoVista;
    } else {
        require_once "./app/views/content/login-view.php";
    }
    ?>
</body>
</html>