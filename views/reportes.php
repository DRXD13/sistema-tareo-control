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

<style>
    /* ====================================================================
       ESTILOS EXCLUSIVOS PARA LA IMPRESIÓN (PDF FORMAL)
       ==================================================================== */
    @media print {
        /* Ocultar toda la interfaz de la web (menús, botones, barra superior) */
        body * { visibility: hidden; }
        
        /* Mostrar SOLO el contenedor del reporte */
        #zonaDeImpresion, #zonaDeImpresion * { visibility: visible; }
        
        /* Posicionar el reporte en la esquina superior izquierda de la hoja A4 */
        #zonaDeImpresion {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            color: #000; /* Forzar tinta negra pura */
        }

        /* Limpiar fondos, sombras y bordes de Bootstrap para ahorrar tinta */
        .card { border: none !important; box-shadow: none !important; }
        .bg-light, .bg-white, .bg-primary { background-color: transparent !important; }
        .text-primary, .text-success, .text-secondary { color: #000 !important; }
        
        /* Estilos estrictos para la tabla (Blanco y Negro puro) */
        .table { width: 100% !important; border-collapse: collapse !important; margin-bottom: 30px !important; }
        .table th, .table td { 
            border: 1px solid #000 !important; 
            padding: 8px !important; 
            color: #000 !important;
            font-size: 11pt !important;
        }
        .table th { background-color: #f2f2f2 !important; font-weight: bold !important; }
        .badge { border: none !important; padding: 0 !important; font-weight: normal !important; }
        
        /* Mostrar los elementos formales (Cabecera y Firmas) que en la web están ocultos */
        #cabeceraFormal, #seccionFirmas { display: block !important; }
        .d-print-none { display: none !important; }
    }

    /* Ocultar elementos de impresión en la vista web normal */
    @media screen {
        #cabeceraFormal, #seccionFirmas { display: none; }
    }
</style>

<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <div>
            <h3 class="text-primary m-0 fw-bold">📊 Reportes Generales Consolidados</h3>
            <p class="text-muted small m-0">Genera y exporta el resumen financiero para aprobación.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 d-print-none">
        <div class="card-body bg-white d-flex flex-wrap align-items-center justify-content-between p-3">
            <div class="text-secondary fw-bold mb-2 mb-md-0">
                <span class="fs-5">📅 Parámetros del Reporte</span>
            </div>
            <form action="index.php" method="GET" class="d-flex flex-wrap align-items-center m-0 gap-3">
                <input type="hidden" name="vista" value="reportes">
                
                <div class="d-flex align-items-center">
                    <label class="fw-bold me-2 text-secondary small">Desde:</label>
                    <input type="date" name="fecha_inicio" class="form-control form-control-sm border-primary shadow-sm" value="<?php echo $fecha_inicio; ?>" required>
                </div>
                
                <div class="d-flex align-items-center">
                    <label class="fw-bold me-2 text-secondary small">Hasta:</label>
                    <input type="date" name="fecha_fin" class="form-control form-control-sm border-primary shadow-sm" value="<?php echo $fecha_fin; ?>" required>
                </div>
                
                <button type="submit" class="btn btn-sm btn-primary fw-bold shadow-sm px-4">🔍 Generar</button>
                
                <?php if (count($listaReporte) > 0): ?>
                    <div class="vr mx-1"></div> 
                    <button type="button" onclick="window.print()" class="btn btn-sm btn-danger fw-bold shadow-sm">
                        🖨️ Imprimir / PDF Formal
                    </button>
                    <a href="index.php?accion=exportar_excel&fecha_inicio=<?php echo $fecha_inicio; ?>&fecha_fin=<?php echo $fecha_fin; ?>" class="btn btn-sm btn-success fw-bold shadow-sm">📗 Excel</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div id="zonaDeImpresion">
        
        <div id="cabeceraFormal" class="mb-4 text-center">
            <div style="border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
                <h2 style="margin: 0; font-weight: bold; text-transform: uppercase;">Sistema de Tareo Web</h2>
                <h4 style="margin: 5px 0; color: #333;">Reporte de Planillas Consolidadas</h4>
                <p style="margin: 0; font-size: 10pt;">
                    <strong>Sede Operativa:</strong> Pisco, Ica | <strong>Fecha de Emisión:</strong> <?php echo date('d/m/Y'); ?> <br>
                    <strong>Período Evaluado:</strong> Del <?php echo date('d/m/Y', strtotime($fecha_inicio)); ?> al <?php echo date('d/m/Y', strtotime($fecha_fin)); ?>
                </p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold text-secondary py-3 d-flex justify-content-between align-items-center d-print-none">
                <span>📋 Resumen de Planilla</span>
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 shadow-sm">Período: <?php echo date('d/m/Y', strtotime($fecha_inicio)); ?> al <?php echo date('d/m/Y', strtotime($fecha_fin)); ?></span>
            </div>
            
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover m-0 align-middle text-center" style="font-size: 0.95rem;">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th class="text-start ps-4">Nombres y Apellidos</th>
                            <th>Documento</th>
                            <th>Cargo</th>
                            <th>Total Horas</th>
                            <th class="text-success fw-bold">Total a Pagar (S/)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($listaReporte) > 0): ?>
                            <?php foreach ($listaReporte as $r): 
                                $gran_total_dinero += $r['total_pagado'];
                                $gran_total_horas += $r['total_horas'];
                            ?>
                                <tr>
                                    <td class="text-start ps-4 fw-bold text-dark"><?php echo $r['nombres'] . ' ' . $r['apellidos']; ?></td>
                                    <td class="text-muted small"><?php echo $r['numero_documento']; ?></td>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2">
                                            <?php echo $r['nombre_cargo']; ?>
                                        </span>
                                    </td>
                                    <td class="fw-semibold text-dark"><?php echo $r['total_horas']; ?> hrs</td>
                                    <td class="text-success fw-bold">S/ <?php echo number_format($r['total_pagado'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            
                            <tr class="bg-light border-top border-2 border-dark" style="border-top-width: 2px !important;">
                                <td colspan="3" class="text-end fw-bold text-primary fs-6 pe-4">GRAN TOTAL CONSOLIDADO:</td>
                                <td class="text-dark fw-bold fs-6"><?php echo $gran_total_horas; ?> hrs</td>
                                <td class="text-primary fw-bold fs-5 bg-primary bg-opacity-10">S/ <?php echo number_format($gran_total_dinero, 2); ?></td>
                            </tr>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-5 text-danger fw-bold">No hay jornales cerrados en este rango de fechas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="seccionFirmas" style="margin-top: 80px;">
            <table style="width: 100%; text-align: center; border: none !important;">
                <tr style="border: none !important;">
                    <td style="border: none !important; width: 33%;">
                        __________________________________<br>
                        <strong>Firma de Elaboración</strong><br>
                        <small>Recursos Humanos</small>
                    </td>
                    <td style="border: none !important; width: 33%;">
                        __________________________________<br>
                        <strong>Firma de Revisión</strong><br>
                        <small>Gerencia de Operaciones</small>
                    </td>
                    <td style="border: none !important; width: 33%;">
                        __________________________________<br>
                        <strong>Sello y Conformidad</strong><br>
                        <small>Administración</small>
                    </td>
                </tr>
            </table>
            <p style="text-align: right; font-size: 8pt; margin-top: 30px; color: #666;">
                Documento generado por: <?php echo $_SESSION['nombres']; ?> | Sistema de Tareo Web
            </p>
        </div>

    </div>
</div>