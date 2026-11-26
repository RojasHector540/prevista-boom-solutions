<?php
namespace app\models;

class usuarioModel extends mainModel {

    public function autenticarUsuario(string $usuario, string $clave): array {
        $sql = "SELECT u.id, u.nombre, u.apellido, u.usuario, u.contraseña, u.rol_id,
                       r.nombre AS rol, e.nombre AS estatus
                FROM usuarios u
                INNER JOIN roles r ON u.rol_id = r.id
                INNER JOIN estatus_usuario e ON u.estatus_id = e.id
                WHERE u.usuario = :usuario
                LIMIT 1";

        $datos = $this->ejecutarConsulta($sql, [":usuario" => $usuario])->fetch();

        if (!$datos) {
            return [
                "ok"     => false,
                "alerta" => "simple",
                "tipo"   => "error",
                "texto"  => "Usuario o contraseña incorrectos."
            ];
        }

        if ($datos['estatus'] !== 'Activo') {
            return [
                "ok"     => false,
                "alerta" => "simple",
                "tipo"   => "warning",
                "texto"  => "Este usuario se encuentra inactivo."
            ];
        }

        if (!password_verify($clave, $datos['contraseña'])) {
            return [
                "ok"     => false,
                "alerta" => "simple",
                "tipo"   => "error",
                "texto"  => "Usuario o contraseña incorrectos."
            ];
        }

        return [
            "ok"      => true,
            "usuario" => [
                "id"       => $datos['id'],
                "nombre"   => $datos['nombre'],
                "apellido" => $datos['apellido'],
                "usuario"  => $datos['usuario'],
                "rol_id"   => $datos['rol_id'],
                "rol"      => strtolower(trim($datos['rol']))
            ]
        ];
    }

    public function obtenerPreguntasActivas(): array {
        $sql = "SELECT id, pregunta FROM preguntas_seguridad WHERE status = 1 ORDER BY id ASC";
        return $this->ejecutarConsulta($sql)->fetchAll();
    }

    public function obtenerRolesRegistro(): array {
        $sql = "SELECT id, nombre, descripcion
                FROM roles
                WHERE status = 1 AND nombre != 'admin'
                ORDER BY id ASC";
        return $this->ejecutarConsulta($sql)->fetchAll();
    }

    public function obtenerTodosRoles(): array {
        $sql = "SELECT id, nombre, descripcion FROM roles WHERE status = 1 ORDER BY id ASC";
        return $this->ejecutarConsulta($sql)->fetchAll();
    }

    public function registrarUsuario(array $data): array {
        $nombre    = $data['nombre'];
        $apellido  = $data['apellido'];
        $usuario   = $data['usuario'];
        $correo    = $data['correo'];
        $rol_id    = $data['rol_id'];
        $clave     = $data['clave'];
        $clave2    = $data['clave2'];
        $pregunta  = $data['pregunta_id'];
        $respuesta = $data['respuesta'];

        if (
            $nombre === "" || $apellido === "" || $usuario === "" || $correo === "" ||
            $rol_id <= 0 || $clave === "" || $pregunta <= 0 || $respuesta === ""
        ) {
            return [
                "alerta" => "simple",
                "tipo"   => "error",
                "texto"  => "Todos los campos, el rol y la pregunta de seguridad son obligatorios."
            ];
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return [
                "alerta" => "simple",
                "tipo"   => "error",
                "texto"  => "Ingresa un correo electrónico válido."
            ];
        }

        if ($clave !== $clave2) {
            return [
                "alerta" => "simple",
                "tipo"   => "error",
                "texto"  => "Las contraseñas no coinciden."
            ];
        }

        $rolValido = $this->ejecutarConsulta(
            "SELECT id FROM roles WHERE id = :rol_id AND status = 1 AND nombre != 'admin' LIMIT 1",
            [":rol_id" => $rol_id]
        )->fetch();

        if (!$rolValido) {
            return [
                "alerta" => "simple",
                "tipo"   => "error",
                "texto"  => "El rol seleccionado no es válido."
            ];
        }

        $duplicado = $this->ejecutarConsulta(
            "SELECT id FROM usuarios WHERE usuario = :usuario OR correo = :correo LIMIT 1",
            [":usuario" => $usuario, ":correo" => $correo]
        )->fetch();

        if ($duplicado) {
            return [
                "alerta" => "simple",
                "tipo"   => "error",
                "texto"  => "Ese nombre de usuario o correo electrónico ya está registrado."
            ];
        }

        $sql = "INSERT INTO usuarios (nombre, apellido, usuario, correo, contraseña, rol_id, estatus_id, pregunta_id, respuesta_seguridad)
                VALUES (:nombre, :apellido, :usuario, :correo, :clave, :rol_id, :estatus_id, :pregunta_id, :respuesta)";

        $insertado = $this->ejecutarConsulta($sql, [
            ":nombre"      => $nombre,
            ":apellido"    => $apellido,
            ":usuario"     => $usuario,
            ":correo"      => $correo,
            ":clave"       => password_hash($clave, PASSWORD_BCRYPT),
            ":rol_id"      => $rol_id,
            ":estatus_id"  => 1,
            ":pregunta_id" => $pregunta,
            ":respuesta"   => password_hash(strtolower(trim($respuesta)), PASSWORD_BCRYPT)
        ]);

        if ($insertado->rowCount() > 0) {
            return [
                "alerta" => "redireccionar",
                "url"    => APP_URL . "login/"
            ];
        }

        return [
            "alerta" => "simple",
            "tipo"   => "error",
            "texto"  => "No se pudo registrar el usuario."
        ];
    }

    public function buscarPreguntaRecuperacion(string $tipoOlvido, string $usuarioBuscar, string $correoBuscar): array {
        if ($tipoOlvido === 'usuario') {
            if ($correoBuscar === "") {
                return ["ok" => false, "texto" => "Ingresa tu correo electrónico registrado."];
            }

            $sql = "SELECT u.id, p.pregunta
                    FROM usuarios u
                    INNER JOIN preguntas_seguridad p ON u.pregunta_id = p.id
                    INNER JOIN estatus_usuario e ON u.estatus_id = e.id
                    WHERE LOWER(u.correo) = :correo AND e.nombre != 'Eliminado'
                    LIMIT 1";
            $row = $this->ejecutarConsulta($sql, [":correo" => strtolower(trim($correoBuscar))])->fetch();
        } else {
            if ($usuarioBuscar === "") {
                return ["ok" => false, "texto" => "Ingresa tu nombre de usuario."];
            }

            $sql = "SELECT u.id, p.pregunta
                    FROM usuarios u
                    INNER JOIN preguntas_seguridad p ON u.pregunta_id = p.id
                    INNER JOIN estatus_usuario e ON u.estatus_id = e.id
                    WHERE u.usuario = :usuario AND e.nombre != 'Eliminado'
                    LIMIT 1";
            $row = $this->ejecutarConsulta($sql, [":usuario" => $usuarioBuscar])->fetch();
        }

        if ($row) {
            return [
                "ok"       => true,
                "user_id"  => $row['id'],
                "pregunta" => $row['pregunta']
            ];
        }

        return [
            "ok"    => false,
            "texto" => "No se encontró una cuenta con pregunta de seguridad configurada."
        ];
    }

    public function recuperarCuenta(int $userId, string $respuesta, string $tipoOlvido, string $nuevoUsuario, string $nuevaClave): array {
        $respuestaNorm = strtolower(trim($respuesta));

        if ($userId <= 0 || $respuestaNorm === "") {
            return ["alerta" => "simple", "texto" => "Ingresa la respuesta de seguridad."];
        }

        $user = $this->ejecutarConsulta(
            "SELECT id, respuesta_seguridad FROM usuarios WHERE id = :id LIMIT 1",
            [":id" => $userId]
        )->fetch();

        if (!$user) {
            return ["alerta" => "simple", "texto" => "Usuario no encontrado."];
        }

        if (!password_verify($respuestaNorm, (string)$user['respuesta_seguridad'])) {
            return ["alerta" => "simple", "texto" => "La respuesta de seguridad es incorrecta."];
        }

        $campos = [];
        $params = [":id" => $userId];

        if ($tipoOlvido === 'usuario' || $tipoOlvido === 'ambos') {
            if ($nuevoUsuario === "") {
                return ["alerta" => "simple", "texto" => "Debes escribir el nuevo usuario."];
            }
            $campos[] = "usuario = :usuario";
            $params[":usuario"] = $nuevoUsuario;
        }

        if ($tipoOlvido === 'clave' || $tipoOlvido === 'ambos') {
            if ($nuevaClave === "") {
                return ["alerta" => "simple", "texto" => "Debes escribir la nueva contraseña."];
            }
            $campos[] = "contraseña = :clave";
            $params[":clave"] = password_hash($nuevaClave, PASSWORD_BCRYPT);
        }

        if (empty($campos)) {
            return ["alerta" => "simple", "texto" => "Nada que actualizar."];
        }

        $sqlUpdate = "UPDATE usuarios SET " . implode(", ", $campos) . " WHERE id = :id";
        $this->ejecutarConsulta($sqlUpdate, $params);

        return [
            "alerta" => "redireccionar",
            "url"    => APP_URL . "login/"
        ];
    }

    public function listarUsuariosActivosYInactivos(): array {
        $sql = "SELECT u.id, u.nombre, u.apellido, u.usuario, u.correo,
                       u.rol_id, r.nombre AS rol,
                       u.estatus_id, e.nombre AS estatus,
                       u.creado_en
                FROM usuarios u
                INNER JOIN roles r ON u.rol_id = r.id
                INNER JOIN estatus_usuario e ON u.estatus_id = e.id
                WHERE e.nombre != 'Eliminado'
                ORDER BY u.id ASC";

        return $this->ejecutarConsulta($sql)->fetchAll();
    }

    public function alternarEstatusUsuario(int $id, int $sesionId): array {
        if ($id <= 0) {
            return ["ok" => false, "tipo" => "error", "texto" => "Usuario inválido."];
        }

        if ($id === $sesionId) {
            return ["ok" => false, "tipo" => "warning", "texto" => "No puedes cambiar tu propio estatus mientras tienes sesión activa."];
        }

        $actual = $this->ejecutarConsulta(
            "SELECT u.id, e.nombre AS estatus_actual
             FROM usuarios u
             INNER JOIN estatus_usuario e ON u.estatus_id = e.id
             WHERE u.id = :id AND e.nombre != 'Eliminado'
             LIMIT 1",
            [":id" => $id]
        )->fetch();

        if (!$actual) {
            return ["ok" => false, "tipo" => "error", "texto" => "El usuario no existe."];
        }

        $nuevoEstatusNombre = ($actual['estatus_actual'] === 'Activo') ? 'Inactivo' : 'Activo';

        $nuevoId = $this->ejecutarConsulta(
            "SELECT id FROM estatus_usuario WHERE nombre = :nombre LIMIT 1",
            [":nombre" => $nuevoEstatusNombre]
        )->fetchColumn();

        $this->ejecutarConsulta(
            "UPDATE usuarios SET estatus_id = :estatus_id WHERE id = :id",
            [":estatus_id" => (int)$nuevoId, ":id" => $id]
        );

        return [
            "ok"            => true,
            "tipo"          => "success",
            "texto"         => "Estatus actualizado a {$nuevoEstatusNombre}.",
            "nuevo_estatus" => $nuevoEstatusNombre,
            "nuevo_id"      => (int)$nuevoId
        ];
    }

    public function actualizarUsuario(array $data): array {
        $id       = $data['id'];
        $nombre   = $data['nombre'];
        $apellido = $data['apellido'];
        $usuario  = $data['usuario'];
        $correo   = $data['correo'];
        $rolId    = $data['rol_id'];
        $clave    = $data['clave'];

        if ($id <= 0 || $nombre === "" || $apellido === "" || $usuario === "" || $correo === "" || $rolId <= 0) {
            return ["ok" => false, "tipo" => "error", "texto" => "Completa todos los campos obligatorios."];
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return ["ok" => false, "tipo" => "error", "texto" => "El correo electrónico no es válido."];
        }

        $duplicado = $this->ejecutarConsulta(
            "SELECT id FROM usuarios WHERE (usuario = :usuario OR correo = :correo) AND id != :id LIMIT 1",
            [":usuario" => $usuario, ":correo" => $correo, ":id" => $id]
        )->fetch();

        if ($duplicado) {
            return ["ok" => false, "tipo" => "error", "texto" => "Ese usuario o correo ya pertenece a otra cuenta."];
        }

        $campos = [
            "nombre = :nombre",
            "apellido = :apellido",
            "usuario = :usuario",
            "correo = :correo",
            "rol_id = :rol_id"
        ];
        $params = [
            ":nombre"   => $nombre,
            ":apellido" => $apellido,
            ":usuario"  => $usuario,
            ":correo"   => $correo,
            ":rol_id"   => $rolId,
            ":id"       => $id
        ];

        if ($clave !== "") {
            $campos[] = "contraseña = :clave";
            $params[":clave"] = password_hash($clave, PASSWORD_BCRYPT);
        }

        $this->ejecutarConsulta(
            "UPDATE usuarios SET " . implode(", ", $campos) . " WHERE id = :id",
            $params
        );

        $nombreRol = (string)$this->ejecutarConsulta(
            "SELECT nombre FROM roles WHERE id = :id LIMIT 1",
            [":id" => $rolId]
        )->fetchColumn();

        return [
            "ok"    => true,
            "tipo"  => "success",
            "texto" => "Datos del usuario actualizados correctamente.",
            "datos" => [
                "id"       => $id,
                "nombre"   => $nombre,
                "apellido" => $apellido,
                "usuario"  => $usuario,
                "correo"   => $correo,
                "rol_id"   => $rolId,
                "rol"      => $nombreRol
            ]
        ];
    }

    public function eliminarUsuarioLogico(int $id, int $sesionId): array {
        if ($id <= 0) {
            return ["ok" => false, "tipo" => "error", "texto" => "Usuario inválido."];
        }

        if ($id === $sesionId) {
            return ["ok" => false, "tipo" => "warning", "texto" => "No puedes eliminar tu propio usuario en sesión."];
        }

        $estatusEliminadoId = (int)$this->ejecutarConsulta(
            "SELECT id FROM estatus_usuario WHERE nombre = 'Eliminado' LIMIT 1"
        )->fetchColumn();

        if ($estatusEliminadoId <= 0) {
            return ["ok" => false, "tipo" => "error", "texto" => "Falta el estatus 'Eliminado' en la base de datos."];
        }

        $this->ejecutarConsulta(
            "UPDATE usuarios SET estatus_id = :estatus_id WHERE id = :id",
            [":estatus_id" => $estatusEliminadoId, ":id" => $id]
        );

        return [
            "ok"    => true,
            "tipo"  => "success",
            "texto" => "Usuario eliminado lógicamente del sistema."
        ];
    }
}