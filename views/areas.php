<?php
require_once "models/Area.php";
$areaModel = new Area();
$listaAreas = $areaModel->listarAreas();

// Verificamos si el usuario hizo clic en "Editar" para cargar los datos en el formulario
$areaEditar = null;
if (isset($_GET['editar'])) {
    $areaEditar = $areaModel->obtenerArea($_GET['editar']);
}
?>

<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary m-0">🏢 Gestión de Áreas</h3>
        <?php if($areaEditar): ?>
            <a href="index.php?vista=areas" class="btn btn-secondary">Volver a Nuevo</a>
        <?php endif; ?>
    </div>

    <?php if(isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Aviso!</strong> 
            <?php 
                if($_GET['mensaje'] == 'exito') echo "El área se guardó correctamente.";
                if($_GET['mensaje'] == 'exito_editar') echo "El área se actualizó correctamente.";
                if($_GET['mensaje'] == 'exito_estado') echo "El estado se modificó correctamente.";
                if($_GET['mensaje'] == 'error') echo "Ocurrió un error en la operación.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm <?php echo $areaEditar ? 'border-primary border-top border-3' : ''; ?>">
                <div class="card-header bg-white fw-bold text-secondary">
                    <?php echo $areaEditar ? '✏️ Editar Área' : '➕ Nueva Área'; ?>
                </div>
                <div class="card-body">
                    <form action="index.php?accion=<?php echo $areaEditar ? 'actualizar_area' : 'guardar_area'; ?>" method="POST">
                        
                        <?php if($areaEditar): ?>
                            <input type="hidden" name="id_area" value="<?php echo $areaEditar['id_area']; ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="nombre_area" class="form-label">Nombre del Área</label>
                            <input type="text" class="form-control" id="nombre_area" name="nombre_area" 
                                   value="<?php echo $areaEditar ? $areaEditar['nombre_area'] : ''; ?>" required>
                        </div>
                        
                        <button type="submit" class="btn <?php echo $areaEditar ? 'btn-success' : 'btn-primary'; ?> w-100 fw-bold">
                            <?php echo $areaEditar ? 'Actualizar Cambios' : 'Guardar Área'; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold text-secondary">
                    Áreas Registradas
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
                            <?php if (count($listaAreas) > 0): ?>
                                <?php foreach ($listaAreas as $area): ?>
                                    <tr>
                                        <td><?php echo $area['id_area']; ?></td>
                                        <td class="fw-bold text-start"><?php echo $area['nombre_area']; ?></td>
                                        <td>
                                            <?php if ($area['estado'] == 1): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="index.php?vista=areas&editar=<?php echo $area['id_area']; ?>" class="btn btn-sm btn-outline-primary">✏️ Editar</a>
                                            
                                            <a href="index.php?accion=cambiar_estado_area&id=<?php echo $area['id_area']; ?>&estado=<?php echo $area['estado']; ?>" 
                                               class="btn btn-sm <?php echo $area['estado'] == 1 ? 'btn-outline-danger' : 'btn-outline-success'; ?>">
                                                <?php echo $area['estado'] == 1 ? '🚫 Desactivar' : '✅ Activar'; ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">No hay áreas.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>