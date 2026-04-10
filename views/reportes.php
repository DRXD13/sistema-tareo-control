<?php
// 🔒 SEGURIDAD: Solo Admin (1) y RR.HH. (3)
if (!isset($_SESSION['id_rol']) || !in_array($_SESSION['id_rol'], [1, 3])) {
    echo '<div class="alert alert-danger mt-4 text-center fw-bold fs-5">🛑 Acceso Denegado: Módulo exclusivo para Gerencia y RR.HH.</div>';
    exit();
}

require_once "models/Reporte.php";

$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-t');

$reporteModel = new Reporte();
$listaReporte = $reporteModel->resumenPlanilla($fecha_inicio, $fecha_fin);

$gran_total_dinero = 0;
$gran_total_horas = 0;
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div class="container-fluid px-4 mt-4">
    <h3 class="text-primary mb-4">📊 Reportes Generales Consolidados</h3>

    <div class="card border-0 shadow-sm mb-4 border-top border-primary border-3" data-html2canvas-ignore="true">
        <div class="card-body bg-light">
            <form action="index.php" method="GET" class="row align-items-center">
                <input type="hidden" name="vista" value="reportes">
                
                <div class="col-md-3">
                    <label class="fw-bold text-secondary small">Desde:</label>
                    <input type="date" name="fecha_inicio" class="form-control form-control-sm border-primary" value="<?php echo $fecha_inicio; ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="fw-bold text-secondary small">Hasta:</label>
                    <input type="date" name="fecha_fin" class="form-control form-control-sm border-primary" value="<?php echo $fecha_fin; ?>" required>
                </div>
                <div class="col-md-3 mt-4">
                    <button type="submit" class="btn btn-sm btn-primary fw-bold px-4">🔍 Generar Reporte</button>
                </div>
                
                <div class="col-md-3 mt-4 text-end">
                    <?php if (count($listaReporte) > 0): ?>
                        <button type="button" onclick="exportarPDF()" class="btn btn-sm btn-danger fw-bold shadow-sm me-1">📄 Descargar PDF</button>
                        
                        <a href="index.php?accion=exportar_excel&fecha_inicio=<?php echo $fecha_inicio; ?>&fecha_fin=<?php echo $fecha_fin; ?>" class="btn btn-sm btn-success fw-bold shadow-sm">📗 Descargar Excel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div id="contenidoParaPDF" class="bg-white p-3">
        <div class="text-center mb-4 d-none" id="cabeceraImpresion">
            <h4 class="fw-bold">Reporte de Planillas Consolidadas</h4>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white fw-bold">
                Resumen de Planilla: <?php echo date('d/m/Y', strtotime($fecha_inicio)); ?> al <?php echo date('d/m/Y', strtotime($fecha_fin)); ?>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-striped table-bordered m-0 align-middle text-center" style="font-size: 0.9rem;">
                    <thead class="table-secondary">
                        <tr>
                            <th class="text-start">Trabajador</th>
                            <th>Documento</th>
                            <th>Cargo</th>
                            <th>Total Horas</th>
                            <th class="text-success fw-bold">Total Pagado (S/)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($listaReporte) > 0): ?>
                            <?php foreach ($listaReporte as $r): 
                                $gran_total_dinero += $r['total_pagado'];
                                $gran_total_horas += $r['total_horas'];
                            ?>
                                <tr>
                                    <td class="text-start fw-bold">👤 <?php echo $r['nombres'] . ' ' . $r['apellidos']; ?></td>
                                    <td class="text-muted"><?php echo $r['numero_documento']; ?></td>
                                    <td><?php echo $r['nombre_cargo']; ?></td>
                                    <td><?php echo $r['total_horas']; ?> hrs</td>
                                    <td class="text-success fw-bold">S/ <?php echo number_format($r['total_pagado'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            
                            <tr class="table-dark fs-6">
                                <td colspan="3" class="text-end fw-bold">GRAN TOTAL:</td>
                                <td class="fw-bold text-warning"><?php echo $gran_total_horas; ?> hrs</td>
                                <td class="fw-bold text-success fs-5">S/ <?php echo number_format($gran_total_dinero, 2); ?></td>
                            </tr>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted fs-5">No hay jornales cerrados en este rango de fechas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function exportarPDF() {
    // Mostramos la cabecera oculta solo para el PDF
    document.getElementById('cabeceraImpresion').classList.remove('d-none');
    
    var elemento = document.getElementById('contenidoParaPDF');
    
    var opciones = {
        margin:       10,
        filename:     'Reporte_Planilla_<?php echo $fecha_inicio; ?>.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    html2pdf().set(opciones).from(elemento).save().then(() => {
        // Volvemos a ocultar la cabecera una vez descargado
        document.getElementById('cabeceraImpresion').classList.add('d-none');
    });
}
</script>