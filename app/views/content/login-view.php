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

    <!-- Mitad Derecha: Formulario de Inicio de Sesión con tamaño contenido -->
    <div class="relative z-10 w-full md:w-1/2 md:h-full flex items-center justify-center px-6 py-6 md:px-12 bg-gray-900">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-6 border border-gray-200">
            <div class="flex flex-col items-center text-center mb-3">
                <?php
                $logoSizeClass = 'w-24 h-25 mb-1';
                require __DIR__ . '/../inc/logo.php';
                ?>
                <h1 class="text-xl font-extrabold text-gray-900 tracking-tight leading-tight">PREVISTA</h1>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-orange-600">BOOM SOLUTIONS</p>
            </div>

            <form id="formLogin" action="<?php echo APP_URL; ?>app/ajax/loginAjax.php" method="POST" autocomplete="off" class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                    <input type="text" name="usuario" placeholder="Ej: admin" required
                           class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input type="password" name="contraseña" placeholder="••••••••" required
                           class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div id="mensajeLogin" class="hidden text-sm p-2.5 rounded-lg text-center font-medium"></div>

                <button type="submit" id="btnLogin"
                        class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2.5 rounded-lg shadow transition">
                    Iniciar Sesión
                </button>
            </form>

            <div class="mt-4 pt-3 border-t border-gray-200 flex justify-between text-xs font-medium">
                <a href="<?php echo APP_URL; ?>registro/" class="text-orange-600 hover:text-orange-700">
                    Registrar nuevo usuario
                </a>
                <a href="<?php echo APP_URL; ?>recuperar/" class="text-gray-600 hover:text-gray-900">
                    ¿Olvidaste usuario o clave?
                </a>
            </div>
        </div>
    </div>

</div>

<script>
document.getElementById('formLogin').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const msg  = document.getElementById('mensajeLogin');
    const btn  = document.getElementById('btnLogin');

    btn.disabled = true;
    btn.textContent = 'Verificando...';

    fetch(form.action, {
        method: 'POST',
        body: new FormData(form)
    })
    .then(res => res.json())
    .then(data => {
        if (data.alerta === 'redireccionar') {
            window.location.href = data.url;
        } else {
            msg.classList.remove('hidden');
            msg.className = 'text-sm p-2.5 rounded-lg text-center font-medium bg-red-100 text-red-700 border border-red-300';
            msg.textContent = data.texto;
            btn.disabled = false;
            btn.textContent = 'Iniciar Sesión';
        }
    })
    .catch(err => {
        console.error(err);
        msg.classList.remove('hidden');
        msg.className = 'text-sm p-2.5 rounded-lg text-center font-medium bg-red-100 text-red-700';
        msg.textContent = 'Error de conexión o ruta inválida (revisa APP_URL).';
        btn.disabled = false;
        btn.textContent = 'Iniciar Sesión';
    });
});
</script>