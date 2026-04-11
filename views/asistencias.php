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
        <h3 class="text-primary m-0">⏱️ Control de Asistencias y Permisos</h3>
    </div>

    <?php if(isset($_GET['mensaje'])): ?>
        <?php if($_GET['mensaje'] == 'duplicado'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>🛑 Regla RN04 Activada:</strong> El trabajador ya tiene un registro en esta fecha. No se permiten duplicados.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php else: ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>¡Aviso!</strong> 
                <?php 
                    if($_GET['mensaje'] == 'exito_ingreso') echo "Registro de entrada/permiso guardado correctamente.";
                    if($_GET['mensaje'] == 'exito_salida') echo "Hora de SALIDA registrada correctamente.";
                    if($_GET['mensaje'] == 'error') echo "Ocurrió un error en la operación.";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-light">
            <form action="index.php" method="GET" class="d-flex align-items-center">
                <input type="hidden" name="vista" value="asistencias">
                <label class="fw-bold me-3 text-secondary">Fecha:</label>
                <input type="date" name="fecha" class="form-control w-auto me-3 border-primary" value="<?php echo $fecha_seleccionada; ?>" required>
                <button type="submit" class="btn btn-outline-primary fw-bold">Buscar Día</button>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold text-secondary">
            Personal Activo - <?php echo date('d/m/Y', strtotime($fecha_seleccionada)); ?>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-striped table-hover m-0 align-middle text-center" style="font-size: 0.9rem;">
                <thead class="table-dark">
                    <tr>
                        <th class="text-start">Trabajador</th>
                        <th>Área / Cargo</th>
                        <th>Estado Actual</th>
                        <th>Acción requerida</th>
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
                            <td class="text-start fw-bold"><?php echo $t['nombres'] . ' ' . $t['apellidos']; ?></td>
                            <td><span class="badge bg-info text-dark"><?php echo $t['nombre_area']; ?></span></td>
                            
                            <td>
                                <?php if (!$asistenciaHoy): ?>
                                    <span class="badge bg-secondary">Sin marcar</span>
                                <?php else: ?>
                                    <div class="text-success fw-bold small">
                                        Estado: <?php echo $asistenciaHoy['estado']; ?>
                                    </div>
                                    <div class="text-primary fw-bold small mt-1">
                                        <?php echo ($asistenciaHoy['hora_ingreso'] != '00:00:00') ? 'Entrada: ' . date('h:i A', strtotime($asistenciaHoy['hora_ingreso'])) : ''; ?>
                                    </div>
                                    <?php if ($asistenciaHoy['hora_salida']): ?>
                                        <div class="text-danger fw-bold small mt-1">
                                            Salida: <?php echo date('h:i A', strtotime($asistenciaHoy['hora_salida'])); ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if (!$asistenciaHoy): ?>
                                    <form action="index.php?accion=guardar_ingreso" method="POST" class="d-flex justify-content-center align-items-center gap-2">
                                        <input type="hidden" name="id_trabajador" value="<?php echo $id; ?>">
                                        <input type="hidden" name="fecha" value="<?php echo $fecha_seleccionada; ?>">
                                        
                                        <input type="time" class="form-control form-control-sm" name="hora_ingreso" style="width: 110px;">
                                        
                                        <select class="form-select form-select-sm border-primary fw-bold" name="estado" style="width: 140px;">
                                            <option value="Puntual">Puntual</option>
                                            <option value="Tardanza">Tardanza</option>
                                            <option value="Falta Justificada">Falta Justificada</option>
                                            <option value="Descanso Médico">Descanso Médico</option>
                                            <option value="Permiso Especial">Permiso Especial</option>
                                        </select>
                                        <input type="text" class="form-control form-control-sm" name="observaciones" placeholder="Obs..." style="width: 100px;">
                                        <button type="submit" class="btn btn-sm btn-success fw-bold">Registrar</button>
                                    </form>

                                <?php elseif ($asistenciaHoy && !$asistenciaHoy['hora_salida'] && in_array($asistenciaHoy['estado'], ['Puntual', 'Tardanza'])): ?>
                                    <form action="index.php?accion=guardar_salida" method="POST" class="d-flex justify-content-center align-items-center gap-2">
                                        <input type="hidden" name="id_asistencia" value="<?php echo $asistenciaHoy['id_asistencia']; ?>">
                                        <input type="time" class="form-control form-control-sm" name="hora_salida" style="width: 110px;" required>
                                        <button type="submit" class="btn btn-sm btn-danger fw-bold">Salió</button>
                                    </form>

                                <?php else: ?>
                                    <span class="badge bg-success">Jornada/Permiso Completado ✅</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>