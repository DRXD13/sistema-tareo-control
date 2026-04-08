<?php
require_once "models/Trabajador.php";
require_once "models/Area.php";
require_once "models/Cargo.php";
require_once "models/Cuadrilla.php";

$trabajadorModel = new Trabajador();
$listaTrabajadores = $trabajadorModel->listarTrabajadores();

$areaModel = new Area();
$listaAreas = $areaModel->listarAreas();

$cargoModel = new Cargo();
$listaCargos = $cargoModel->listarCargos();

$cuadrillaModel = new Cuadrilla();
$listaCuadrillas = $cuadrillaModel->listarCuadrillas();

$trabajadorEditar = null;
if (isset($_GET['editar'])) {
    $trabajadorEditar = $trabajadorModel->obtenerTrabajador($_GET['editar']);
}
?>

<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary m-0">👥 Gestión de Trabajadores</h3>
        <?php if($trabajadorEditar): ?>
            <a href="index.php?vista=trabajadores" class="btn btn-secondary">Volver a Nuevo</a>
        <?php endif; ?>
    </div>

    <?php if(isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Aviso!</strong> 
            <?php 
                if($_GET['mensaje'] == 'exito') echo "El trabajador se guardó correctamente.";
                if($_GET['mensaje'] == 'exito_editar') echo "El trabajador se actualizó correctamente.";
                if($_GET['mensaje'] == 'exito_estado') echo "El estado se modificó correctamente.";
                if($_GET['mensaje'] == 'error') echo "Ocurrió un error en la operación.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm <?php echo $trabajadorEditar ? 'border-primary border-top border-3' : ''; ?>">
                <div class="card-header bg-white fw-bold text-secondary">
                    <?php echo $trabajadorEditar ? '✏️ Editar Trabajador' : '➕ Nuevo Trabajador'; ?>
                </div>
                <div class="card-body">
                    <form action="index.php?accion=<?php echo $trabajadorEditar ? 'actualizar_trabajador' : 'guardar_trabajador'; ?>" method="POST">
                        <?php if($trabajadorEditar): ?>
                            <input type="hidden" name="id_trabajador" value="<?php echo $trabajadorEditar['id_trabajador']; ?>">
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Nombres</label>
                                <input type="text" class="form-control form-control-sm" name="nombres" value="<?php echo $trabajadorEditar ? $trabajadorEditar['nombres'] : ''; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Apellidos</label>
                                <input type="text" class="form-control form-control-sm" name="apellidos" value="<?php echo $trabajadorEditar ? $trabajadorEditar['apellidos'] : ''; ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Tipo Doc.</label>
                                <select class="form-select form-select-sm" name="tipo_documento" required>
                                    <option value="DNI" <?php echo ($trabajadorEditar && $trabajadorEditar['tipo_documento'] == 'DNI') ? 'selected' : ''; ?>>DNI</option>
                                    <option value="CE" <?php echo ($trabajadorEditar && $trabajadorEditar['tipo_documento'] == 'CE') ? 'selected' : ''; ?>>Carnet Ext.</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Número Doc.</label>
                                <input type="text" class="form-control form-control-sm" name="numero_documento" value="<?php echo $trabajadorEditar ? $trabajadorEditar['numero_documento'] : ''; ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Área</label>
                            <select class="form-select form-select-sm" name="id_area" required>
                                <option value="">Seleccione...</option>
                                <?php foreach($listaAreas as $area): ?>
                                    <?php if($area['estado'] == 1): ?>
                                        <option value="<?php echo $area['id_area']; ?>" <?php echo ($trabajadorEditar && $trabajadorEditar['id_area'] == $area['id_area']) ? 'selected' : ''; ?>>
                                            <?php echo $area['nombre_area']; ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Cargo</label>
                            <select class="form-select form-select-sm" name="id_cargo" required>
                                <option value="">Seleccione...</option>
                                <?php foreach($listaCargos as $cargo): ?>
                                    <?php if($cargo['estado'] == 1): ?>
                                        <option value="<?php echo $cargo['id_cargo']; ?>" <?php echo ($trabajadorEditar && $trabajadorEditar['id_cargo'] == $cargo['id_cargo']) ? 'selected' : ''; ?>>
                                            <?php echo $cargo['nombre_cargo']; ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-primary small fw-bold">Jornal Diario (S/)</label>
                            <input type="number" step="0.10" class="form-control form-control-sm border-primary" name="jornal_diario" value="<?php echo $trabajadorEditar ? $trabajadorEditar['jornal_diario'] : '50.00'; ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Cuadrilla (Opcional)</label>
                                <select class="form-select form-select-sm" name="id_cuadrilla">
                                    <option value="">Ninguna</option>
                                    <?php foreach($listaCuadrillas as $cuadrilla): ?>
                                        <?php if($cuadrilla['estado'] == 1): ?>
                                            <option value="<?php echo $cuadrilla['id_cuadrilla']; ?>" <?php echo ($trabajadorEditar && $trabajadorEditar['id_cuadrilla'] == $cuadrilla['id_cuadrilla']) ? 'selected' : ''; ?>>
                                                <?php echo $cuadrilla['nombre_cuadrilla']; ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Fecha Ingreso</label>
                                <input type="date" class="form-control form-control-sm" name="fecha_ingreso" value="<?php echo $trabajadorEditar ? $trabajadorEditar['fecha_ingreso'] : date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn <?php echo $trabajadorEditar ? 'btn-success' : 'btn-primary'; ?> w-100 fw-bold">
                            <?php echo $trabajadorEditar ? 'Actualizar Cambios' : 'Guardar Trabajador'; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold text-secondary">
                    Personal Registrado
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-hover m-0 text-center align-middle" style="font-size: 0.9rem;">
                        <thead class="table-dark">
                            <tr>
                                <th>Doc.</th>
                                <th>Nombres y Apellidos</th>
                                <th>Área / Cargo</th>
                                <th class="text-success">Jornal (S/)</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($listaTrabajadores) > 0): ?>
                                <?php foreach ($listaTrabajadores as $t): ?>
                                    <tr>
                                        <td class="text-muted"><?php echo $t['tipo_documento'].': '.$t['numero_documento']; ?></td>
                                        <td class="fw-bold text-start"><?php echo $t['nombres'].' '.$t['apellidos']; ?></td>
                                        <td class="text-start">
                                            <span class="badge bg-info text-dark"><?php echo $t['nombre_area']; ?></span><br>
                                            <small class="text-muted"><?php echo $t['nombre_cargo']; ?></small>
                                        </td>
                                        <td class="fw-bold text-success">S/ <?php echo number_format($t['jornal_diario'], 2); ?></td>
                                        <td>
                                            <?php if ($t['estado'] == 1): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="index.php?vista=trabajadores&editar=<?php echo $t['id_trabajador']; ?>" class="btn btn-sm btn-outline-primary mb-1">✏️</a>
                                            <a href="index.php?accion=cambiar_estado_trabajador&id=<?php echo $t['id_trabajador']; ?>&estado=<?php echo $t['estado']; ?>" 
                                               class="btn btn-sm <?php echo $t['estado'] == 1 ? 'btn-outline-danger' : 'btn-outline-success'; ?> mb-1">
                                                <?php echo $t['estado'] == 1 ? '🚫' : '✅'; ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center py-4 text-muted">No hay trabajadores registrados.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>