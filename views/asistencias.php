<?php
require_once "models/Trabajador.php";
require_once "models/Asistencia.php";

$fecha_seleccionada = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

$trabajadorModel = new Trabajador();
$listaTrabajadores = $trabajadorModel->listarTrabajadores();

$asistenciaModel = new Asistencia();
$listaAsistencias = $asistenciaModel->listarAsistenciasHoy($fecha_seleccionada);

$datosAsistencia = [];
foreach ($listaAsistencias as $a) {
    $datosAsistencia[$a['id_trabajador']] = $a;
}
?>

<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-primary m-0 fw-bold">⏱️ Control de Asistencias y Permisos</h3>
            <p class="text-muted small m-0">Registra el ingreso, salida o justificación del personal activo.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-white d-flex align-items-center justify-content-between p-3">
            <div class="text-secondary fw-bold">
                <span class="fs-5">📅 Panel de Registro Diario</span>
            </div>
            <form action="index.php" method="GET" class="d-flex align-items-center m-0">
                <input type="hidden" name="vista" value="asistencias">
                <label class="fw-bold me-3 text-secondary small">Seleccionar Día:</label>
                <input type="date" name="fecha" class="form-control form-control-sm w-auto me-3 border-primary shadow-sm" value="<?php echo $fecha_seleccionada; ?>" required>
                <button type="submit" class="btn btn-sm btn-primary fw-bold shadow-sm px-4">Consultar Día</button>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold text-secondary py-3 d-flex justify-content-between align-items-center">
            <span>👥 Personal Activo - <?php echo date('d/m/Y', strtotime($fecha_seleccionada)); ?></span>
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">Regla de Negocio RN04 Activa</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-0 align-middle text-center" style="font-size: 0.95rem;">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th class="text-start ps-4">Trabajador</th>
                            <th>Área / Cargo</th>
                            <th>Estado Actual</th>
                            <th>Acción Requerida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $hayActivos = false;
                        foreach ($listaTrabajadores as $t): 
                            if ($t['estado'] == 1):
                                $hayActivos = true;
                                $id = $t['id_trabajador'];
                                $asistenciaHoy = isset($datosAsistencia[$id]) ? $datosAsistencia[$id] : null;
                        ?>
                            <tr>
                                <td class="text-start ps-4">
                                    <span class="fw-bold text-dark"><?php echo $t['nombres'] . ' ' . $t['apellidos']; ?></span><br>
                                    <small class="text-muted">Doc: <?php echo $t['numero_documento']; ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary"><?php echo $t['nombre_area']; ?></span><br>
                                    <small class="text-muted fw-bold"><?php echo $t['nombre_cargo']; ?></small>
                                </td>
                                
                                <td class="bg-light">
                                    <?php if (!$asistenciaHoy): ?>
                                        <span class="badge bg-secondary px-3 py-2 rounded-pill">Sin marcar</span>
                                    <?php else: ?>
                                        <div class="fw-bold <?php echo ($asistenciaHoy['estado'] == 'Tardanza' || $asistenciaHoy['estado'] == 'Falta Justificada') ? 'text-warning' : 'text-success'; ?>">
                                            <?php echo $asistenciaHoy['estado']; ?>
                                        </div>
                                        <?php if ($asistenciaHoy['hora_ingreso'] != '00:00:00'): ?>
                                            <div class="text-primary fw-bold small mt-1">
                                                <span class="text-muted fw-normal">Ingreso:</span> <?php echo date('h:i A', strtotime($asistenciaHoy['hora_ingreso'])); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($asistenciaHoy['hora_salida']): ?>
                                            <div class="text-danger fw-bold small mt-1">
                                                <span class="text-muted fw-normal">Salida:</span> <?php echo date('h:i A', strtotime($asistenciaHoy['hora_salida'])); ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if (!$asistenciaHoy): ?>
                                        <form action="index.php?accion=guardar_ingreso" method="POST" class="d-flex justify-content-center align-items-center gap-2">
                                            <input type="hidden" name="id_trabajador" value="<?php echo $id; ?>">
                                            <input type="hidden" name="fecha" value="<?php echo $fecha_seleccionada; ?>">
                                            
                                            <input type="time" class="form-control form-control-sm shadow-sm" name="hora_ingreso" style="width: 120px;" title="Hora de Ingreso">
                                            
                                            <select class="form-select form-select-sm border-primary fw-bold shadow-sm" name="estado" style="width: 150px;">
                                                <option value="Puntual">Puntual</option>
                                                <option value="Tardanza">Tardanza</option>
                                                <option value="Falta Justificada">Falta Justificada</option>
                                                <option value="Descanso Médico">Descanso Médico</option>
                                                <option value="Permiso Especial">Permiso Especial</option>
                                            </select>
                                            
                                            <input type="text" class="form-control form-control-sm shadow-sm" name="observaciones" placeholder="Obs..." style="width: 120px;">
                                            <button type="submit" class="btn btn-sm btn-primary fw-bold shadow-sm px-3">Registrar</button>
                                        </form>

                                    <?php elseif ($asistenciaHoy && !$asistenciaHoy['hora_salida'] && in_array($asistenciaHoy['estado'], ['Puntual', 'Tardanza'])): ?>
                                        <form action="index.php?accion=guardar_salida" method="POST" class="d-flex justify-content-center align-items-center gap-2">
                                            <input type="hidden" name="id_asistencia" value="<?php echo $asistenciaHoy['id_asistencia']; ?>">
                                            
                                            <input type="time" class="form-control form-control-sm border-danger shadow-sm" name="hora_salida" style="width: 130px;" required title="Hora de Salida">
                                            <button type="submit" class="btn btn-sm btn-danger fw-bold shadow-sm px-4">Marcar Salida</button>
                                        </form>

                                    <?php else: ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-4 py-2 rounded-pill">
                                            Jornada/Permiso Completado ✅
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php 
                            endif;
                        endforeach; 
                        
                        if (!$hayActivos):
                        ?>
                            <tr><td colspan="4" class="text-center py-5 text-muted">No hay personal activo para registrar asistencia.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>