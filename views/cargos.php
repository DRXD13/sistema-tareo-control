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
        <h3 class="text-primary m-0">💼 Gestión de Cargos</h3>
        <?php if($cargoEditar): ?>
            <a href="index.php?vista=cargos" class="btn btn-secondary">Volver a Nuevo</a>
        <?php endif; ?>
    </div>

    <?php if(isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Aviso!</strong> 
            <?php 
                if($_GET['mensaje'] == 'exito') echo "El cargo se guardó correctamente.";
                if($_GET['mensaje'] == 'exito_editar') echo "El cargo se actualizó correctamente.";
                if($_GET['mensaje'] == 'exito_estado') echo "El estado se modificó correctamente.";
                if($_GET['mensaje'] == 'error') echo "Ocurrió un error en la operación.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm <?php echo $cargoEditar ? 'border-primary border-top border-3' : ''; ?>">
                <div class="card-header bg-white fw-bold text-secondary">
                    <?php echo $cargoEditar ? '✏️ Editar Cargo' : '➕ Nuevo Cargo'; ?>
                </div>
                <div class="card-body">
                    <form action="index.php?accion=<?php echo $cargoEditar ? 'actualizar_cargo' : 'guardar_cargo'; ?>" method="POST">
                        <?php if($cargoEditar): ?>
                            <input type="hidden" name="id_cargo" value="<?php echo $cargoEditar['id_cargo']; ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="nombre_cargo" class="form-label">Nombre del Cargo</label>
                            <input type="text" class="form-control" id="nombre_cargo" name="nombre_cargo" 
                                   value="<?php echo $cargoEditar ? $cargoEditar['nombre_cargo'] : ''; ?>" placeholder="Ej. Supervisor" required>
                        </div>
                        
                        <button type="submit" class="btn <?php echo $cargoEditar ? 'btn-success' : 'btn-primary'; ?> w-100 fw-bold">
                            <?php echo $cargoEditar ? 'Actualizar Cambios' : 'Guardar Cargo'; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold text-secondary">
                    Cargos Registrados
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover m-0 text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($listaCargos) > 0): ?>
                                <?php foreach ($listaCargos as $cargo): ?>
                                    <tr>
                                        <td><?php echo $cargo['id_cargo']; ?></td>
                                        <td class="fw-bold text-start"><?php echo $cargo['nombre_cargo']; ?></td>
                                        <td>
                                            <?php if ($cargo['estado'] == 1): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="index.php?vista=cargos&editar=<?php echo $cargo['id_cargo']; ?>" class="btn btn-sm btn-outline-primary">✏️ Editar</a>
                                            <a href="index.php?accion=cambiar_estado_cargo&id=<?php echo $cargo['id_cargo']; ?>&estado=<?php echo $cargo['estado']; ?>" 
                                               class="btn btn-sm <?php echo $cargo['estado'] == 1 ? 'btn-outline-danger' : 'btn-outline-success'; ?>">
                                                <?php echo $cargo['estado'] == 1 ? '🚫 Desactivar' : '✅ Activar'; ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">No hay cargos registrados.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>