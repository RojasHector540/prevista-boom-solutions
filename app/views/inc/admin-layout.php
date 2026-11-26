<?php
$esAdmin     = (isset($_SESSION['rol']) && strtolower(trim((string)$_SESSION['rol'])) === 'admin');
$vistaActiva = $vista ?? 'dashboard';
?>
<div id="toastContainer" class="fixed top-5 right-5 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none"></div>

<div class="min-h-screen flex bg-gray-100">
    <!-- Menú Lateral -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col justify-between shrink-0 shadow-xl">
        <div>
            <div class="px-5 py-5 border-b border-gray-800 flex items-center gap-3.5">
                <?php
                $logoSizeClass = 'w-14 h-14 shrink-0';
                require __DIR__ . '/logo.php';
                ?>
                <div class="min-w-0">
                    <div class="text-lg font-extrabold tracking-tight text-orange-500 leading-none truncate">PREVISTA</div>
                    <div class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 mt-1 truncate">BOOM SOLUTIONS</div>
                </div>
            </div>

            <nav class="p-4 space-y-1.5 text-sm">
                <a href="<?php echo APP_URL; ?>dashboard/"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition <?php echo ($vistaActiva === 'dashboard') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?>">
                    <span>Panel Principal</span>
                </a>

                <?php if ($esAdmin): ?>
                    <a href="<?php echo APP_URL; ?>usuarios/"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition <?php echo ($vistaActiva === 'usuarios') ? 'bg-orange-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?>">
                        <span>Usuarios</span>
                    </a>
                <?php endif; ?>
            </nav>
        </div>

        <div class="p-4 border-t border-gray-800 text-xs text-gray-400">
            <div class="font-semibold text-gray-200 truncate">
                <?php echo htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']); ?>
            </div>
            <div class="uppercase text-orange-400 mt-0.5">
                <?php echo htmlspecialchars($_SESSION['rol']); ?>
            </div>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center shadow-sm">
            <h2 class="text-lg font-bold text-gray-800 capitalize">
                <?php echo htmlspecialchars($vistaActiva === 'usuarios' ? 'Gestión de Usuarios' : 'Dashboard'); ?>
            </h2>

            <div class="flex items-center gap-4 text-sm">
                <span class="text-gray-600 hidden sm:inline">
                    Usuario: <strong class="text-gray-800"><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong>
                </span>
                <a href="<?php echo APP_URL; ?>app/ajax/loginAjax.php?logout=1"
                   class="bg-gray-800 hover:bg-red-600 text-white px-3.5 py-1.5 rounded-lg font-medium transition">
                    Cerrar sesión
                </a>
            </div>
        </header>

        <main class="flex-1 p-6">
            <?php require_once $contenidoVista; ?>
        </main>
    </div>
</div>

<script>
function mostrarToast(texto, tipo = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const estilos = {
        success: 'bg-emerald-600 text-white border-emerald-700',
        error:   'bg-red-600 text-white border-red-700',
        warning: 'bg-amber-500 text-gray-950 border-amber-600'
    };

    const toast = document.createElement('div');
    toast.className = `pointer-events-auto flex items-center justify-between gap-3 px-4 py-3 rounded-xl shadow-lg border text-sm font-medium transition-all duration-300 ${estilos[tipo] || estilos.success}`;
    toast.innerHTML = `
        <span>${texto}</span>
        <button type="button" onclick="this.parentElement.remove()" class="font-bold opacity-80 hover:opacity-100">✕</button>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-x-4');
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}
</script>