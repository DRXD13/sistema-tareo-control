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
    <?php 
    // Capturamos el rol actual para simplificar la lógica de validación
    $rol_actual = isset($_SESSION['id_rol']) ? $_SESSION['id_rol'] : 0; 
    ?>
    <div class="d-flex">
        <div class="sidebar" style="width: 260px; min-height: 100vh;">
            <h4 class="text-white text-center py-4 border-bottom border-secondary m-0">
                ⚙️ Tareo Web
            </h4>
            <div class="mt-3">
                <a href="index.php?vista=inicio">🏠 Inicio</a>
                
                <?php if (in_array($rol_actual, [1])): // Solo Admin ?>
                    <a href="index.php?vista=areas">🏢 Gestión de Áreas</a>
                    <a href="index.php?vista=cargos">💼 Gestión de Cargos</a>
                    <a href="index.php?vista=actividades">⚙️ Catálogo Actividades</a>
                <?php endif; ?>

                <?php if (in_array($rol_actual, [1, 2])): // Admin y Supervisor ?>
                    <a href="index.php?vista=cuadrillas">🚜 Gestión de Cuadrillas</a>
                <?php endif; ?>

                <?php if (in_array($rol_actual, [1, 3, 4])): // Admin, RRHH, Jefe Área ?>
                    <a href="index.php?vista=trabajadores">👥 Gestión de Personal</a>
                <?php endif; ?>

                <?php if (in_array($rol_actual, [1, 2, 3, 4])): // Todos los roles ?>
                    <a href="index.php?vista=asistencias">⏱️ Asistencias (Ingreso/Salida)</a>
                    <a href="index.php?vista=tareo">📋 Tareo Diario</a>
                    <a href="index.php?vista=actividades_diarias">🛠️ Actividades Diarias</a>
                <?php endif; ?>

                <?php if (in_array($rol_actual, [1, 3])): // Admin y RRHH ?>
                    <a href="index.php?vista=jornales">💰 Control de Jornales</a>
                    <a href="index.php?vista=reportes">📊 Reportes Generales</a>
                <?php endif; ?>

                <?php if (in_array($rol_actual, [1])): // Solo Admin ?>
                    <a href="index.php?vista=bitacora">📓 Bitácora del Sistema</a>
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
                    <span class="me-3">👤 Bienvenido, <b><?php echo $_SESSION['nombres']; ?></b> (Rol: <?php echo $rol_actual; ?>)</span>
                </div>
            </div>

            <div class="container-fluid px-4">
                <?php 
                $vista = isset($_GET['vista']) ? $_GET['vista'] : 'inicio';

                // Enrutador de Vistas
                $vistas_permitidas = ['areas', 'cargos', 'cuadrillas', 'trabajadores', 'tareo', 'actividades', 'jornales', 'asistencias', 'actividades_diarias', 'bitacora', 'reportes'];
                
                if (in_array($vista, $vistas_permitidas)) {
                    require_once "views/" . $vista . ".php";
                } else {
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