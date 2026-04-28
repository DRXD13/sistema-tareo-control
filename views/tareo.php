<?php
require_once "models/Trabajador.php";
require_once "models/Tareo.php";
require_once "models/Actividad.php"; // Llamamos al modelo de actividades

$fecha_seleccionada = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

$trabajadorModel = new Trabajador();
$listaTrabajadores = $trabajadorModel->listarTrabajadores();

$actividadModel = new Actividad();
$listaActividades = $actividadModel->listarActividades(); // Cargamos el catálogo

$tareoModel = new Tareo();
$tareoRegistrado = $tareoModel->listarTareoPorFecha($fecha_seleccionada);

$datosTareo = [];
foreach ($tareoRegistrado as $t) {
    $datosTareo[$t['id_trabajador']] = $t;
}
?>

<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-primary m-0 fw-bold">📋 Registro de Tareo Diario</h3>
            <p class="text-muted small m-0">Asigna actividades, estado y horas de trabajo al personal en campo.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-white d-flex align-items-center justify-content-between p-3">
            <div class="text-secondary fw-bold">
                <span class="fs-5">📅 Panel de Asignación</span>
            </div>
            <form action="index.php" method="GET" class="d-flex align-items-center m-0">
                <input type="hidden" name="vista" value="tareo">
                <label class="fw-bold me-3 text-secondary small">Fecha de Tareo:</label>
                <input type="date" name="fecha" class="form-control form-control-sm w-auto me-3 border-primary shadow-sm" value="<?php echo $fecha_seleccionada; ?>" required>
                <button type="submit" class="btn btn-sm btn-primary fw-bold shadow-sm px-4">Cargar Lista</button>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold text-secondary py-3 d-flex justify-content-between align-items-center">
            <span>👥 Personal Activo - <?php echo date('d/m/Y', strtotime($fecha_seleccionada)); ?></span>
            <span class="badge bg-warning text-dark border border-warning shadow-sm">Asegúrate de revisar las horas antes de guardar</span>
        </div>
        <div class="card-body p-0">
            <form action="index.php?accion=guardar_tareo" method="POST">
                <input type="hidden" name="fecha_tareo" value="<?php echo $fecha_seleccionada; ?>">
                
                <div class="table-responsive">
                    <table class="table table-hover m-0 align-middle" style="font-size: 0.9rem;">
                        <thead class="table-light text-secondary text-center">
                            <tr>
                                <th class="text-start ps-4">Trabajador</th>
                                <th>Área / Cargo</th>
                                <th>Actividad Diaria</th>
                                <th>Asistencia</th>
                                <th style="width: 110px;">Horas</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $hayActivos = false;
                            foreach ($listaTrabajadores as $trabajador): 
                                if ($trabajador['estado'] == 1):
                                    $hayActivos = true;
                                    $id = $trabajador['id_trabajador'];
                                    
                                    $actividadActual = isset($datosTareo[$id]) ? $datosTareo[$id]['id_actividad'] : '';
                                    $estadoActual = isset($datosTareo[$id]) ? $datosTareo[$id]['estado_asistencia'] : 'Presente';
                                    $horasActuales = isset($datosTareo[$id]) ? $datosTareo[$id]['horas_trabajadas'] : '8.00';
                                    $obsActual = isset($datosTareo[$id]) ? $datosTareo[$id]['observaciones'] : '';
                            ?>
                                <tr>
                                    <td class="text-start ps-4">
                                        <input type="hidden" name="id_trabajador[]" value="<?php echo $id; ?>">
                                        <span class="fw-bold text-dark"><?php echo $trabajador['nombres'] . ' ' . $trabajador['apellidos']; ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary"><?php echo $trabajador['nombre_area']; ?></span>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm shadow-sm" name="id_actividad[]">
                                            <option value="">Sin actividad asignada...</option>
                                            <?php foreach ($listaActividades as $act): ?>
                                                <?php if ($act['estado'] == 1): ?>
                                                    <option value="<?php echo $act['id_actividad']; ?>" <?php echo ($actividadActual == $act['id_actividad']) ? 'selected' : ''; ?>>
                                                        <?php echo $act['nombre_actividad']; ?>
                                                    </option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm shadow-sm <?php echo $estadoActual == 'Falto' ? 'border-danger text-danger fw-bold' : ($estadoActual == 'Tardanza' ? 'border-warning text-warning fw-bold' : 'border-success text-success fw-bold'); ?>" name="estado_asistencia[]" onchange="cambiarColorSelect(this)">
                                            <option value="Presente" <?php echo ($estadoActual == 'Presente') ? 'selected' : ''; ?>>✅ Presente</option>
                                            <option value="Falto" <?php echo ($estadoActual == 'Falto') ? 'selected' : ''; ?>>❌ Falto</option>
                                            <option value="Tardanza" <?php echo ($estadoActual == 'Tardanza') ? 'selected' : ''; ?>>⏰ Tardanza</option>
                                        </select>
                                    </td>
                                    <td class="px-2">
                                        <input type="number" step="0.5" min="0" max="24" class="form-control form-control-sm text-center shadow-sm fw-bold bg-light" name="horas_trabajadas[]" value="<?php echo $horasActuales; ?>">
                                    </td>
                                    <td class="pe-4">
                                        <input type="text" class="form-control form-control-sm shadow-sm" name="observaciones[]" value="<?php echo $obsActual; ?>" placeholder="Opcional...">
                                    </td>
                                </tr>
                            <?php 
                                endif;
                            endforeach; 
                            
                            if (!$hayActivos): 
                            ?>
                                <tr><td colspan="6" class="text-center py-5 text-muted">No hay trabajadores activos para registrar tareo.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($hayActivos): ?>
                    <div class="p-4 bg-light border-top d-flex justify-content-end align-items-center">
                        <span class="text-muted small me-4">Verifica que las horas y actividades sean correctas.</span>
                        <button type="submit" class="btn btn-primary fw-bold px-5 shadow-sm">💾 Guardar Tareo Masivo</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<script>
function cambiarColorSelect(select) {
    select.classList.remove('border-success', 'text-success', 'border-danger', 'text-danger', 'border-warning', 'text-warning');
    if(select.value === 'Falto') {
        select.classList.add('border-danger', 'text-danger');
    } else if (select.value === 'Tardanza') {
        select.classList.add('border-warning', 'text-warning');
    } else {
        select.classList.add('border-success', 'text-success');
    }
}
</script>