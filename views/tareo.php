<?php
require_once "models/Trabajador.php";
require_once "models/Tareo.php";

// 1. Determinar la fecha a procesar (por defecto carga la fecha de hoy)
$fecha_seleccionada = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

// 2. Obtener todos los trabajadores
$trabajadorModel = new Trabajador();
$listaTrabajadores = $trabajadorModel->listarTrabajadores();

// 3. Obtener el tareo que ya esté registrado en esta fecha (si es que existe)
$tareoModel = new Tareo();
$tareoRegistrado = $tareoModel->listarTareoPorFecha($fecha_seleccionada);

// Creamos un diccionario para buscar rápido si el trabajador ya tiene asistencia hoy
$datosTareo = [];
foreach ($tareoRegistrado as $t) {
    $datosTareo[$t['id_trabajador']] = $t;
}
?>

<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary m-0">📋 Registro de Tareo Diario</h3>
    </div>

    <?php if(isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Aviso!</strong> 
            <?php 
                if($_GET['mensaje'] == 'exito') echo "El tareo masivo se guardó correctamente.";
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
                            <th>Estado de Asistencia</th>
                            <th style="width: 120px;">Horas</th>
                            <th>Observaciones (Opcional)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $hayActivos = false;
                        foreach ($listaTrabajadores as $trabajador): 
                            // Solo mostramos a los trabajadores que están Activos (estado = 1)
                            if ($trabajador['estado'] == 1):
                                $hayActivos = true;
                                $id = $trabajador['id_trabajador'];
                                
                                // Verificamos si este trabajador ya tiene datos guardados hoy
                                $estadoActual = isset($datosTareo[$id]) ? $datosTareo[$id]['estado_asistencia'] : 'Presente';
                                $horasActuales = isset($datosTareo[$id]) ? $datosTareo[$id]['horas_trabajadas'] : '8.00';
                                $obsActual = isset($datosTareo[$id]) ? $datosTareo[$id]['observaciones'] : '';
                        ?>
                            <tr>
                                <td class="fw-bold">
                                    <input type="hidden" name="id_trabajador[]" value="<?php echo $id; ?>">
                                    <?php echo $trabajador['nombres'] . ' ' . $trabajador['apellidos']; ?>
                                    <br><small class="text-muted fw-normal">Doc: <?php echo $trabajador['numero_documento']; ?></small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info text-dark"><?php echo $trabajador['nombre_area']; ?></span><br>
                                    <small class="text-muted"><?php echo $trabajador['nombre_cargo']; ?></small>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm" name="estado_asistencia[]">
                                        <option value="Presente" <?php echo ($estadoActual == 'Presente') ? 'selected' : ''; ?>>✅ Presente</option>
                                        <option value="Falto" <?php echo ($estadoActual == 'Falto') ? 'selected' : ''; ?>>❌ Falto</option>
                                        <option value="Tardanza" <?php echo ($estadoActual == 'Tardanza') ? 'selected' : ''; ?>>⏰ Tardanza</option>
                                        <option value="Permiso" <?php echo ($estadoActual == 'Permiso') ? 'selected' : ''; ?>>📝 Permiso</option>
                                        <option value="Descanso" <?php echo ($estadoActual == 'Descanso') ? 'selected' : ''; ?>>🏥 Descanso Médico</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.5" class="form-control form-control-sm text-center" name="horas_trabajadas[]" value="<?php echo $horasActuales; ?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="observaciones[]" value="<?php echo $obsActual; ?>" placeholder="Escribir motivo...">
                                </td>
                            </tr>
                        <?php 
                            endif;
                        endforeach; 
                        
                        if (!$hayActivos): 
                        ?>
                            <tr><td colspan="5" class="text-center py-4 text-danger fw-bold">No hay trabajadores activos registrados en el sistema.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <?php if ($hayActivos): ?>
                    <div class="p-3 bg-light border-top text-end">
                        <button type="submit" class="btn btn-primary fw-bold px-5">💾 Guardar Tareo del Día</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>