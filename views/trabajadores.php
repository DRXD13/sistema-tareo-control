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
        <div>
            <h3 class="text-primary m-0 fw-bold">👥 Gestión de Personal</h3>
            <p class="text-muted small m-0">Administra a todos los trabajadores de la empresa</p>
        </div>
        
        <?php if(!$trabajadorEditar): ?>
            <button type="button" class="btn btn-primary fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalTrabajador">
                ➕ Nuevo Trabajador
            </button>
        <?php endif; ?>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold text-secondary py-3">
            📋 Personal Registrado
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-0 text-center align-middle" style="font-size: 0.95rem;">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>Doc.</th>
                            <th class="text-start">Nombres y Apellidos</th>
                            <th class="text-start">Área / Cargo</th>
                            <th class="text-success">Jornal (S/)</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($listaTrabajadores) > 0): ?>
                            <?php foreach ($listaTrabajadores as $t): ?>
                                <tr>
                                    <td class="text-muted fw-bold"><?php echo $t['tipo_documento'].': '.$t['numero_documento']; ?></td>
                                    <td class="fw-bold text-start text-dark"><?php echo $t['nombres'].' '.$t['apellidos']; ?></td>
                                    <td class="text-start">
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary"><?php echo $t['nombre_area']; ?></span><br>
                                        <small class="text-muted fw-bold"><?php echo $t['nombre_cargo']; ?></small>
                                    </td>
                                    <td class="fw-bold text-success">S/ <?php echo number_format($t['jornal_diario'], 2); ?></td>
                                    <td>
                                        <?php if ($t['estado'] == 1): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 rounded-pill">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 rounded-pill">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="index.php?vista=trabajadores&editar=<?php echo $t['id_trabajador']; ?>" class="btn btn-sm btn-outline-primary shadow-sm mb-1">✏️</a>
                                        
                                        <a href="index.php?accion=cambiar_estado_trabajador&id=<?php echo $t['id_trabajador']; ?>&estado=<?php echo $t['estado']; ?>" 
                                           class="btn btn-sm <?php echo $t['estado'] == 1 ? 'btn-outline-danger' : 'btn-outline-success'; ?> shadow-sm mb-1">
                                            <?php echo $t['estado'] == 1 ? '🚫' : '✅'; ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted">No hay trabajadores registrados en el sistema.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTrabajador" tabindex="-1" aria-labelledby="modalTrabajadorLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            
            <div class="modal-header <?php echo $trabajadorEditar ? 'bg-warning text-dark' : 'bg-primary text-white'; ?>">
                <h5 class="modal-title fw-bold" id="modalTrabajadorLabel">
                    <?php echo $trabajadorEditar ? '✏️ Editar Datos del Trabajador' : '➕ Registrar Nuevo Trabajador'; ?>
                </h5>
                <button type="button" class="btn-close <?php echo $trabajadorEditar ? '' : 'btn-close-white'; ?>" 
                        <?php echo $trabajadorEditar ? 'onclick="window.location.href=\'index.php?vista=trabajadores\'"' : 'data-bs-dismiss="modal"'; ?> aria-label="Close"></button>
            </div>

            <form action="index.php?accion=<?php echo $trabajadorEditar ? 'actualizar_trabajador' : 'guardar_trabajador'; ?>" method="POST">
                <div class="modal-body p-4 bg-light">
                    
                    <?php if($trabajadorEditar): ?>
                        <input type="hidden" name="id_trabajador" value="<?php echo $trabajadorEditar['id_trabajador']; ?>">
                        <div class="alert alert-warning small py-2">
                            Estás modificando los datos del trabajador <b><?php echo $trabajadorEditar['nombres']; ?></b>.
                        </div>
                    <?php endif; ?>

                    <div class="row bg-white p-3 shadow-sm rounded mb-3">
                        <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Información Personal</h6>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary small fw-bold">Nombres <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombres" value="<?php echo $trabajadorEditar ? $trabajadorEditar['nombres'] : ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary small fw-bold">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="apellidos" value="<?php echo $trabajadorEditar ? $trabajadorEditar['apellidos'] : ''; ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary small fw-bold">Tipo Doc. <span class="text-danger">*</span></label>
                            <select class="form-select" name="tipo_documento" required>
                                <option value="DNI" <?php echo ($trabajadorEditar && $trabajadorEditar['tipo_documento'] == 'DNI') ? 'selected' : ''; ?>>DNI</option>
                                <option value="CE" <?php echo ($trabajadorEditar && $trabajadorEditar['tipo_documento'] == 'CE') ? 'selected' : ''; ?>>Carnet Extranjería</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary small fw-bold">Número Doc. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="numero_documento" value="<?php echo $trabajadorEditar ? $trabajadorEditar['numero_documento'] : ''; ?>" required>
                        </div>
                    </div>

                    <div class="row bg-white p-3 shadow-sm rounded">
                        <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Datos Laborales</h6>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary small fw-bold">Área <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_area" required>
                                <option value="">Seleccione el área...</option>
                                <?php foreach($listaAreas as $area): ?>
                                    <?php if($area['estado'] == 1): ?>
                                        <option value="<?php echo $area['id_area']; ?>" <?php echo ($trabajadorEditar && $trabajadorEditar['id_area'] == $area['id_area']) ? 'selected' : ''; ?>>
                                            <?php echo $area['nombre_area']; ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary small fw-bold">Cargo <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_cargo" required>
                                <option value="">Seleccione el cargo...</option>
                                <?php foreach($listaCargos as $cargo): ?>
                                    <?php if($cargo['estado'] == 1): ?>
                                        <option value="<?php echo $cargo['id_cargo']; ?>" <?php echo ($trabajadorEditar && $trabajadorEditar['id_cargo'] == $cargo['id_cargo']) ? 'selected' : ''; ?>>
                                            <?php echo $cargo['nombre_cargo']; ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label text-success small fw-bold">Jornal Diario (S/) <span class="text-danger">*</span></label>
                            <input type="number" step="0.10" class="form-control border-success bg-success bg-opacity-10 fw-bold" name="jornal_diario" value="<?php echo $trabajadorEditar ? $trabajadorEditar['jornal_diario'] : '50.00'; ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-secondary small fw-bold">Cuadrilla</label>
                            <select class="form-select" name="id_cuadrilla">
                                <option value="">Sin Cuadrilla</option>
                                <?php foreach($listaCuadrillas as $cuadrilla): ?>
                                    <?php if($cuadrilla['estado'] == 1): ?>
                                        <option value="<?php echo $cuadrilla['id_cuadrilla']; ?>" <?php echo ($trabajadorEditar && $trabajadorEditar['id_cuadrilla'] == $cuadrilla['id_cuadrilla']) ? 'selected' : ''; ?>>
                                            <?php echo $cuadrilla['nombre_cuadrilla']; ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-secondary small fw-bold">Fecha Ingreso <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_ingreso" value="<?php echo $trabajadorEditar ? $trabajadorEditar['fecha_ingreso'] : date('Y-m-d'); ?>" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-white border-top">
                    <?php if($trabajadorEditar): ?>
                        <a href="index.php?vista=trabajadores" class="btn btn-secondary fw-bold px-4">Cancelar</a>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary fw-bold px-4" data-bs-dismiss="modal">Cancelar</button>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn <?php echo $trabajadorEditar ? 'btn-warning text-dark' : 'btn-primary'; ?> fw-bold px-5">
                        <?php echo $trabajadorEditar ? '💾 Actualizar Trabajador' : '💾 Guardar Trabajador'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if($trabajadorEditar): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('modalTrabajador'));
        myModal.show();
    });
</script>
<?php endif; ?>