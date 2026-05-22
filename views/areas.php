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
        <div>
            <h3 class="text-primary m-0 fw-bold">🏢 Gestión de Áreas</h3>
            <p class="text-muted small m-0">Administra los departamentos de la institución</p>
        </div>
        
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-secondary fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalBuscarArea">
                🔍 Buscar
            </button>
            
            <?php if(!$areaEditar): ?>
                <button type="button" class="btn btn-primary fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalArea">
                    ➕ Nueva Área
                </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <span class="fw-bold text-secondary">📋 Áreas Registradas en el Sistema</span>
            <span id="badgeFiltroArea" class="badge bg-warning text-dark d-none">Filtro Activo <span style="cursor:pointer;" onclick="limpiarBusquedaArea()">✖</span></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-0 text-center align-middle">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>ID</th>
                            <th class="text-start">Nombre del Área</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTablaAreas">
                        <?php if (count($listaAreas) > 0): ?>
                            <?php foreach ($listaAreas as $area): ?>
                                <tr>
                                    <td class="text-muted fw-bold"><?php echo $area['id_area']; ?></td>
                                    <td class="fw-bold text-start text-dark"><?php echo $area['nombre_area']; ?></td>
                                    <td>
                                        <?php if ($area['estado'] == 1): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 rounded-pill">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 rounded-pill">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="index.php?vista=areas&editar=<?php echo $area['id_area']; ?>" class="btn btn-sm btn-outline-primary shadow-sm">✏️ Editar</a>
                                        
                                        <a href="index.php?accion=cambiar_estado_area&id=<?php echo $area['id_area']; ?>&estado=<?php echo $area['estado']; ?>" 
                                           class="btn btn-sm <?php echo $area['estado'] == 1 ? 'btn-outline-danger' : 'btn-outline-success'; ?> shadow-sm">
                                            <?php echo $area['estado'] == 1 ? '🚫 Desactivar' : '✅ Activar'; ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center py-5 text-muted">No hay áreas registradas en el sistema.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBuscarArea" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title fw-bold">🔍 Buscar Área</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <p class="small text-muted mb-4">Ingresa el nombre del área que deseas encontrar.</p>
                
                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Buscar por Nombre del Área</label>
                    <input type="text" class="form-control" id="filtro_nombre_area">
                </div>
            </div>
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-outline-secondary fw-bold" onclick="limpiarBusquedaArea()">Limpiar</button>
                <button type="button" class="btn btn-primary fw-bold px-4" onclick="ejecutarBusquedaArea()">Aplicar Búsqueda</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalArea" tabindex="-1" aria-labelledby="modalAreaLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            
            <div class="modal-header <?php echo $areaEditar ? 'bg-warning text-dark' : 'bg-primary text-white'; ?>">
                <h5 class="modal-title fw-bold" id="modalAreaLabel">
                    <?php echo $areaEditar ? '✏️ Editar Área' : '➕ Registrar Nueva Área'; ?>
                </h5>
                <button type="button" class="btn-close <?php echo $areaEditar ? '' : 'btn-close-white'; ?>" 
                        <?php echo $areaEditar ? 'onclick="window.location.href=\'index.php?vista=areas\'"' : 'data-bs-dismiss="modal"'; ?> aria-label="Close"></button>
            </div>

            <form action="index.php?accion=<?php echo $areaEditar ? 'actualizar_area' : 'guardar_area'; ?>" method="POST">
                <div class="modal-body p-4">
                    
                    <?php if($areaEditar): ?>
                        <input type="hidden" name="id_area" value="<?php echo $areaEditar['id_area']; ?>">
                        <div class="alert alert-warning small py-2">
                            Estás modificando los datos del área <b><?php echo $areaEditar['id_area']; ?></b>.
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="nombre_area" class="form-label fw-bold text-secondary small">Nombre del Área <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-primary bg-light" id="nombre_area" name="nombre_area" 
                               value="<?php echo $areaEditar ? $areaEditar['nombre_area'] : ''; ?>" required placeholder="Ejemplo: Logística">
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <?php if($areaEditar): ?>
                        <a href="index.php?vista=areas" class="btn btn-secondary fw-bold">Cancelar</a>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn <?php echo $areaEditar ? 'btn-warning text-dark' : 'btn-primary'; ?> fw-bold px-4">
                        <?php echo $areaEditar ? 'Actualizar Cambios' : 'Guardar Área'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ==========================================
    // LÓGICA DEL BUSCADOR INTELIGENTE (ÁREAS)
    // ==========================================
    function ejecutarBusquedaArea() {
        let inputNombre = document.getElementById('filtro_nombre_area').value.toLowerCase();
        
        // Validar que se ingrese el dato
        if (inputNombre === '') {
             Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Por favor ingresa un nombre para buscar.',
                confirmButtonColor: '#0d6efd'
            });
            return;
        }

        let filas = document.getElementById('cuerpoTablaAreas').getElementsByTagName('tr');
        let encontrados = 0;

        for (let i = 0; i < filas.length; i++) {
            if(filas[i].cells.length < 2) continue; // Ignorar fila de tabla vacía

            let textoNombre = filas[i].cells[1].textContent.toLowerCase();
            
            let coincideNombre = textoNombre.includes(inputNombre);

            if (coincideNombre) {
                filas[i].style.display = "";
                encontrados++;
            } else {
                filas[i].style.display = "none";
            }
        }

        // Mostrar u ocultar el badge de aviso
        if(inputNombre !== '') {
            document.getElementById('badgeFiltroArea').classList.remove('d-none');
        }

        // Cerrar el modal antes de mostrar la alerta
        var modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalBuscarArea'));
        if (modalInstance) modalInstance.hide();

        // Lanzar alerta de resultados
        if (encontrados > 0) {
            Swal.fire({
                icon: 'success',
                title: 'Búsqueda Exitosa',
                text: `Se encontraron ${encontrados} área(s) con el nombre ingresado.`,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Sin Resultados',
                text: 'No se encontró el área solicitada.',
                confirmButtonColor: '#dc3545'
            });
        }
    }

    function limpiarBusquedaArea() {
        document.getElementById('filtro_nombre_area').value = '';
        
        let filas = document.getElementById('cuerpoTablaAreas').getElementsByTagName('tr');
        for (let i = 0; i < filas.length; i++) {
            filas[i].style.display = "";
        }
        
        document.getElementById('badgeFiltroArea').classList.add('d-none');
        
        // Intentar cerrar el modal si está abierto
        var modalElement = document.getElementById('modalBuscarArea');
        var modalInstance = bootstrap.Modal.getInstance(modalElement);
        if(modalInstance) modalInstance.hide();
    }

    document.addEventListener("DOMContentLoaded", function() {
        
        // Mostrar Modal de Edición automáticamente si existe
        <?php if($areaEditar): ?>
            var myModal = new bootstrap.Modal(document.getElementById('modalArea'));
            myModal.show();
        <?php endif; ?>

        // LÓGICA DE ALERTAS ANIMADAS (SWEETALERT2)
        const urlParams = new URLSearchParams(window.location.search);
        const alerta = urlParams.get('alerta');

        if (alerta) {
            let configuracion = {};

            if (alerta === 'guardado' || alerta === 'actualizado') {
                configuracion = { icon: 'success', title: '¡Operación Exitosa!', text: 'El área fue guardada correctamente.' };
            } else if (alerta === 'error') {
                configuracion = { icon: 'error', title: 'Error del Sistema', text: 'Ocurrió un problema al procesar la solicitud.' };
            }

            if (Object.keys(configuracion).length > 0) {
                Swal.fire({
                    ...configuracion,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });

                window.history.replaceState(null, null, window.location.pathname + "?vista=areas");
            }
        }
    });
</script>