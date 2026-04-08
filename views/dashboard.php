<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Tareo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; background-color: #212529; }
        .sidebar a { color: #cfd8dc; text-decoration: none; display: block; padding: 15px 20px; transition: 0.3s; }
        .sidebar a:hover { background-color: #343a40; color: #fff; border-left: 4px solid #0d6efd; }
    </style>
</head>
<body>
    
    <div class="d-flex">
        <div class="sidebar" style="width: 260px; min-height: 100vh;">
            <h4 class="text-white text-center py-4 border-bottom border-secondary m-0">
                ⚙️ Tareo Web
            </h4>
            <div class="mt-3">
                <a href="index.php?vista=inicio">🏠 Inicio</a>
                
                <?php 
                // 🔒 Solo Administrador (1) y Asistente RR.HH. (3) pueden gestionar la base del sistema
                if (isset($_SESSION['id_rol']) && ($_SESSION['id_rol'] == 1 || $_SESSION['id_rol'] == 3)): 
                ?>
                    <a href="index.php?vista=areas">🏢 Gestión de Áreas</a>
                    <a href="index.php?vista=cargos">💼 Gestión de Cargos</a>
                    <a href="index.php?vista=cuadrillas">🚜 Gestión de Cuadrillas</a>
                    <a href="index.php?vista=trabajadores">👥 Gestión de Personal</a>
                    <a href="index.php?vista=actividades">⚙️ Catálogo Actividades</a>
                    <a href="index.php?vista=jornales">💰 Control de Jornales</a>
                <?php endif; ?>

                <?php 
                // 🔒 Operativo: Admin (1), Supervisor (2) y RR.HH. (3) pueden registrar el día a día
                if (isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], [1, 2, 3])): 
                ?>
                    <a href="index.php?vista=asistencias">⏱️ Asistencias (Ingreso/Salida)</a>
                    <a href="index.php?vista=tareo">📋 Tareo Diario</a>
                    <a href="index.php?vista=actividades_diarias">🛠️ Actividades Diarias</a>
                <?php endif; ?>

                <?php 
                // 🔒 Solo el Administrador (1) puede ver las bitácoras y los reportes finales
                if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1): 
                ?>
                    <a href="index.php?vista=bitacora">📓 Bitácora del Sistema</a>
                    <a href="index.php?vista=reportes">📊 Reportes Generales</a>
                <?php endif; ?>
            </div>
            
            <div style="position: absolute; bottom: 20px; width: 260px;">
                <a href="logout.php" class="text-danger fw-bold">🚪 Cerrar Sesión</a>
            </div>
        </div>

        <div class="flex-grow-1 bg-light">
            <div class="bg-white p-3 shadow-sm d-flex justify-content-between align-items-center mb-4">
                <h5 class="m-0 text-secondary">Panel de Control</h5>
                <div>
                    <span class="me-3">👤 Bienvenido, <b><?php echo $_SESSION['nombres']; ?></b> (Rol: <?php echo $_SESSION['id_rol']; ?>)</span>
                </div>
            </div>

            <div class="container-fluid px-4">
                <?php 
                // Detectamos qué vista quiere ver el usuario
                $vista = isset($_GET['vista']) ? $_GET['vista'] : 'inicio';

                // Mostramos la vista correspondiente
                if ($vista == 'areas') {
                    require_once "views/areas.php";
                } elseif ($vista == 'cargos') {
                    require_once "views/cargos.php";
                } elseif ($vista == 'cuadrillas') {
                    require_once "views/cuadrillas.php";
                } elseif ($vista == 'trabajadores') {
                    require_once "views/trabajadores.php";
                } elseif ($vista == 'tareo') {
                    require_once "views/tareo.php";
                } elseif ($vista == 'actividades') {
                    require_once "views/actividades.php";
                } elseif ($vista == 'jornales') {
                    require_once "views/jornales.php";
                } elseif ($vista == 'asistencias') {
                    require_once "views/asistencias.php";
                } elseif ($vista == 'actividades_diarias') {
                    require_once "views/actividades_diarias.php";
                } elseif ($vista == 'bitacora') {
                    require_once "views/bitacora.php";
                } elseif ($vista == 'reportes') {
                    require_once "views/reportes.php";
                } else {
                    // Pantalla por defecto
                ?>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5 text-center">
                            <h2 class="text-primary mb-3">¡Sistema de Tareo Iniciado!</h2>
                            <p class="lead text-muted">Selecciona una opción del menú lateral para comenzar a operar.</p>
                        </div>
                    </div>
                <?php 
                } 
                ?>
            </div>
        </div>
    </div>

</body>
</html>