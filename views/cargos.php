<?php
require_once "models/Cargo.php";
$cargoModel = new Cargo();
$listaCargos = $cargoModel->listarCargos();

$cargoEditar = null;
if (isset($_GET['editar'])) {
    $cargoEditar = $cargoModel->obtenerCargo($_GET['editar']);
}
?>

<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-primary m-0 fw-bold">💼 Gestión de Cargos</h3>
            <p class="text-muted small m-0">Administra los puestos de trabajo del personal</p>
        </div>
        
        <?php if(!$cargoEditar): ?>
            <button type="button" class="btn btn-primary fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalCargo">
                ➕ Nuevo Cargo
            </button>
        <?php endif; ?>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold text-secondary py-3">
            📋 Cargos Registrados
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-0 text-center align-middle">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>ID</th>
                            <th class="text-start">Nombre del Cargo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($listaCargos) > 0): ?>
                            <?php foreach ($listaCargos as $cargo): ?>
                                <tr>
                                    <td class="text-muted fw-bold">#<?php echo $cargo['id_cargo']; ?></td>
                                    <td class="fw-bold text-start text-dark"><?php echo $cargo['nombre_cargo']; ?></td>
                                    <td>
                                        <?php if ($cargo['estado'] == 1): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 rounded-pill">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 rounded-pill">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="index.php?vista=cargos&editar=<?php echo $cargo['id_cargo']; ?>" class="btn btn-sm btn-outline-primary shadow-sm">✏️ Editar</a>
                                        
                                        <a href="index.php?accion=cambiar_estado_cargo&id=<?php echo $cargo['id_cargo']; ?>&estado=<?php echo $cargo['estado']; ?>" 
                                           class="btn btn-sm <?php echo $cargo['estado'] == 1 ? 'btn-outline-danger' : 'btn-outline-success'; ?> shadow-sm">
                                            <?php echo $cargo['estado'] == 1 ? '🚫 Desactivar' : '✅ Activar'; ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center py-5 text-muted">No hay cargos registrados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCargo" tabindex="-1" aria-labelledby="modalCargoLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            
            <div class="modal-header <?php echo $cargoEditar ? 'bg-warning text-dark' : 'bg-primary text-white'; ?>">
                <h5 class="modal-title fw-bold" id="modalCargoLabel">
                    <?php echo $cargoEditar ? '✏️ Editar Cargo' : '➕ Registrar Nuevo Cargo'; ?>
                </h5>
                <button type="button" class="btn-close <?php echo $cargoEditar ? '' : 'btn-close-white'; ?>" 
                        <?php echo $cargoEditar ? 'onclick="window.location.href=\'index.php?vista=cargos\'"' : 'data-bs-dismiss="modal"'; ?> aria-label="Close"></button>
            </div>

            <form action="index.php?accion=<?php echo $cargoEditar ? 'actualizar_cargo' : 'guardar_cargo'; ?>" method="POST">
                <div class="modal-body p-4">
                    
                    <?php if($cargoEditar): ?>
                        <input type="hidden" name="id_cargo" value="<?php echo $cargoEditar['id_cargo']; ?>">
                        <div class="alert alert-warning small py-2">
                            Estás modificando los datos del cargo <b>#<?php echo $cargoEditar['id_cargo']; ?></b>.
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="nombre_cargo" class="form-label fw-bold text-secondary small">Nombre del Cargo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-primary bg-light" id="nombre_cargo" name="nombre_cargo" 
                               value="<?php echo $cargoEditar ? $cargoEditar['nombre_cargo'] : ''; ?>" required placeholder="Ejemplo: Supervisor de Campo">
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <?php if($cargoEditar): ?>
                        <a href="index.php?vista=cargos" class="btn btn-secondary fw-bold">Cancelar</a>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn <?php echo $cargoEditar ? 'btn-warning text-dark' : 'btn-primary'; ?> fw-bold px-4">
                        <?php echo $cargoEditar ? 'Actualizar Cambios' : 'Guardar Cargo'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if($cargoEditar): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('modalCargo'));
        myModal.show();
    });
</script>
<?php endif; ?>