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
        <div>
            <h3 class="text-primary m-0 fw-bold">🛠️ Catálogo de Actividades</h3>
            <p class="text-muted small m-0">Administra las tareas operativas para el registro de tareo</p>
        </div>
        
        <?php if(!$actividadEditar): ?>
            <button type="button" class="btn btn-primary fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalActividad">
                ➕ Nueva Actividad
            </button>
        <?php endif; ?>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold text-secondary py-3">
            📋 Actividades Registradas
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-0 text-center align-middle">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>ID</th>
                            <th class="text-start">Nombre de la Actividad</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($listaActividades) > 0): ?>
                            <?php foreach ($listaActividades as $act): ?>
                                <tr>
                                    <td class="text-muted fw-bold"><?php echo $act['id_actividad']; ?></td>
                                    <td class="fw-bold text-start text-dark"><?php echo $act['nombre_actividad']; ?></td>
                                    <td>
                                        <?php if ($act['estado'] == 1): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 rounded-pill">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 rounded-pill">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="index.php?vista=actividades&editar=<?php echo $act['id_actividad']; ?>" class="btn btn-sm btn-outline-primary shadow-sm">✏️ Editar</a>
                                        
                                        <a href="index.php?accion=cambiar_estado_actividad&id=<?php echo $act['id_actividad']; ?>&estado=<?php echo $act['estado']; ?>" 
                                           class="btn btn-sm <?php echo $act['estado'] == 1 ? 'btn-outline-danger' : 'btn-outline-success'; ?> shadow-sm">
                                            <?php echo $act['estado'] == 1 ? '🚫 Desactivar' : '✅ Activar'; ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center py-5 text-muted">No hay actividades registradas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalActividad" tabindex="-1" aria-labelledby="modalActividadLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            
            <div class="modal-header <?php echo $actividadEditar ? 'bg-warning text-dark' : 'bg-primary text-white'; ?>">
                <h5 class="modal-title fw-bold" id="modalActividadLabel">
                    <?php echo $actividadEditar ? '✏️ Editar Actividad' : '➕ Registrar Nueva Actividad'; ?>
                </h5>
                <button type="button" class="btn-close <?php echo $actividadEditar ? '' : 'btn-close-white'; ?>" 
                        <?php echo $actividadEditar ? 'onclick="window.location.href=\'index.php?vista=actividades\'"' : 'data-bs-dismiss="modal"'; ?> aria-label="Close"></button>
            </div>

            <form action="index.php?accion=<?php echo $actividadEditar ? 'actualizar_actividad' : 'guardar_actividad'; ?>" method="POST">
                <div class="modal-body p-4">
                    
                    <?php if($actividadEditar): ?>
                        <input type="hidden" name="id_actividad" value="<?php echo $actividadEditar['id_actividad']; ?>">
                        <div class="alert alert-warning small py-2">
                            Estás modificando los datos de la actividad <b><?php echo $actividadEditar['id_actividad']; ?></b>.
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary small">Nombre de la Actividad <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-primary bg-light" name="nombre_actividad" 
                               value="<?php echo $actividadEditar ? $actividadEditar['nombre_actividad'] : ''; ?>" required placeholder="Ejemplo: Cosecha, Mantenimiento...">
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <?php if($actividadEditar): ?>
                        <a href="index.php?vista=actividades" class="btn btn-secondary fw-bold">Cancelar</a>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn <?php echo $actividadEditar ? 'btn-warning text-dark' : 'btn-primary'; ?> fw-bold px-4">
                        <?php echo $actividadEditar ? 'Actualizar Cambios' : 'Guardar Actividad'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if($actividadEditar): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('modalActividad'));
        myModal.show();
    });
</script>
<?php endif; ?>