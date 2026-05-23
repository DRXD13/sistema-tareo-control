<?php
require_once "models/Jornal.php";

$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-d');

$jornalModel = new Jornal();
$listaJornales = $jornalModel->calcularJornales($fecha_inicio, $fecha_fin);
$total_planilla = 0;
?>

<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-primary m-0 fw-bold">💰 Control de Jornales y Planillas</h3>
            <p class="text-muted small m-0">Cálculo automatizado de pagos según las horas reportadas en el tareo.</p>
        </div>
        
        <button type="button" class="btn btn-secondary fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalBuscarJornal">
            🔍 Buscar Trabajador
        </button>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-white d-flex align-items-center justify-content-between p-3">
            <div class="text-secondary fw-bold">
                <span class="fs-5">📅 Rango de Evaluación</span>
            </div>
            <form action="index.php" method="GET" class="d-flex align-items-center m-0">
                <input type="hidden" name="vista" value="jornales">
                
                <label class="fw-bold me-2 text-secondary small">Desde:</label>
                <input type="date" name="fecha_inicio" class="form-control form-control-sm w-auto me-3 border-primary shadow-sm" value="<?php echo $fecha_inicio; ?>" required>
                
                <label class="fw-bold me-2 text-secondary small">Hasta:</label>
                <input type="date" name="fecha_fin" class="form-control form-control-sm w-auto me-4 border-primary shadow-sm" value="<?php echo $fecha_fin; ?>" required>
                
                <button type="submit" class="btn btn-sm btn-primary fw-bold shadow-sm px-4">🔄 Calcular Pagos</button>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold text-secondary py-3 d-flex justify-content-between align-items-center">
            <span>📊 Resultados del Cálculo</span>
            <div class="d-flex gap-2 align-items-center">
                <span id="badgeFiltroJornal" class="badge bg-warning text-dark d-none">Filtro Activo <span style="cursor:pointer;" onclick="limpiarBusquedaJornal()">✖</span></span>
                <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 shadow-sm">Período: <?php echo date('d/m/Y', strtotime($fecha_inicio)); ?> al <?php echo date('d/m/Y', strtotime($fecha_fin)); ?></span>
            </div>
        </div>
        
        <div class="card-body p-0">
            <form action="index.php?accion=guardar_planilla" method="POST">
                <input type="hidden" name="fecha_inicio" value="<?php echo $fecha_inicio; ?>">
                <input type="hidden" name="fecha_fin" value="<?php echo $fecha_fin; ?>">

                <div class="table-responsive">
                    <table class="table table-hover m-0 align-middle text-center" style="font-size: 0.95rem;">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th class="text-start ps-4">Trabajador</th>
                                <th>Jornal Base (8h)</th>
                                <th>Total Horas Aprobadas</th>
                                <th class="text-success fw-bold">Total a Pagar (S/)</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpoTablaJornales">
                            <?php if (count($listaJornales) > 0): ?>
                                <?php foreach ($listaJornales as $j): 
                                    $total_planilla += $j['total_pagar'];
                                ?>
                                    <tr class="fila-jornal">
                                        <td class="text-start ps-4">
                                            <input type="hidden" name="id_trabajador[]" value="<?php echo $j['id_trabajador']; ?>">
                                            <input type="hidden" name="jornal_diario[]" value="<?php echo $j['jornal_diario']; ?>">
                                            <input type="hidden" name="total_horas[]" value="<?php echo $j['total_horas']; ?>">
                                            <input type="hidden" name="total_pagar[]" value="<?php echo $j['total_pagar']; ?>">
                                            
                                            <span class="fw-bold text-dark nombre-trabajador"><?php echo $j['nombres'] . ' ' . $j['apellidos']; ?></span>
                                            <br><small class="text-muted fw-normal">Doc: <?php echo $j['numero_documento']; ?></small>
                                        </td>
                                        <td class="text-secondary fw-semibold">S/ <?php echo number_format($j['jornal_diario'], 2); ?></td>
                                        <td>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 rounded-pill fs-6">
                                                ⏱️ <?php echo $j['total_horas']; ?> hrs
                                            </span>
                                        </td>
                                        <td class="text-success fw-bold fs-5">
                                            S/ <?php echo number_format($j['total_pagar'], 2); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                
                                <tr id="filaTotalPlanilla" class="bg-light border-top border-2 border-primary">
                                    <td colspan="3" class="text-end fw-bold text-primary fs-5 pe-4">GRAN TOTAL PLANILLA:</td>
                                    <td class="text-primary fw-bold fs-4 bg-primary bg-opacity-10">S/ <?php echo number_format($total_planilla, 2); ?></td>
                                </tr>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-5 text-danger fw-bold">No hay tareos aprobados en este rango de fechas para calcular.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (count($listaJornales) > 0): ?>
                    <div class="p-4 bg-white border-top d-flex justify-content-end align-items-center">
                        <span class="text-muted small me-4">Al guardar, esta planilla pasará al historial contable.</span>
                        <button type="submit" class="btn btn-success fw-bold px-5 shadow-sm">💾 Cerrar y Guardar Planilla</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBuscarJornal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title fw-bold">🔍 Buscar Trabajador en Planilla</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <p class="small text-muted mb-4">Ingresa el nombre o apellido del trabajador para localizar su cálculo de pago en el rango actual.</p>
                
                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Buscar por Nombres o Apellidos</label>
                    <input type="text" class="form-control" id="filtro_nombre_jornal">
                </div>
            </div>
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-outline-secondary fw-bold" onclick="limpiarBusquedaJornal()">Limpiar</button>
                <button type="button" class="btn btn-primary fw-bold px-4" onclick="ejecutarBusquedaJornal()">Aplicar Búsqueda</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ==========================================
    // LÓGICA DEL BUSCADOR INTELIGENTE (JORNALES)
    // ==========================================
    function ejecutarBusquedaJornal() {
        let inputNombre = document.getElementById('filtro_nombre_jornal').value.toLowerCase();
        
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

        // Seleccionamos solo las filas de trabajadores, excluyendo mensajes vacíos o el gran total
        let filas = document.querySelectorAll('#cuerpoTablaJornales tr.fila-jornal');
        let totalPlanilla = document.getElementById('filaTotalPlanilla');
        let encontrados = 0;

        filas.forEach(function(fila) {
            let textoNombre = fila.querySelector('.nombre-trabajador').textContent.toLowerCase();
            
            if (textoNombre.includes(inputNombre)) {
                fila.style.display = "";
                encontrados++;
            } else {
                fila.style.display = "none";
            }
        });

        // Ocultamos la fila del "GRAN TOTAL" si estamos filtrando, para evitar confusiones numéricas
        if(totalPlanilla) {
            totalPlanilla.style.display = "none";
        }

        // Mostrar u ocultar el badge de aviso
        if(inputNombre !== '') {
            document.getElementById('badgeFiltroJornal').classList.remove('d-none');
        }

        // Cerrar el modal antes de mostrar la alerta
        var modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalBuscarJornal'));
        if (modalInstance) modalInstance.hide();

        // Lanzar alerta de resultados
        if (encontrados > 0) {
            Swal.fire({
                icon: 'success',
                title: 'Búsqueda Exitosa',
                text: `Se encontraron ${encontrados} trabajador(es) en la planilla actual.`,
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
                text: 'No se encontró al trabajador en este cálculo de planillas.',
                confirmButtonColor: '#dc3545'
            });
        }
    }

    function limpiarBusquedaJornal() {
        document.getElementById('filtro_nombre_jornal').value = '';
        
        let filas = document.querySelectorAll('#cuerpoTablaJornales tr.fila-jornal');
        filas.forEach(function(fila) {
            fila.style.display = "";
        });

        // Volver a mostrar el Gran Total
        let totalPlanilla = document.getElementById('filaTotalPlanilla');
        if(totalPlanilla) {
            totalPlanilla.style.display = "";
        }
        
        document.getElementById('badgeFiltroJornal').classList.add('d-none');
        
        // Intentar cerrar el modal si está abierto
        var modalElement = document.getElementById('modalBuscarJornal');
        var modalInstance = bootstrap.Modal.getInstance(modalElement);
        if(modalInstance) modalInstance.hide();
    }

    // Lógica para alertas del sistema global (si aplica)
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const alerta = urlParams.get('alerta');

        if (alerta === 'guardado') {
            Swal.fire({
                icon: 'success',
                title: '¡Planilla Guardada!',
                text: 'Los cálculos se han registrado en el historial contable con éxito.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
            window.history.replaceState(null, null, window.location.pathname + "?vista=jornales");
        } else if (alerta === 'error') {
            Swal.fire({
                icon: 'error',
                title: 'Error del Sistema',
                text: 'Ocurrió un problema al guardar la planilla.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
            window.history.replaceState(null, null, window.location.pathname + "?vista=jornales");
        }
    });
</script>