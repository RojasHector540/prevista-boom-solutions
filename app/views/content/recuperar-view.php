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

    <!-- Mitad Derecha: Formulario de Recuperar Acceso con tamaño contenido -->
    <div class="relative z-10 w-full md:w-1/2 md:h-full flex items-center justify-center px-6 py-6 md:px-12 bg-gray-900 overflow-y-auto">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl p-6 border border-gray-200">
            <div class="flex flex-col items-center text-center mb-3">
                <?php
                $logoSizeClass = 'w-24 h-24 mb-1';
                require __DIR__ . '/../inc/logo.php';
                ?>
                <h1 class="text-xl font-extrabold text-gray-900 tracking-tight leading-tight">Recuperar Acceso</h1>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-orange-600">PREVISTA - BOOM SOLUTIONS</p>
            </div>

            <form id="formBuscar" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" class="space-y-3 text-sm">
                <input type="hidden" name="accion" value="buscar_pregunta">

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">¿Qué olvidaste?</label>
                    <select id="tipoOlvido" name="tipo_olvido" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                        <option value="usuario">Solo mi usuario</option>
                        <option value="clave">Solo mi contraseña</option>
                        <option value="ambos" selected>Ambos (usuario y contraseña)</option>
                    </select>
                </div>

                <div id="campoUsuarioBuscar">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tu usuario actual</label>
                    <input type="text" name="usuario_buscar" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                </div>

                <div id="campoCorreoBuscar" class="hidden">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tu correo electrónico registrado</label>
                    <input type="email" name="correo_buscar" placeholder="nombre@boomsolutions.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                </div>

                <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-semibold py-2 rounded-lg transition">
                    Continuar
                </button>
            </form>

            <form id="formRecuperar" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" class="hidden mt-4 pt-4 border-t space-y-3 text-sm">
                <input type="hidden" name="accion" value="recuperar">
                <input type="hidden" id="userIdInput" name="user_id">
                <input type="hidden" id="tipoOlvidoFinal" name="tipo_olvido">

                <div class="bg-orange-50 border-l-4 border-orange-500 p-2.5 rounded">
                    <p class="text-[11px] text-gray-500 uppercase font-semibold">Pregunta de seguridad</p>
                    <p id="textoPregunta" class="text-xs font-bold text-gray-800 mt-0.5"></p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tu respuesta</label>
                    <input type="text" name="respuesta_seguridad" required class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                </div>

                <div id="boxNuevoUsuario">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nuevo Usuario</label>
                    <input type="text" name="nuevo_usuario" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                </div>

                <div id="boxNuevaClave">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nueva Contraseña</label>
                    <input type="password" name="nueva_clave" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                </div>

                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 rounded-lg transition">
                    Guardar Cambios
                </button>
            </form>

            <div id="msgRecuperar" class="hidden mt-3 text-xs p-2.5 rounded-lg text-center font-medium"></div>

            <div class="mt-4 pt-3 border-t border-gray-200 text-center">
                <a href="<?php echo APP_URL; ?>login/" class="text-xs font-medium text-gray-600 hover:text-gray-900">Volver al Login</a>
            </div>
        </div>
    </div>

</div>

<script>
const tipoSelect   = document.getElementById('tipoOlvido');
const campoUser    = document.getElementById('campoUsuarioBuscar');
const campoCorreo  = document.getElementById('campoCorreoBuscar');

tipoSelect.addEventListener('change', function() {
    if (this.value === 'usuario') {
        campoUser.classList.add('hidden');
        campoCorreo.classList.remove('hidden');
    } else {
        campoUser.classList.remove('hidden');
        campoCorreo.classList.add('hidden');
    }
});

document.getElementById('formBuscar').addEventListener('submit', function(e) {
    e.preventDefault();
    const msg = document.getElementById('msgRecuperar');
    fetch(this.action, { method: 'POST', body: new FormData(this) })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                msg.classList.add('hidden');
                document.getElementById('userIdInput').value = data.user_id;
                document.getElementById('tipoOlvidoFinal').value = tipoSelect.value;
                document.getElementById('textoPregunta').textContent = data.pregunta;

                const boxU = document.getElementById('boxNuevoUsuario');
                const boxC = document.getElementById('boxNuevaClave');
                boxU.classList.toggle('hidden', tipoSelect.value === 'clave');
                boxC.classList.toggle('hidden', tipoSelect.value === 'usuario');

                document.getElementById('formRecuperar').classList.remove('hidden');
            } else {
                msg.classList.remove('hidden');
                msg.className = 'mt-3 text-xs p-2.5 rounded-lg text-center font-medium bg-red-100 text-red-700';
                msg.textContent = data.texto;
            }
        });
});

document.getElementById('formRecuperar').addEventListener('submit', function(e) {
    e.preventDefault();
    const msg = document.getElementById('msgRecuperar');
    fetch(this.action, { method: 'POST', body: new FormData(this) })
        .then(r => r.json())
        .then(data => {
            if (data.alerta === 'redireccionar') {
                window.location.href = data.url;
            } else {
                msg.classList.remove('hidden');
                msg.className = 'mt-3 text-xs p-2.5 rounded-lg text-center font-medium bg-red-100 text-red-700';
                msg.textContent = data.texto;
            }
        });
});
</script>