<?php
require_once "models/Trabajador.php";
require_once "models/Area.php";
require_once "models/Cuadrilla.php";

// Instanciamos los modelos para sacar las métricas reales
$trabajadorModel = new Trabajador();
$listaTrabajadores = $trabajadorModel->listarTrabajadores();
$activos = count(array_filter($listaTrabajadores, function($t) { return $t['estado'] == 1; }));

$areaModel = new Area();
$listaAreas = $areaModel->listarAreas();
$areasActivas = count(array_filter($listaAreas, function($a) { return $a['estado'] == 1; }));

$cuadrillaModel = new Cuadrilla();
$listaCuadrillas = $cuadrillaModel->listarCuadrillas();
$cuadrillasActivas = count(array_filter($listaCuadrillas, function($c) { return $c['estado'] == 1; }));
?>

<div class="container-fluid px-4 mt-4">
    <!-- CABECERA -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-primary m-0 fw-bold">📊 Panel de Control Analítico</h3>
            <p class="text-muted small m-0">Resumen operativo del Sistema de Tareo.</p>
        </div>
        <div class="text-end">
            <span class="text-secondary fw-bold">Fecha:</span> 
            <span class="badge bg-primary fs-6"><?php echo date('d / m / Y'); ?></span>
        </div>
    </div>

    <!-- TARJETAS DE INDICADORES (KPIs) -->
    <div class="row mb-4">
        <!-- Tarjeta 1: Personal -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 border-start border-primary border-4 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Personal Activo en Campo</div>
                            <div class="h3 mb-0 fw-bold text-dark"><?php echo $activos; ?> <span class="fs-6 text-muted fw-normal">Trabajadores</span></div>
                        </div>
                        <div class="col-auto">
                            <span class="fs-1">👥</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta 2: Áreas -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 border-start border-success border-4 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Departamentos / Áreas</div>
                            <div class="h3 mb-0 fw-bold text-dark"><?php echo $areasActivas; ?> <span class="fs-6 text-muted fw-normal">Operativas</span></div>
                        </div>
                        <div class="col-auto">
                            <span class="fs-1">🏢</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta 3: Cuadrillas -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 border-start border-info border-4 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                Grupos de Trabajo</div>
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
</div>