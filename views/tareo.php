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
        <h3 class="text-primary m-0">📋 Registro de Tareo y Actividades</h3>
    </div>

    <?php if(isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Aviso!</strong> 
            <?php 
                if($_GET['mensaje'] == 'exito') echo "El tareo se guardó correctamente.";
                if($_GET['mensaje'] == 'error') echo "Ocurrió un error al guardar los datos.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-light">
            <form action="index.php" method="GET" class="d-flex align-items-center">
                <input type="hidden" name="vista" value="tareo">
                <label class="fw-bold me-3 text-secondary">Fecha de Tareo:</label>
                <input type="date" name="fecha" class="form-control w-auto me-3 border-primary" value="<?php echo $fecha_seleccionada; ?>" required>
                <button type="submit" class="btn btn-outline-primary fw-bold">Cargar Lista</button>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold text-secondary">
            Personal Activo - <?php echo date('d/m/Y', strtotime($fecha_seleccionada)); ?>
        </div>
        <div class="card-body p-0 table-responsive">
            <form action="index.php?accion=guardar_tareo" method="POST">
                <input type="hidden" name="fecha_tareo" value="<?php echo $fecha_seleccionada; ?>">
                
                <table class="table table-striped table-hover m-0 align-middle" style="font-size: 0.9rem;">
                    <thead class="table-dark text-center">
                        <tr>
                            <th class="text-start">Trabajador</th>
                            <th>Área / Cargo</th>
                            <th>Actividad Diaria</th>
                            <th>Asistencia</th>
                            <th style="width: 100px;">Horas</th>
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
                                <td class="fw-bold">
                                    <input type="hidden" name="id_trabajador[]" value="<?php echo $id; ?>">
                                    <?php echo $trabajador['nombres'] . ' ' . $trabajador['apellidos']; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info text-dark"><?php echo $trabajador['nombre_area']; ?></span>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm" name="id_actividad[]">
                                        <option value="">Ninguna...</option>
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
                                    <select class="form-select form-select-sm" name="estado_asistencia[]">
                                        <option value="Presente" <?php echo ($estadoActual == 'Presente') ? 'selected' : ''; ?>>✅ Presente</option>
                                        <option value="Falto" <?php echo ($estadoActual == 'Falto') ? 'selected' : ''; ?>>❌ Falto</option>
                                        <option value="Tardanza" <?php echo ($estadoActual == 'Tardanza') ? 'selected' : ''; ?>>⏰ Tardanza</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.5" class="form-control form-control-sm text-center" name="horas_trabajadas[]" value="<?php echo $horasActuales; ?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="observaciones[]" value="<?php echo $obsActual; ?>">
                                </td>
                            </tr>
                        <?php 
                            endif;
                        endforeach; 
                        
                        if (!$hayActivos): 
                        ?>
                            <tr><td colspan="6" class="text-center py-4 text-danger fw-bold">No hay trabajadores activos.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <?php if ($hayActivos): ?>
                    <div class="p-3 bg-light border-top text-end">
                        <button type="submit" class="btn btn-primary fw-bold px-5">💾 Guardar Tareo y Actividades</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>