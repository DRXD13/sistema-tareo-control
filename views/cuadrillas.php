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
        <h3 class="text-primary m-0">🚜 Gestión de Cuadrillas</h3>
        <?php if($cuadrillaEditar): ?>
            <a href="index.php?vista=cuadrillas" class="btn btn-secondary">Volver a Nueva</a>
        <?php endif; ?>
    </div>

    <?php if(isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Aviso!</strong> 
            <?php 
                if($_GET['mensaje'] == 'exito') echo "La cuadrilla se guardó correctamente.";
                if($_GET['mensaje'] == 'exito_editar') echo "La cuadrilla se actualizó correctamente.";
                if($_GET['mensaje'] == 'exito_estado') echo "El estado se modificó correctamente.";
                if($_GET['mensaje'] == 'error') echo "Ocurrió un error en la operación.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm <?php echo $cuadrillaEditar ? 'border-primary border-top border-3' : ''; ?>">
                <div class="card-header bg-white fw-bold text-secondary">
                    <?php echo $cuadrillaEditar ? '✏️ Editar Cuadrilla' : '➕ Nueva Cuadrilla'; ?>
                </div>
                <div class="card-body">
                    <form action="index.php?accion=<?php echo $cuadrillaEditar ? 'actualizar_cuadrilla' : 'guardar_cuadrilla'; ?>" method="POST">
                        <?php if($cuadrillaEditar): ?>
                            <input type="hidden" name="id_cuadrilla" value="<?php echo $cuadrillaEditar['id_cuadrilla']; ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="nombre_cuadrilla" class="form-label">Nombre de la Cuadrilla</label>
                            <input type="text" class="form-control" id="nombre_cuadrilla" name="nombre_cuadrilla" 
                                   value="<?php echo $cuadrillaEditar ? $cuadrillaEditar['nombre_cuadrilla'] : ''; ?>" placeholder="Ej. Cuadrilla A - Cosecha" required>
                        </div>
                        
                        <button type="submit" class="btn <?php echo $cuadrillaEditar ? 'btn-success' : 'btn-primary'; ?> w-100 fw-bold">
                            <?php echo $cuadrillaEditar ? 'Actualizar Cambios' : 'Guardar Cuadrilla'; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold text-secondary">
                    Cuadrillas Registradas
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
                            <?php if (count($listaCuadrillas) > 0): ?>
                                <?php foreach ($listaCuadrillas as $cuadrilla): ?>
                                    <tr>
                                        <td><?php echo $cuadrilla['id_cuadrilla']; ?></td>
                                        <td class="fw-bold text-start"><?php echo $cuadrilla['nombre_cuadrilla']; ?></td>
                                        <td>
                                            <?php if ($cuadrilla['estado'] == 1): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="index.php?vista=cuadrillas&editar=<?php echo $cuadrilla['id_cuadrilla']; ?>" class="btn btn-sm btn-outline-primary">✏️ Editar</a>
                                            <a href="index.php?accion=cambiar_estado_cuadrilla&id=<?php echo $cuadrilla['id_cuadrilla']; ?>&estado=<?php echo $cuadrilla['estado']; ?>" 
                                               class="btn btn-sm <?php echo $cuadrilla['estado'] == 1 ? 'btn-outline-danger' : 'btn-outline-success'; ?>">
                                                <?php echo $cuadrilla['estado'] == 1 ? '🚫 Desactivar' : '✅ Activar'; ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">No hay cuadrillas registradas.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>