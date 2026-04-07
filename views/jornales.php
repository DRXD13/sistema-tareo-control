<?php
require_once "models/Jornal.php";

// Si el usuario no ha elegido fechas, por defecto mostramos desde el día 1 hasta hoy
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-d');

$jornalModel = new Jornal();
$listaJornales = $jornalModel->calcularJornales($fecha_inicio, $fecha_fin);

// Variable para sumar el total de toda la planilla
$total_planilla = 0;
?>

<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary m-0">💰 Control de Jornales (Planilla)</h3>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-light">
            <form action="index.php" method="GET" class="row align-items-center">
                <input type="hidden" name="vista" value="jornales">
                
                <div class="col-auto">
                    <label class="fw-bold text-secondary">Desde:</label>
                    <input type="date" name="fecha_inicio" class="form-control border-primary" value="<?php echo $fecha_inicio; ?>" required>
                </div>
                
                <div class="col-auto">
                    <label class="fw-bold text-secondary">Hasta:</label>
                    <input type="date" name="fecha_fin" class="form-control border-primary" value="<?php echo $fecha_fin; ?>" required>
                </div>
                
                <div class="col-auto mt-4">
                    <button type="submit" class="btn btn-primary fw-bold">🔄 Calcular Planilla</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold text-secondary">
            Cálculo de Pagos: <?php echo date('d/m/Y', strtotime($fecha_inicio)); ?> al <?php echo date('d/m/Y', strtotime($fecha_fin)); ?>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-striped table-hover m-0 align-middle text-center" style="font-size: 0.95rem;">
                <thead class="table-dark">
                    <tr>
                        <th class="text-start">Trabajador</th>
                        <th>Jornal Diario (8h)</th>
                        <th>Total Horas Trabajadas</th>
                        <th class="text-success fw-bold">Total a Pagar (S/)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($listaJornales) > 0): ?>
                        <?php foreach ($listaJornales as $j): 
                            // Sumamos al total general de la planilla
                            $total_planilla += $j['total_pagar'];
                        ?>
                            <tr>
                                <td class="text-start fw-bold">
                                    <?php echo $j['nombres'] . ' ' . $j['apellidos']; ?>
                                    <br><small class="text-muted fw-normal">Doc: <?php echo $j['numero_documento']; ?></small>
                                </td>
                                <td>S/ <?php echo number_format($j['jornal_diario'], 2); ?></td>
                                <td><span class="badge bg-secondary fs-6"><?php echo $j['total_horas']; ?> hrs</span></td>
                                <td class="text-success fw-bold fs-6">S/ <?php echo number_format($j['total_pagar'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <tr class="table-info fw-bold fs-5">
                            <td colspan="3" class="text-end">TOTAL PLANILLA:</td>
                            <td class="text-success">S/ <?php echo number_format($total_planilla, 2); ?></td>
                        </tr>

                    <?php else: ?>
                        <tr><td colspan="4" class="text-center py-4 text-danger fw-bold">No hay tareos registrados en este rango de fechas.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>