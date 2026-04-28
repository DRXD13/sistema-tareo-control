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
        <div>
            <h3 class="text-primary m-0 fw-bold">🛠️ Registro de Actividades Diarias</h3>
            <p class="text-muted small m-0">Reporte detallado de las labores realizadas por el personal.</p>
        </div>
        <button type="button" class="btn btn-primary fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalActividadDiaria">
            ➕ Redactar Nuevo Reporte
        </button>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-white d-flex align-items-center justify-content-between p-3">
            <div class="text-secondary fw-bold">
                <span class="fs-5">📅 Panel de Búsqueda</span>
            </div>
            <form action="index.php" method="GET" class="d-flex align-items-center m-0">
                <input type="hidden" name="vista" value="actividades_diarias">
                <label class="fw-bold me-3 text-secondary small">Ver reportes del día:</label>
                <input type="date" name="fecha" class="form-control form-control-sm w-auto me-3 border-primary shadow-sm" value="<?php echo $fecha_seleccionada; ?>" required>
                <button type="submit" class="btn btn-sm btn-primary fw-bold shadow-sm px-4">Buscar Reportes</button>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold text-secondary py-3">
            📋 Reportes Registrados - <?php echo date('d/m/Y', strtotime($fecha_seleccionada)); ?>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-0 align-middle" style="font-size: 0.95rem;">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th class="text-start ps-4">Trabajador</th>
                            <th class="text-start" style="width: 45%;">Reporte de Actividad</th>
                            <th class="text-start">Observaciones del Supervisor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($listaActividades) > 0): ?>
                            <?php foreach ($listaActividades as $act): ?>
                                <tr>
                                    <td class="fw-bold text-dark text-nowrap text-start ps-4">
                                        👤 <?php echo $act['nombres'] . ' ' . $act['apellidos']; ?>
                                    </td>
                                    <td class="text-start text-muted">
                                        <?php echo nl2br(htmlspecialchars($act['descripcion_actividad'])); ?>
                                    </td>
                                    <td class="text-start text-muted small">
                                        <?php echo htmlspecialchars($act['observaciones_supervisor']) ?: '<span class="text-light-emphasis fst-italic">Ninguna</span>'; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center py-5 text-muted">No hay actividades reportadas en la fecha seleccionada.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalActividadDiaria" tabindex="-1" aria-labelledby="modalActividadDiariaLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalActividadDiariaLabel">
                    ➕ Redactar Nuevo Reporte de Actividad
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="index.php?accion=guardar_actividad_diaria" method="POST">
                <div class="modal-body p-4 bg-light">
                    
                    <div class="row bg-white p-3 shadow-sm rounded mb-3">
                        <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Datos del Registro</h6>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-secondary small fw-bold">Fecha de la Actividad <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha" value="<?php echo $fecha_seleccionada; ?>" required>
                        </div>

                        <div class="col-md-8 mb-3">
                            <label class="form-label text-secondary small fw-bold">Seleccionar Trabajador <span class="text-danger">*</span></label>
                            <select class="form-select border-primary" name="id_trabajador" required>
                                <option value="">Seleccione al personal...</option>
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
                    </div>

                    <div class="row bg-white p-3 shadow-sm rounded">
                        <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Detalle de Operaciones</h6>
                        <div class="col-12 mb-3">
                            <label class="form-label text-secondary small fw-bold">Descripción del Trabajo Realizado <span class="text-danger">*</span></label>
                            <textarea class="form-control bg-light" name="descripcion_actividad" rows="4" placeholder="Ej: Se realizó la limpieza profunda del parque principal, mantenimiento de tuberías y pintado de bancas..." required></textarea>
                        </div>

                        <div class="col-12 mb-2">
                            <label class="form-label text-secondary small fw-bold">Observaciones del Supervisor <span class="text-muted">(Opcional)</span></label>
                            <input type="text" class="form-control bg-light" name="observaciones_supervisor" placeholder="Ej: Faltó material, el clima retrasó las labores, etc.">
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-white border-top">
                    <button type="button" class="btn btn-secondary fw-bold px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold px-5">💾 Guardar Reporte</button>
                </div>
            </form>
        </div>
    </div>
</div>