<?php
require_once "models/Trabajador.php";
require_once "models/Area.php";
require_once "models/Cuadrilla.php";

// Instanciamos los modelos para sacar las métricas reales
$trabajadorModel = new Trabajador();
$listaTrabajadores = $trabajadorModel->listarTrabajadores();
$activos = count(array_filter($listaTrabajadores, function($t) { return $t['estado'] == 1; }));
$inactivos = count($listaTrabajadores) - $activos; // Calculamos inactivos para el gráfico

$areaModel = new Area();
$listaAreas = $areaModel->listarAreas();
$areasActivas = count(array_filter($listaAreas, function($a) { return $a['estado'] == 1; }));

$cuadrillaModel = new Cuadrilla();
$listaCuadrillas = $cuadrillaModel->listarCuadrillas();
$cuadrillasActivas = count(array_filter($listaCuadrillas, function($c) { return $c['estado'] == 1; }));
?>

<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-primary m-0 fw-bold">📊 Panel de Control Analítico</h3>
            <p class="text-muted small m-0">Resumen operativo del Sistema de Tareo - Municipalidad Distrital Túpac Amaru Inca.</p>
        </div>
        <div class="text-end">
            <span class="text-secondary fw-bold">Fecha:</span> 
            <span class="badge bg-primary fs-6"><?php echo date('d / m / Y'); ?></span>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 border-start border-primary border-4 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Personal Activo en Campo</div>
                            <div class="h3 mb-0 fw-bold text-dark"><?php echo $activos; ?> <span class="fs-6 text-muted fw-normal">Trabajadores</span></div>
                        </div>
                        <div class="col-auto">
                            <span class="fs-1">👥</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 border-start border-success border-4 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Departamentos / Áreas</div>
                            <div class="h3 mb-0 fw-bold text-dark"><?php echo $areasActivas; ?> <span class="fs-6 text-muted fw-normal">Operativas</span></div>
                        </div>
                        <div class="col-auto">
                            <span class="fs-1">🏢</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 border-start border-info border-4 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">Grupos de Trabajo</div>
                            <div class="h3 mb-0 fw-bold text-dark"><?php echo $cuadrillasActivas; ?> <span class="fs-6 text-muted fw-normal">Cuadrillas</span></div>
                        </div>
                        <div class="col-auto">
                            <span class="fs-1">🚜</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold text-secondary py-3">
                    📈 Tendencia de Asistencia (Últimos 5 Días)
                </div>
                <div class="card-body">
                    <canvas id="asistenciaChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold text-secondary py-3">
                    📊 Distribución de Personal
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <canvas id="personalChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. CONFIGURACIÓN DEL GRÁFICO CIRCULAR (DOUGHNUT) - DATOS REALES DE PHP
        const ctxPersonal = document.getElementById('personalChart').getContext('2d');
        const personalChart = new Chart(ctxPersonal, {
            type: 'doughnut',
            data: {
                labels: ['Activos', 'Inactivos'],
                datasets: [{
                    data: [<?php echo $activos; ?>, <?php echo $inactivos; ?>],
                    backgroundColor: ['#198754', '#dc3545'], // Verde y Rojo Bootstrap
                    hoverBackgroundColor: ['#157347', '#bb2d3b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // 2. CONFIGURACIÓN DEL GRÁFICO DE BARRAS - DATOS SIMULADOS PARA DEMOSTRACIÓN
        // (En el futuro, estos datos pueden venir de un array de PHP consultando las asistencias por fecha)
        const ctxAsistencia = document.getElementById('asistenciaChart').getContext('2d');
        const asistenciaChart = new Chart(ctxAsistencia, {
            type: 'bar',
            data: {
                labels: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'],
                datasets: [
                    {
                        label: 'Asistencias',
                        data: [45, 48, 46, 42, 47],
                        backgroundColor: 'rgba(13, 110, 253, 0.7)', // Azul primario
                        borderColor: 'rgba(13, 110, 253, 1)',
                        borderWidth: 1,
                        borderRadius: 5
                    },
                    {
                        label: 'Faltas',
                        data: [5, 2, 4, 8, 3],
                        backgroundColor: 'rgba(220, 53, 69, 0.7)', // Rojo peligro
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1,
                        borderRadius: 5
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });

    });
</script>