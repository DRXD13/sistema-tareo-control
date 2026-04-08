<?php
// 🔒 DOBLE SEGURIDAD: Solo el Administrador (Rol 1) puede ver esta pantalla
if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 1) {
    echo '<div class="alert alert-danger mt-4">🛑 Acceso Denegado: No tienes los permisos necesarios para ver la auditoría del sistema.</div>';
    exit();
}

require_once "models/Bitacora.php";
$bitacoraModel = new Bitacora();
$listaBitacora = $bitacoraModel->listarBitacora();
?>

<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary m-0">📓 Bitácora del Sistema (Auditoría)</h3>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark text-white fw-bold">
            Historial de Acciones de Usuarios
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-striped table-hover m-0 align-middle" style="font-size: 0.9rem;">
                <thead class="table-secondary">
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Fecha y Hora</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Acción Realizada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($listaBitacora) > 0): ?>
                        <?php foreach ($listaBitacora as $b): ?>
                            <tr>
                                <td class="text-muted fw-bold">#<?php echo $b['id_bitacora']; ?></td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        🗓️ <?php echo date('d/m/Y', strtotime($b['fecha_hora'])); ?>
                                    </span>
                                    <span class="badge bg-light text-dark border ms-1">
                                        ⏰ <?php echo date('h:i A', strtotime($b['fecha_hora'])); ?>
                                    </span>
                                </td>
                                <td class="fw-bold text-primary">
                                    👤 <?php echo $b['nombres'] . ' ' . $b['apellidos']; ?>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark"><?php echo $b['nombre_rol']; ?></span>
                                </td>
                                <td class="text-start">
                                    <?php echo $b['accion']; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">No hay registros en la bitácora aún.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>