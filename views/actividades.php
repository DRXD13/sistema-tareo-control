<?php
require_once "models/Actividad.php";
$actividadModel = new Actividad();
$listaActividades = $actividadModel->listarActividades();

$actividadEditar = null;
if (isset($_GET['editar'])) {
    $actividadEditar = $actividadModel->obtenerActividad($_GET['editar']);
}
?>

<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary m-0">🛠️ Catálogo de Actividades</h3>
        <?php if($actividadEditar): ?>
            <a href="index.php?vista=actividades" class="btn btn-secondary">Volver a Nueva</a>
        <?php endif; ?>
    </div>

    <?php if(isset($_GET['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Aviso!</strong> 
            <?php 
                if($_GET['mensaje'] == 'exito') echo "La actividad se guardó correctamente.";
                if($_GET['mensaje'] == 'exito_editar') echo "La actividad se actualizó correctamente.";
                if($_GET['mensaje'] == 'exito_estado') echo "El estado se modificó correctamente.";
                if($_GET['mensaje'] == 'error') echo "Ocurrió un error en la operación.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm <?php echo $actividadEditar ? 'border-primary border-top border-3' : ''; ?>">
                <div class="card-header bg-white fw-bold text-secondary">
                    <?php echo $actividadEditar ? '✏️ Editar Actividad' : '➕ Nueva Actividad'; ?>
                </div>
                <div class="card-body">
                    <form action="index.php?accion=<?php echo $actividadEditar ? 'actualizar_actividad' : 'guardar_actividad'; ?>" method="POST">
                        <?php if($actividadEditar): ?>
                            <input type="hidden" name="id_actividad" value="<?php echo $actividadEditar['id_actividad']; ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label">Nombre de la Actividad</label>
                            <input type="text" class="form-control" name="nombre_actividad" 
                                   value="<?php echo $actividadEditar ? $actividadEditar['nombre_actividad'] : ''; ?>" placeholder="Ej. Cosecha, Mantenimiento..." required>
                        </div>
                        
                        <button type="submit" class="btn <?php echo $actividadEditar ? 'btn-success' : 'btn-primary'; ?> w-100 fw-bold">
                            <?php echo $actividadEditar ? 'Actualizar Cambios' : 'Guardar Actividad'; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold text-secondary">
                    Actividades Registradas
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
                            <?php if (count($listaActividades) > 0): ?>
                                <?php foreach ($listaActividades as $act): ?>
                                    <tr>
                                        <td><?php echo $act['id_actividad']; ?></td>
                                        <td class="fw-bold text-start"><?php echo $act['nombre_actividad']; ?></td>
                                        <td>
                                            <?php if ($act['estado'] == 1): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="index.php?vista=actividades&editar=<?php echo $act['id_actividad']; ?>" class="btn btn-sm btn-outline-primary">✏️ Editar</a>
                                            <a href="index.php?accion=cambiar_estado_actividad&id=<?php echo $act['id_actividad']; ?>&estado=<?php echo $act['estado']; ?>" 
                                               class="btn btn-sm <?php echo $act['estado'] == 1 ? 'btn-outline-danger' : 'btn-outline-success'; ?>">
                                                <?php echo $act['estado'] == 1 ? '🚫 Desactivar' : '✅ Activar'; ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">No hay actividades registradas.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>