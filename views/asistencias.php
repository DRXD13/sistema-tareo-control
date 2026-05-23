<?php
require_once "models/Trabajador.php";
require_once "models/Asistencia.php";

$fecha_seleccionada = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

$trabajadorModel = new Trabajador();
$listaTrabajadores = $trabajadorModel->listarTrabajadores();

$asistenciaModel = new Asistencia();
$listaAsistencias = $asistenciaModel->listarAsistenciasHoy($fecha_seleccionada);

$datosAsistencia = [];
foreach ($listaAsistencias as $a) {
    $datosAsistencia[$a['id_trabajador']] = $a;
}
?>

<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-primary m-0 fw-bold">⏱️ Control de Asistencias y Permisos</h3>
            <p class="text-muted small m-0">Registra el ingreso, salida o justificación del personal activo.</p>
        </div>
        
        <button type="button" class="btn btn-secondary fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalBuscarAsistencia">
            🔍 Buscar Personal
        </button>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-white d-flex align-items-center justify-content-between p-3">
            <div class="text-secondary fw-bold">
                <span class="fs-5">📅 Panel de Registro Diario</span>
            </div>
            <form action="index.php" method="GET" class="d-flex align-items-center m-0">
                <input type="hidden" name="vista" value="asistencias">
                <label class="fw-bold me-3 text-secondary small">Seleccionar Día:</label>
                <input type="date" name="fecha" class="form-control form-control-sm w-auto me-3 border-primary shadow-sm" value="<?php echo $fecha_seleccionada; ?>" required>
                <button type="submit" class="btn btn-sm btn-primary fw-bold shadow-sm px-4">Consultar Día</button>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold text-secondary py-3 d-flex justify-content-between align-items-center">
            <span>👥 Personal Activo - <?php echo date('d/m/Y', strtotime($fecha_seleccionada)); ?></span>
            
            <div class="d-flex gap-2 align-items-center">
                <span id="badgeFiltroAsistencia" class="badge bg-warning text-dark d-none">Filtro Activo <span style="cursor:pointer;" onclick="limpiarBusquedaAsistencia()">✖</span></span>
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">Regla de Negocio RN04 Activa</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover m-0 align-middle text-center" style="font-size: 0.95rem;">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th class="text-start ps-4">Trabajador</th>
                            <th>Área / Cargo</th>
                            <th>Estado Actual</th>
                            <th>Acción Requerida</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoTablaAsistencias">
                        <?php 
                        $hayActivos = false;
                        foreach ($listaTrabajadores as $t): 
                            if ($t['estado'] == 1):
                                $hayActivos = true;
                                $id = $t['id_trabajador'];
                                $asistenciaHoy = isset($datosAsistencia[$id]) ? $datosAsistencia[$id] : null;
                        ?>
                            <tr>
                                <td class="text-start ps-4">
                                    <span class="fw-bold text-dark"><?php echo $t['nombres'] . ' ' . $t['apellidos']; ?></span><br>
                                    <small class="text-muted">Doc: <?php echo $t['numero_documento']; ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary"><?php echo $t['nombre_area']; ?></span><br>
                                    <small class="text-muted fw-bold"><?php echo $t['nombre_cargo']; ?></small>
                                </td>
                                
                                <td class="bg-light">
                                    <?php if (!$asistenciaHoy): ?>
                                        <span class="badge bg-secondary px-3 py-2 rounded-pill">Sin marcar</span>
                                    <?php else: ?>
                                        <div class="fw-bold <?php echo ($asistenciaHoy['estado'] == 'Tardanza' || $asistenciaHoy['estado'] == 'Falta Justificada') ? 'text-warning' : 'text-success'; ?>">
                                            <?php echo $asistenciaHoy['estado']; ?>
                                        </div>
                                        <?php if ($asistenciaHoy['hora_ingreso'] != '00:00:00'): ?>
                                            <div class="text-primary fw-bold small mt-1">
                                                <span class="text-muted fw-normal">Ingreso:</span> <?php echo date('h:i A', strtotime($asistenciaHoy['hora_ingreso'])); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($asistenciaHoy['hora_salida']): ?>
                                            <div class="text-danger fw-bold small mt-1">
                                                <span class="text-muted fw-normal">Salida:</span> <?php echo date('h:i A', strtotime($asistenciaHoy['hora_salida'])); ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if (!$asistenciaHoy): ?>
                                        <form action="index.php?accion=guardar_ingreso" method="POST" class="d-flex justify-content-center align-items-center gap-2">
                                            <input type="hidden" name="id_trabajador" value="<?php echo $id; ?>">
                                            <input type="hidden" name="fecha" value="<?php echo $fecha_seleccionada; ?>">
                                            
                                            <input type="time" class="form-control form-control-sm shadow-sm" name="hora_ingreso" style="width: 120px;" title="Hora de Ingreso">
                                            
                                            <select class="form-select form-select-sm border-primary fw-bold shadow-sm" name="estado" style="width: 150px;">
                                                <option value="Puntual">Puntual</option>
                                                <option value="Tardanza">Tardanza</option>
                                                <option value="Falta Justificada">Falta Justificada</option>
                                                <option value="Descanso Médico">Descanso Médico</option>
                                                <option value="Permiso Especial">Permiso Especial</option>
                                            </select>
                                            
                                            <input type="text" class="form-control form-control-sm shadow-sm" name="observaciones" placeholder="Obs..." style="width: 120px;">
                                            <button type="submit" class="btn btn-sm btn-primary fw-bold shadow-sm px-3">Registrar</button>
                                        </form>

                                    <?php elseif ($asistenciaHoy && !$asistenciaHoy['hora_salida'] && in_array($asistenciaHoy['estado'], ['Puntual', 'Tardanza'])): ?>
                                        <form action="index.php?accion=guardar_salida" method="POST" class="d-flex justify-content-center align-items-center gap-2">
                                            <input type="hidden" name="id_asistencia" value="<?php echo $asistenciaHoy['id_asistencia']; ?>">
                                            
                                            <input type="time" class="form-control form-control-sm border-danger shadow-sm" name="hora_salida" style="width: 130px;" required title="Hora de Salida">
                                            <button type="submit" class="btn btn-sm btn-danger fw-bold shadow-sm px-4">Marcar Salida</button>
                                        </form>

                                    <?php else: ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-4 py-2 rounded-pill">
                                            Jornada/Permiso Completado ✅
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php 
                            endif;
                        endforeach; 
                        
                        if (!$hayActivos):
                        ?>
                            <tr><td colspan="4" class="text-center py-5 text-muted">No hay personal activo para registrar asistencia.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBuscarAsistencia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title fw-bold">🔍 Buscar Trabajador</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <p class="small text-muted mb-4">Ingresa el nombre o apellido del trabajador que deseas encontrar en la lista de hoy.</p>
                
                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Buscar por Nombres o Apellidos</label>
                    <input type="text" class="form-control" id="filtro_nombre_trabajador">
                </div>
            </div>
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-outline-secondary fw-bold" onclick="limpiarBusquedaAsistencia()">Limpiar</button>
                <button type="button" class="btn btn-primary fw-bold px-4" onclick="ejecutarBusquedaAsistencia()">Aplicar Búsqueda</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ==========================================
    // LÓGICA DEL BUSCADOR INTELIGENTE (ASISTENCIAS)
    // ==========================================
    function ejecutarBusquedaAsistencia() {
        let inputNombre = document.getElementById('filtro_nombre_trabajador').value.toLowerCase();
        
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

        let filas = document.getElementById('cuerpoTablaAsistencias').getElementsByTagName('tr');
        let encontrados = 0;

        for (let i = 0; i < filas.length; i++) {
            if(filas[i].cells.length < 2) continue; // Ignorar fila de tabla vacía

            // En la tabla de Asistencias, el nombre del trabajador está dentro de un span en la primera celda
            let textoNombre = filas[i].cells[0].querySelector('span.fw-bold').textContent.toLowerCase();
            
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
            document.getElementById('badgeFiltroAsistencia').classList.remove('d-none');
        }

        // Cerrar el modal antes de mostrar la alerta
        var modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalBuscarAsistencia'));
        if (modalInstance) modalInstance.hide();

        // Lanzar alerta de resultados
        if (encontrados > 0) {
            Swal.fire({
                icon: 'success',
                title: 'Búsqueda Exitosa',
                text: `Se encontraron ${encontrados} trabajador(es) con el nombre ingresado.`,
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
                text: 'No se encontró al trabajador solicitado.',
                confirmButtonColor: '#dc3545'
            });
        }
    }

    function limpiarBusquedaAsistencia() {
        document.getElementById('filtro_nombre_trabajador').value = '';
        
        let filas = document.getElementById('cuerpoTablaAsistencias').getElementsByTagName('tr');
        for (let i = 0; i < filas.length; i++) {
            filas[i].style.display = "";
        }
        
        document.getElementById('badgeFiltroAsistencia').classList.add('d-none');
        
        // Intentar cerrar el modal si está abierto
        var modalElement = document.getElementById('modalBuscarAsistencia');
        var modalInstance = bootstrap.Modal.getInstance(modalElement);
        if(modalInstance) modalInstance.hide();
    }
</script>