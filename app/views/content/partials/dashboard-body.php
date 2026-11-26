<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-l-orange-500">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">
        ¡Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!
    </h1>
    <p class="text-gray-600 text-sm">
        Sesión activa en <strong>PREVISTA - BOOM SOLUTIONS</strong> con el rol <strong><?php echo htmlspecialchars($_SESSION['rol']); ?></strong>.
    </p>
    <?php if ($_SESSION['rol'] === 'admin'): ?>
        <p class="text-xs text-orange-600 font-medium mt-3">
            Tienes acceso al módulo <strong>Usuarios</strong> en el menú lateral izquierdo.
        </p>
    <?php endif; ?>
</div>