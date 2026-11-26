<?php
require_once __DIR__ . "/../../../models/mainModel.php";
require_once __DIR__ . "/../../../controllers/usuarioController.php";

$insUsr   = new \app\controllers\usuarioController();
$usuarios = $insUsr->listarUsuariosControlador();
$roles    = $insUsr->listarTodosRoles();
?>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h1 class="text-lg font-bold text-gray-800">Usuarios Registrados</h1>
            <p class="text-xs text-gray-500">Administra estatus, datos de cuenta, roles y eliminación lógica.</p>
        </div>
        <span class="text-xs font-semibold bg-gray-100 text-gray-700 px-3 py-1 rounded-full">
            Total: <span id="totalUsuarios"><?php echo count($usuarios); ?></span>
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600 uppercase text-xs border-b border-gray-200">
                    <th class="py-3 px-4">ID</th>
                    <th class="py-3 px-4">Nombre y Apellido</th>
                    <th class="py-3 px-4">Usuario</th>
                    <th class="py-3 px-4">Correo</th>
                    <th class="py-3 px-4">Rol</th>
                    <th class="py-3 px-4">Estatus</th>
                    <th class="py-3 px-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaUsuariosBody" class="divide-y divide-gray-100">
                <?php foreach ($usuarios as $u): ?>
                    <?php $estaActivo = ($u['estatus'] === 'Activo'); ?>
                    <tr id="fila-usuario-<?php echo $u['id']; ?>" class="hover:bg-gray-50 transition">
                        <td class="py-3 px-4 font-mono text-xs text-gray-500"><?php echo $u['id']; ?></td>
                        <td class="py-3 px-4 font-medium text-gray-800 col-nombre">
                            <?php echo htmlspecialchars($u['nombre'] . ' ' . $u['apellido']); ?>
                        </td>
                        <td class="py-3 px-4 text-gray-700 col-usuario"><?php echo htmlspecialchars($u['usuario']); ?></td>
                        <td class="py-3 px-4 text-gray-600 col-correo"><?php echo htmlspecialchars($u['correo']); ?></td>
                        <td class="py-3 px-4 col-rol">
                            <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-800 uppercase">
                                <?php echo htmlspecialchars($u['rol']); ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 col-estatus">
                            <span class="badge-estatus px-2.5 py-1 rounded-full text-xs font-semibold <?php echo $estaActivo ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-800'; ?>">
                                <?php echo htmlspecialchars($u['estatus']); ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right whitespace-nowrap space-x-1.5">
                            <button type="button"
                                    onclick="cambiarEstatus(<?php echo $u['id']; ?>)"
                                    class="btn-estatus px-2.5 py-1.5 rounded-lg text-xs font-semibold transition <?php echo $estaActivo ? 'bg-amber-500 hover:bg-amber-600 text-white' : 'bg-emerald-600 hover:bg-emerald-700 text-white'; ?>">
                                <?php echo $estaActivo ? 'Desactivar' : 'Activar'; ?>
                            </button>

                            <button type="button"
                                    data-id="<?php echo $u['id']; ?>"
                                    data-nombre="<?php echo htmlspecialchars($u['nombre'], ENT_QUOTES); ?>"
                                    data-apellido="<?php echo htmlspecialchars($u['apellido'], ENT_QUOTES); ?>"
                                    data-usuario="<?php echo htmlspecialchars($u['usuario'], ENT_QUOTES); ?>"
                                    data-correo="<?php echo htmlspecialchars($u['correo'], ENT_QUOTES); ?>"
                                    data-rol="<?php echo $u['rol_id']; ?>"
                                    onclick="abrirModalEditar(this)"
                                    class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white transition">
                                Editar
                            </button>

                            <button type="button"
                                    onclick="eliminarUsuarioLogico(<?php echo $u['id']; ?>)"
                                    class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-red-600 hover:bg-red-700 text-white transition">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Editar Usuario -->
<div id="modalEditar" class="fixed inset-0 z-40 hidden bg-gray-900/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-lg font-bold text-gray-800">Editar Usuario</h3>
            <button type="button" onclick="cerrarModalEditar()" class="text-gray-400 hover:text-gray-700 font-bold">✕</button>
        </div>

        <form id="formEditarUsuario" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" class="space-y-3 text-sm">
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="id" id="edit_id">

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" name="nombre" id="edit_nombre" required class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Apellido</label>
                    <input type="text" name="apellido" id="edit_apellido" required class="w-full px-3 py-2 border rounded-lg">
                </div>
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1">Usuario</label>
                <input type="text" name="usuario" id="edit_usuario" required class="w-full px-3 py-2 border rounded-lg">
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1">Correo Electrónico</label>
                <input type="email" name="correo" id="edit_correo" required class="w-full px-3 py-2 border rounded-lg">
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1">Rol</label>
                <select name="rol_id" id="edit_rol_id" required class="w-full px-3 py-2 border rounded-lg">
                    <?php foreach ($roles as $r): ?>
                        <option value="<?php echo $r['id']; ?>"><?php echo htmlspecialchars(strtoupper($r['nombre'])); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1">Nueva Contraseña <span class="text-xs text-gray-400">(opcional)</span></label>
                <input type="password" name="contraseña" id="edit_clave" placeholder="Dejar en blanco para no cambiar" class="w-full px-3 py-2 border rounded-lg">
            </div>

            <div class="pt-3 flex justify-end gap-2 border-t">
                <button type="button" onclick="cerrarModalEditar()" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-orange-600 hover:bg-orange-700 text-white font-semibold">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const ajaxUsuarioUrl = "<?php echo APP_URL; ?>app/ajax/usuarioAjax.php";

function abrirModalEditar(btn) {
    document.getElementById('edit_id').value       = btn.dataset.id;
    document.getElementById('edit_nombre').value   = btn.dataset.nombre;
    document.getElementById('edit_apellido').value = btn.dataset.apellido;
    document.getElementById('edit_usuario').value  = btn.dataset.usuario;
    document.getElementById('edit_correo').value   = btn.dataset.correo;
    document.getElementById('edit_rol_id').value   = btn.dataset.rol;
    document.getElementById('edit_clave').value    = '';
    document.getElementById('modalEditar').classList.remove('hidden');
}

function cerrarModalEditar() {
    document.getElementById('modalEditar').classList.add('hidden');
}

document.getElementById('formEditarUsuario').addEventListener('submit', function(e) {
    e.preventDefault();
    fetch(this.action, { method: 'POST', body: new FormData(this) })
        .then(r => r.json())
        .then(data => {
            mostrarToast(data.texto, data.tipo || (data.ok ? 'success' : 'error'));
            if (data.ok && data.datos) {
                const fila = document.getElementById(`fila-usuario-${data.datos.id}`);
                if (fila) {
                    fila.querySelector('.col-nombre').textContent  = `${data.datos.nombre} ${data.datos.apellido}`;
                    fila.querySelector('.col-usuario').textContent = data.datos.usuario;
                    fila.querySelector('.col-correo').textContent  = data.datos.correo;
                    fila.querySelector('.col-rol span').textContent = data.datos.rol;

                    const btnEdit = fila.querySelector('button[onclick^="abrirModalEditar"]');
                    btnEdit.dataset.nombre   = data.datos.nombre;
                    btnEdit.dataset.apellido = data.datos.apellido;
                    btnEdit.dataset.usuario  = data.datos.usuario;
                    btnEdit.dataset.correo   = data.datos.correo;
                    btnEdit.dataset.rol      = data.datos.rol_id;
                }
                cerrarModalEditar();
            }
        })
        .catch(() => mostrarToast('Error de comunicación con el servidor.', 'error'));
});

function cambiarEstatus(id) {
    const fd = new FormData();
    fd.append('accion', 'cambiar_estatus');
    fd.append('id', id);

    fetch(ajaxUsuarioUrl, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            mostrarToast(data.texto, data.tipo || (data.ok ? 'success' : 'error'));
            if (data.ok) {
                const fila = document.getElementById(`fila-usuario-${id}`);
                if (!fila) return;

                const esActivo = (data.nuevo_estatus === 'Activo');
                const badge    = fila.querySelector('.badge-estatus');
                const btnEst   = fila.querySelector('.btn-estatus');

                badge.textContent = data.nuevo_estatus;
                badge.className   = `badge-estatus px-2.5 py-1 rounded-full text-xs font-semibold ${esActivo ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-800'}`;

                btnEst.textContent = esActivo ? 'Desactivar' : 'Activar';
                btnEst.className   = `btn-estatus px-2.5 py-1.5 rounded-lg text-xs font-semibold transition ${esActivo ? 'bg-amber-500 hover:bg-amber-600 text-white' : 'bg-emerald-600 hover:bg-emerald-700 text-white'}`;
            }
        })
        .catch(() => mostrarToast('Error al cambiar el estatus.', 'error'));
}

function eliminarUsuarioLogico(id) {
    if (!confirm('¿Seguro que deseas eliminar este usuario? Se marcará como eliminado de forma lógica.')) {
        return;
    }

    const fd = new FormData();
    fd.append('accion', 'eliminar_logico');
    fd.append('id', id);

    fetch(ajaxUsuarioUrl, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            mostrarToast(data.texto, data.tipo || (data.ok ? 'success' : 'error'));
            if (data.ok) {
                const fila = document.getElementById(`fila-usuario-${id}`);
                if (fila) fila.remove();

                const totalEl = document.getElementById('totalUsuarios');
                if (totalEl) {
                    totalEl.textContent = Math.max(0, parseInt(totalEl.textContent || '0', 10) - 1);
                }
            }
        })
        .catch(() => mostrarToast('Error al eliminar el usuario.', 'error'));
}
</script>