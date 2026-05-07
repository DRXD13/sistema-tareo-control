<?php
require_once "models/Cuadrilla.php";
$cuadrillaModel = new Cuadrilla();
$listaCuadrillas = $cuadrillaModel->listarCuadrillas();

$cuadrillaEditar = null;
if (isset($_GET['editar'])) {
    $cuadrillaEditar = $cuadrillaModel->obtenerCuadrilla($_GET['editar']);
}
?>

<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-primary m-0 fw-bold">🚜 Gestión de Cuadrillas</h3>
            <p class="text-muted small m-0">Agrupa a los trabajadores para el control en campo</p>
        </div>
        
        <?php if(!$cuadrillaEditar): ?>
            <button type="button" class="btn btn-primary fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalCuadrilla">
                ➕ Nueva Cuadrilla
            </button>
        <?php endif; ?>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold text-secondary py-3">
            📋 Cuadrillas Registradas
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-0 text-center align-middle">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>ID</th>
                            <th class="text-start">Nombre de la Cuadrilla</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($listaCuadrillas) > 0): ?>
                            <?php foreach ($listaCuadrillas as $cuadrilla): ?>
                                <tr>
                                    <td class="text-muted fw-bold"><?php echo $cuadrilla['id_cuadrilla']; ?></td>
                                    <td class="fw-bold text-start text-dark"><?php echo $cuadrilla['nombre_cuadrilla']; ?></td>
                                    <td>
                                        <?php if ($cuadrilla['estado'] == 1): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 rounded-pill">Activa</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 rounded-pill">Inactiva</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="index.php?vista=cuadrillas&editar=<?php echo $cuadrilla['id_cuadrilla']; ?>" class="btn btn-sm btn-outline-primary shadow-sm">✏️ Editar</a>
                                        
                                        <a href="index.php?accion=cambiar_estado_cuadrilla&id=<?php echo $cuadrilla['id_cuadrilla']; ?>&estado=<?php echo $cuadrilla['estado']; ?>" 
                                           class="btn btn-sm <?php echo $cuadrilla['estado'] == 1 ? 'btn-outline-danger' : 'btn-outline-success'; ?> shadow-sm">
                                            <?php echo $cuadrilla['estado'] == 1 ? '🚫 Desactivar' : '✅ Activar'; ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center py-5 text-muted">No hay cuadrillas registradas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCuadrilla" tabindex="-1" aria-labelledby="modalCuadrillaLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            
            <div class="modal-header <?php echo $cuadrillaEditar ? 'bg-warning text-dark' : 'bg-primary text-white'; ?>">
                <h5 class="modal-title fw-bold" id="modalCuadrillaLabel">
                    <?php echo $cuadrillaEditar ? '✏️ Editar Cuadrilla' : '➕ Registrar Nueva Cuadrilla'; ?>
                </h5>
                <button type="button" class="btn-close <?php echo $cuadrillaEditar ? '' : 'btn-close-white'; ?>" 
                        <?php echo $cuadrillaEditar ? 'onclick="window.location.href=\'index.php?vista=cuadrillas\'"' : 'data-bs-dismiss="modal"'; ?> aria-label="Close"></button>
            </div>

            <form action="index.php?accion=<?php echo $cuadrillaEditar ? 'actualizar_cuadrilla' : 'guardar_cuadrilla'; ?>" method="POST">
                <div class="modal-body p-4">
                    
                    <?php if($cuadrillaEditar): ?>
                        <input type="hidden" name="id_cuadrilla" value="<?php echo $cuadrillaEditar['id_cuadrilla']; ?>">
                        <div class="alert alert-warning small py-2">
                            Estás modificando los datos de la cuadrilla <b><?php echo $cuadrillaEditar['id_cuadrilla']; ?></b>.
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="nombre_cuadrilla" class="form-label fw-bold text-secondary small">Nombre de la Cuadrilla <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-primary bg-light" id="nombre_cuadrilla" name="nombre_cuadrilla" 
                               value="<?php echo $cuadrillaEditar ? $cuadrillaEditar['nombre_cuadrilla'] : ''; ?>" required placeholder="Ejemplo: Cuadrilla A - Cosecha">
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <?php if($cuadrillaEditar): ?>
                        <a href="index.php?vista=cuadrillas" class="btn btn-secondary fw-bold">Cancelar</a>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn <?php echo $cuadrillaEditar ? 'btn-warning text-dark' : 'btn-primary'; ?> fw-bold px-4">
                        <?php echo $cuadrillaEditar ? 'Actualizar Cambios' : 'Guardar Cuadrilla'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if($cuadrillaEditar): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('modalCuadrilla'));
        myModal.show();
    });
</script>
<?php endif; ?>