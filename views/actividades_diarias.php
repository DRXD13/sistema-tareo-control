<?php
require_once "models/Trabajador.php";
require_once "models/ActividadDiaria.php";

$fecha_seleccionada = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

$trabajadorModel = new Trabajador();
$listaTrabajadores = $trabajadorModel->listarTrabajadores();

$actividadModel = new ActividadDiaria();
$listaActividades = $actividadModel->listarPorFecha($fecha_seleccionada);
?>

<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary m-0">🛠️ Registro de Actividades Diarias</h3>
    </div>

    <?php if(isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Aviso!</strong> 
            <?php 
                if($_GET['mensaje'] == 'exito') echo "El reporte de actividad se guardó correctamente.";
                if($_GET['mensaje'] == 'error') echo "Ocurrió un error al guardar la actividad.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm border-primary border-top border-3">
                <div class="card-header bg-white fw-bold text-secondary">
                    📝 Redactar Nuevo Reporte
                </div>
                <div class="card-body">
                    <form action="index.php?accion=guardar_actividad_diaria" method="POST">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Fecha de la Actividad</label>
                            <input type="date" class="form-control form-control-sm" name="fecha" value="<?php echo $fecha_seleccionada; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Seleccionar Trabajador</label>
                            <select class="form-select form-select-sm border-primary" name="id_trabajador" required>
                                <option value="">Seleccione un trabajador...</option>
                                <?php foreach($listaTrabajadores as $t): ?>
                                    <?php if($t['estado'] == 1): ?>
                                        <option value="<?php echo $t['id_trabajador']; ?>">
                                            <?php echo $t['nombres'] . ' ' . $t['apellidos']; ?> 
                                            (<?php echo $t['nombre_area']; ?>)
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Descripción del Trabajo Realizado</label>
                            <textarea class="form-control form-control-sm" name="descripcion_actividad" rows="4" placeholder="Ej: Se realizó la limpieza profunda del parque principal y pintado de bancas..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Observaciones del Supervisor (Opcional)</label>
                            <input type="text" class="form-control form-control-sm" name="observaciones_supervisor" placeholder="Ej: Faltó material, clima lluvioso, etc.">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">💾 Guardar Reporte</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body bg-light py-2">
                    <form action="index.php" method="GET" class="d-flex align-items-center m-0">
                        <input type="hidden" name="vista" value="actividades_diarias">
                        <label class="fw-bold me-3 text-secondary small">Ver reportes del día:</label>
                        <input type="date" name="fecha" class="form-control form-control-sm w-auto me-3 border-primary" value="<?php echo $fecha_seleccionada; ?>" required>
                        <button type="submit" class="btn btn-sm btn-outline-primary fw-bold">Buscar</button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white fw-bold">
                    Reportes Registrados - <?php echo date('d/m/Y', strtotime($fecha_seleccionada)); ?>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-hover m-0 align-middle" style="font-size: 0.9rem;">
                        <thead class="table-secondary">
                            <tr>
                                <th>Trabajador</th>
                                <th>Reporte de Actividad</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($listaActividades) > 0): ?>
                                <?php foreach ($listaActividades as $act): ?>
                                    <tr>
                                        <td class="fw-bold text-nowrap">
                                            👤 <?php echo $act['nombres'] . ' ' . $act['apellidos']; ?>
                                        </td>
                                        <td><?php echo nl2br(htmlspecialchars($act['descripcion_actividad'])); ?></td>
                                        <td class="text-muted small"><?php echo htmlspecialchars($act['observaciones_supervisor']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center py-4 text-muted">No hay actividades reportadas en esta fecha.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>