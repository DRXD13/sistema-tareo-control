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
        
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-secondary fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalBuscarTrabajador">
                🔍 Buscar
            </button>
            
            <?php if(!$trabajadorEditar): ?>
                <button type="button" class="btn btn-primary fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalTrabajador">
                    ➕ Nuevo Trabajador
                </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <span class="fw-bold text-secondary">📋 Personal Registrado</span>
            <span id="badgeFiltro" class="badge bg-warning text-dark d-none">Filtro Activo <span style="cursor:pointer;" onclick="limpiarBusqueda()">✖</span></span>
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
                    <tbody id="cuerpoTablaTrabajadores">
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

<div class="modal fade" id="modalBuscarTrabajador" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title fw-bold">🔍 Buscar Personal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <p class="small text-muted mb-4">Ingresa los datos del trabajador que deseas encontrar. Puedes usar uno o varios campos.</p>
                
                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Buscar por Documento (DNI/CE)</label>
                    <input type="text" class="form-control" id="filtro_dni">
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Buscar por Nombres</label>
                    <input type="text" class="form-control" id="filtro_nombres">
                </div>
            </div>
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-outline-secondary fw-bold" onclick="limpiarBusqueda()">Limpiar</button>
                <button type="button" class="btn btn-primary fw-bold px-4" onclick="ejecutarBusqueda()">Aplicar Búsqueda</button>
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
                            <select class="form-select" name="tipo_documento" id="tipo_documento" required>
                                <option value="DNI" <?php echo ($trabajadorEditar && $trabajadorEditar['tipo_documento'] == 'DNI') ? 'selected' : ''; ?>>DNI</option>
                                <option value="CE" <?php echo ($trabajadorEditar && $trabajadorEditar['tipo_documento'] == 'CE') ? 'selected' : ''; ?>>Carnet Extranjería</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary small fw-bold">Número Doc. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="numero_documento" id="numero_documento" value="<?php echo $trabajadorEditar ? $trabajadorEditar['numero_documento'] : ''; ?>" required>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ==========================================
    // LÓGICA DEL BUSCADOR INTELIGENTE (MODAL)
    // ==========================================
    function ejecutarBusqueda() {
        let inputDni = document.getElementById('filtro_dni').value.toLowerCase();
        let inputNombres = document.getElementById('filtro_nombres').value.toLowerCase();
        
        // Validar que al menos se ingrese un dato
        if (inputDni === '' && inputNombres === '') {
             Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Por favor ingresa al menos un dato para buscar.',
                confirmButtonColor: '#0d6efd'
            });
            return; // No ejecutar búsqueda
        }

        let filas = document.getElementById('cuerpoTablaTrabajadores').getElementsByTagName('tr');
        let encontrados = 0;

        for (let i = 0; i < filas.length; i++) {
            if(filas[i].cells.length < 2) continue;

            let textoDni = filas[i].cells[0].textContent.toLowerCase();
            let textoNombreCompleto = filas[i].cells[1].textContent.toLowerCase();
            
            let coincideDni = inputDni === '' || textoDni.includes(inputDni);
            let coincideNombre = inputNombres === '' || textoNombreCompleto.includes(inputNombres);

            if (coincideDni && coincideNombre) {
                filas[i].style.display = "";
                encontrados++;
            } else {
                filas[i].style.display = "none";
            }
        }

        // Mostrar u ocultar el badge de aviso
        if(inputDni !== '' || inputNombres !== '') {
            document.getElementById('badgeFiltro').classList.remove('d-none');
        }

        // Cerrar el modal antes de mostrar la alerta
        var modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalBuscarTrabajador'));
        modalInstance.hide();

        // Lanzar alerta de resultados (Éxito o Error)
        if (encontrados > 0) {
            Swal.fire({
                icon: 'success',
                title: 'Búsqueda Exitosa',
                text: `Se encontraron ${encontrados} trabajador(es) con los datos ingresados.`,
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
                text: 'No se encontró a la persona solicitada.',
                confirmButtonColor: '#dc3545'
            });
            // Opcional: Podrías llamar a limpiarBusqueda() aquí si quieres que la tabla vuelva a mostrarse completa tras un fallo.
        }
    }

    function limpiarBusqueda() {
        document.getElementById('filtro_dni').value = '';
        document.getElementById('filtro_nombres').value = '';
        
        let filas = document.getElementById('cuerpoTablaTrabajadores').getElementsByTagName('tr');
        for (let i = 0; i < filas.length; i++) {
            filas[i].style.display = "";
        }
        
        document.getElementById('badgeFiltro').classList.add('d-none');
        
        // Intentar cerrar el modal si está abierto
        var modalElement = document.getElementById('modalBuscarTrabajador');
        var modalInstance = bootstrap.Modal.getInstance(modalElement);
        if(modalInstance) modalInstance.hide();
    }

    document.addEventListener("DOMContentLoaded", function() {
        
        // Mostrar Modal de Edición automáticamente si existe
        <?php if($trabajadorEditar): ?>
            var myModal = new bootstrap.Modal(document.getElementById('modalTrabajador'));
            myModal.show();
        <?php endif; ?>

        // LÓGICA DE BLOQUEO DE TECLADO PARA EL DNI/CE
        const selectDoc = document.getElementById('tipo_documento');
        const inputNum = document.getElementById('numero_documento');

        if (selectDoc && inputNum) {
            function actualizarReglas() {
                if (selectDoc.value === 'DNI') {
                    inputNum.setAttribute('maxlength', '8');
                    inputNum.setAttribute('pattern', '[0-9]{8}');
                    inputNum.title = "El DNI debe tener exactamente 8 números";
                    inputNum.value = inputNum.value.replace(/[^0-9]/g, '').substring(0, 8);
                } else {
                    inputNum.setAttribute('maxlength', '12');
                    inputNum.removeAttribute('pattern');
                    inputNum.title = "El CE debe tener entre 9 y 12 caracteres alfanuméricos";
                }
            }

            actualizarReglas();
            selectDoc.addEventListener('change', actualizarReglas);

            inputNum.addEventListener('input', function() {
                if (selectDoc.value === 'DNI') {
                    this.value = this.value.replace(/[^0-9]/g, '');
                } else {
                    this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
                }
            });
        }

        // LÓGICA DE ALERTAS ANIMADAS (SWEETALERT2)
        const urlParams = new URLSearchParams(window.location.search);
        const alerta = urlParams.get('alerta');

        if (alerta) {
            let configuracion = {};

            if (alerta === 'guardado' || alerta === 'actualizado') {
                configuracion = { icon: 'success', title: '¡Operación Exitosa!', text: 'El trabajador fue guardado correctamente.' };
            } else if (alerta === 'error_formato_dni') {
                configuracion = { icon: 'error', title: 'DNI Inválido', text: 'El DNI debe contener exactamente 8 dígitos numéricos.' };
            } else if (alerta === 'error_formato_ce') {
                configuracion = { icon: 'error', title: 'CE Inválido', text: 'El Carnet de Extranjería debe tener entre 9 y 12 caracteres.' };
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

                window.history.replaceState(null, null, window.location.pathname + "?vista=trabajadores");
            }
        }
    });
</script>