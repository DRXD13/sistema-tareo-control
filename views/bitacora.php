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
        <div>
            <h3 class="text-primary m-0 fw-bold">📓 Bitácora del Sistema</h3>
            <p class="text-muted small m-0">Auditoría y monitoreo de las acciones realizadas por los usuarios.</p>
        </div>
        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 shadow-sm">
            🔒 Nivel de Acceso: Administrador
        </span>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold text-secondary py-3">
            📋 Historial de Operaciones
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-0 align-middle" style="font-size: 0.95rem;">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th class="ps-4" style="width: 80px;">ID</th>
                            <th>Fecha y Hora</th>
                            <th>Usuario</th>
                            <th>Nivel / Rol</th>
                            <th class="text-start">Acción Realizada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($listaBitacora) > 0): ?>
                            <?php foreach ($listaBitacora as $b): ?>
                                <tr>
                                    <td class="text-muted fw-bold ps-4"><?php echo $b['id_bitacora']; ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?php echo date('d/m/Y', strtotime($b['fecha_hora'])); ?></div>
                                        <div class="text-muted small">⏰ <?php echo date('h:i A', strtotime($b['fecha_hora'])); ?></div>
                                    </td>
                                    <td class="fw-bold text-primary">
                                        👤 <?php echo $b['nombres'] . ' ' . $b['apellidos']; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 rounded-pill">
                                            <?php echo $b['nombre_rol']; ?>
                                        </span>
                                    </td>
                                    <td class="text-start text-dark">
                                        <?php echo htmlspecialchars($b['accion']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">No hay registros en la bitácora aún.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>