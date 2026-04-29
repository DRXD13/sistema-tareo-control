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
            <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 shadow-sm">Período: <?php echo date('d/m/Y', strtotime($fecha_inicio)); ?> al <?php echo date('d/m/Y', strtotime($fecha_fin)); ?></span>
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
                        <tbody>
                            <?php if (count($listaJornales) > 0): ?>
                                <?php foreach ($listaJornales as $j): 
                                    $total_planilla += $j['total_pagar'];
                                ?>
                                    <tr>
                                        <td class="text-start ps-4">
                                            <input type="hidden" name="id_trabajador[]" value="<?php echo $j['id_trabajador']; ?>">
                                            <input type="hidden" name="jornal_diario[]" value="<?php echo $j['jornal_diario']; ?>">
                                            <input type="hidden" name="total_horas[]" value="<?php echo $j['total_horas']; ?>">
                                            <input type="hidden" name="total_pagar[]" value="<?php echo $j['total_pagar']; ?>">
                                            
                                            <span class="fw-bold text-dark"><?php echo $j['nombres'] . ' ' . $j['apellidos']; ?></span>
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
                                
                                <tr class="bg-light border-top border-2 border-primary">
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