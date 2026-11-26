<?php
require_once "./app/models/mainModel.php";
require_once "./app/models/usuarioModel.php";
require_once "./app/controllers/usuarioController.php";
$insUser   = new \app\controllers\usuarioController();
$preguntas = $insUser->listarPreguntas();
$roles     = $insUser->listarRolesRegistro();
?>
<div class="min-h-screen md:h-screen w-full bg-gray-900 flex flex-col md:flex-row relative overflow-y-auto md:overflow-hidden">

    <!-- Mitad Izquierda: Imagen BOOM SOLUTIONS ocupando todo su espacio con corte diagonal -->
    <div class="relative w-full md:w-1/2 h-64 md:h-full overflow-hidden bg-gray-950 md:[clip-path:polygon(0_0,100%_0,84%_100%,0_100%)]">
        <img src="<?php echo APP_URL; ?>app/views/assets/img/boom-conexion.jpg"
             alt="BOOM SOLUTIONS - La Conexión que Une a Venezuela"
             class="w-full h-full object-cover object-center select-none">
    </div>

    <!-- Línea diagonal divisoria en pantallas medianas/grandes -->
    <svg class="hidden md:block pointer-events-none absolute inset-y-0 left-1/2 -translate-x-[8%] h-full w-24 z-20"
         viewBox="0 0 100 1000" preserveAspectRatio="none" aria-hidden="true">
        <line x1="92" y1="0" x2="12" y2="1000" stroke="#ea580c" stroke-width="5" />
    </svg>

    <!-- Mitad Derecha: Formulario de Registro con tamaño contenido -->
    <div class="relative z-10 w-full md:w-1/2 md:h-full flex items-center justify-center px-6 py-4 md:px-10 bg-gray-900 overflow-y-auto">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl p-5 border border-gray-200">
            <div class="flex flex-col items-center text-center mb-2.5">
                <?php
                $logoSizeClass = 'w-24 h-24 mb-1';
                require __DIR__ . '/../inc/logo.php';
                ?>
                <h1 class="text-xl font-extrabold text-gray-900 tracking-tight leading-tight">Registro de Usuario</h1>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-orange-600">PREVISTA - BOOM SOLUTIONS</p>
            </div>

            <form id="formRegistro" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off" class="space-y-2.5 text-sm">
                <input type="hidden" name="accion" value="registrar">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-0.5">Nombre</label>
                        <input type="text" name="nombre" required class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-0.5">Apellido</label>
                        <input type="text" name="apellido" required class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-0.5">Usuario</label>
                        <input type="text" name="usuario" required class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-0.5">Correo Electrónico</label>
                        <input type="email" name="correo" placeholder="nombre@boomsolutions.com" required class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-0.5">Rol / Departamento</label>
                    <select name="rol_id" required class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                        <option value="">Selecciona tu rol...</option>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?php echo $r['id']; ?>">
                                <?php echo htmlspecialchars(strtoupper($r['nombre']) . ($r['descripcion'] ? " - " . $r['descripcion'] : "")); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-0.5">Contraseña</label>
                        <input type="password" name="contraseña" required class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-0.5">Confirmar Contraseña</label>
                        <input type="password" name="contraseña_confirmar" required class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-0.5">Pregunta de Seguridad</label>
                        <select name="pregunta_id" required class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                            <option value="">Selecciona una pregunta...</option>
                            <?php foreach ($preguntas as $p): ?>
                                <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['pregunta']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-0.5">Respuesta de Seguridad</label>
                        <input type="text" name="respuesta_seguridad" required class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    </div>
                </div>

                <div id="msgRegistro" class="hidden text-xs p-2 rounded-lg text-center font-medium"></div>

                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 rounded-lg shadow transition">
                    Crear Cuenta
                </button>
            </form>

            <div class="mt-3 pt-2.5 border-t border-gray-200 text-center">
                <a href="<?php echo APP_URL; ?>login/" class="text-xs font-medium text-gray-600 hover:text-gray-900">Volver al Login</a>
            </div>
        </div>
    </div>

</div>

<script>
document.getElementById('formRegistro').addEventListener('submit', function(e) {
    e.preventDefault();
    const msg = document.getElementById('msgRegistro');
    fetch(this.action, { method: 'POST', body: new FormData(this) })
        .then(r => r.json())
        .then(data => {
            if (data.alerta === 'redireccionar') {
                window.location.href = data.url;
            } else {
                msg.classList.remove('hidden');
                msg.className = 'text-xs p-2 rounded-lg text-center font-medium bg-red-100 text-red-700';
                msg.textContent = data.texto;
            }
        });
});
</script>