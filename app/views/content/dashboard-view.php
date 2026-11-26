<?php
$esAdmin     = (isset($_SESSION['rol']) && strtolower(trim((string)$_SESSION['rol'])) === 'admin');
$vistaActiva = 'dashboard';
?>
<div id="toastContainer" class="fixed top-5 right-5 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none"></div>

<div class="min-h-screen flex bg-gray-100">
    <!-- Menú Lateral -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col justify-between shrink-0 shadow-xl">
        <div>
            <div class="px-5 py-5 border-b border-gray-800 flex items-center gap-3.5">
                <?php
                $logoSizeClass = 'w-14 h-14 shrink-0';
                require __DIR__ . '/../inc/logo.php';
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
            <h2 class="text-lg font-bold text-gray-800">Dashboard</h2>

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
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-l-orange-500">
                <h1 class="text-2xl font-bold text-gray-800 mb-1">
                    ¡Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!
                </h1>
                <p class="text-gray-600 text-sm">
                    Has ingresado correctamente al sistema <strong>Prevista</strong> con el rol de <strong><?php echo htmlspecialchars($_SESSION['rol']); ?></strong>.
                </p>
                <?php if ($esAdmin): ?>
                    <p class="text-xs text-orange-600 font-medium mt-3">
                        Tienes disponible la sección <strong>Usuarios</strong> en el menú lateral izquierdo.
                    </p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>