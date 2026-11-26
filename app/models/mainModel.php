<?php
namespace app\models;

use \PDO;

if (file_exists(__DIR__ . "/../../config/server.php")) {
    require_once __DIR__ . "/../../config/server.php";
}

class mainModel {
    protected function conectar() {
        $conexion = new PDO(
            "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS
        );
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conexion;
    }

    protected function ejecutarConsulta(string $sql, array $params = []) {
        $stmt = $this->conectar()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function limpiarCadena($cadena) {
        $cadena = trim((string)$cadena);
        $cadena = stripslashes($cadena);
        $cadena = htmlspecialchars($cadena, ENT_QUOTES, 'UTF-8');
        return $cadena;
    }
}