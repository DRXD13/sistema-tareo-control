<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Tareo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/estilos.css" rel="stylesheet">
    <style>
        /* ESTILOS PREMIUM PARA EL MENÚ LATERAL */
        .sidebar {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar a {
            color: #cbd5e1 !important;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            font-size: 0.95rem;
        }
        .sidebar a:hover {
            background-color: rgba(255,255,255,0.1);
            color: #ffffff !important;
            border-left: 4px solid #3b82f6;
            padding-left: 25px;
        }
        .sidebar-brand {
            background-color: rgba(0,0,0,0.2);
            font-size: 1.2rem;
            letter-spacing: 1px;
        }
        /* BARRA SUPERIOR */
        .topbar-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 3px solid #3b82f6;
        }
        /* Animación suave para el dropdown */
        .dropdown-menu {
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-light">
    <?php 
    $rol_actual = isset($_SESSION['id_rol']) ? $_SESSION['id_rol'] : 0; 
    
    $nombres_roles = [
        1 => 'Administrador',
        2 => 'Supervisor de Campo',
        3 => 'Recursos Humanos',
        4 => 'Jefe de Área'
    ];
    $nombre_rol_mostrar = isset($nombres_roles[$rol_actual]) ? $nombres_roles[$rol_actual] : 'Usuario';
    ?>
    
    <div class="d-flex">
        <div class="sidebar d-flex flex-column" style="width: 270px; min-height: 100vh;">
            <div class="sidebar-brand text-white text-center py-4 border-bottom border-secondary mb-3 fw-bold shadow-sm">
                ⚙️ TAREO WEB
            </div>
            
            <div class="flex-grow-1">
                <a href="index.php?vista=inicio">🏠 Inicio</a>
                
                <?php if (in_array($rol_actual, [1])): ?>
                    <div class="text-secondary small fw-bold px-4 mt-3 mb-1 text-uppercase">Organización</div>
                    <a href="index.php?vista=areas">🏢 Gestión de Áreas</a>
                    <a href="index.php?vista=cargos">💼 Gestión de Cargos</a>
                    <a href="index.php?vista=actividades">⚙️ Catálogo Actividades</a>
                <?php endif; ?>

                <?php if (in_array($rol_actual, [1, 2])): ?>
                    <div class="text-secondary small fw-bold px-4 mt-3 mb-1 text-uppercase">Campo</div>
                    <a href="index.php?vista=cuadrillas">🚜 Gestión de Cuadrillas</a>
                <?php endif; ?>

                <?php if (in_array($rol_actual, [1, 3, 4])): ?>
                    <div class="text-secondary small fw-bold px-4 mt-3 mb-1 text-uppercase">Personal</div>
                    <a href="index.php?vista=trabajadores">👥 Gestión de Personal</a>
                <?php endif; ?>

                <?php if (in_array($rol_actual, [1, 2, 3, 4])): ?>
                    <div class="text-secondary small fw-bold px-4 mt-3 mb-1 text-uppercase">Operaciones</div>
                    <a href="index.php?vista=asistencias">⏱️ Asistencias</a>
                    <a href="index.php?vista=tareo">📋 Tareo Diario</a>
                    <a href="index.php?vista=actividades_diarias">🛠️ Actividades Diarias</a>
                <?php endif; ?>

                <?php if (in_array($rol_actual, [1, 3])): ?>
                    <div class="text-secondary small fw-bold px-4 mt-3 mb-1 text-uppercase">Reportes</div>
                    <a href="index.php?vista=jornales">💰 Jornales</a>
                    <a href="index.php?vista=reportes">📊 Reportes Generales</a>
                <?php endif; ?>

                <?php if (in_array($rol_actual, [1])): ?>
                    <div class="text-secondary small fw-bold px-4 mt-3 mb-1 text-uppercase">Seguridad</div>
                    <a href="index.php?vista=bitacora">📓 Bitácora</a>
                <?php endif; ?>
            </div>
            
            <div class="mt-auto border-top border-secondary pt-3 pb-4 text-center">
                <small class="text-secondary d-block mb-2">v2.0 Stable</small>
            </div>
        </div>

        <div class="flex-grow-1" style="max-height: 100vh; overflow-y: auto;">
            
            <div class="topbar-glass p-3 shadow-sm d-flex justify-content-between align-items-center mb-4 sticky-top">
                <div>
                    <h5 class="m-0 text-dark fw-bold" id="saludo-dinamico">Cargando...</h5>
                    <small class="text-muted fw-bold" id="reloj-digital">📅 Conectando...</small>
                </div>

                <div class="dropdown">
                    <button class="btn btn-light border shadow-sm px-3 py-2 text-start d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="min-width: 200px; border-radius: 10px;">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 35px; height: 35px; font-size: 1.2rem;">
                            👤
                        </div>
                        <div style="line-height: 1.1;">
                            <span class="d-block fw-bold text-dark" style="font-size: 0.85rem;"><?php echo explode(' ', trim($_SESSION['nombres']))[0]; ?></span>
                            <span class="text-primary fw-bold" style="font-size: 0.7rem; text-uppercase: uppercase;"><?php echo $nombre_rol_mostrar; ?></span>
                        </div>
                        <div class="ms-auto ps-3 text-secondary" style="font-size: 0.6rem;">▼</div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-2" aria-labelledby="userDropdown" style="border-radius: 12px; min-width: 200px;">
                        <li><h6 class="dropdown-header text-uppercase text-muted fw-bold" style="font-size: 0.65rem;">Opciones de Cuenta</h6></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2 rounded" href="logout.php">
                                <span class="me-2">🔄</span> Cambiar de usuario
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2 rounded text-danger fw-bold" href="logout.php">
                                <span class="me-2">🚪</span> Cerrar sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="container-fluid px-4 pb-4">
                <?php 
                $vista = isset($_GET['vista']) ? $_GET['vista'] : 'inicio';
                $vistas_permitidas = ['inicio', 'areas', 'cargos', 'cuadrillas', 'trabajadores', 'tareo', 'actividades', 'jornales', 'asistencias', 'actividades_diarias', 'bitacora', 'reportes'];
                
                if (in_array($vista, $vistas_permitidas)) {
                    require_once "views/" . $vista . ".php";
                } else {
                    require_once "views/inicio.php";
                } 
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function actualizarReloj() {
            const ahora = new Date();
            const horas = ahora.getHours();
            const minutos = ahora.getMinutes().toString().padStart(2, '0');
            const segundos = ahora.getSeconds().toString().padStart(2, '0');
            
            let saludo = '¡Buenas noches';
            if (horas >= 6 && horas < 12) {
                saludo = '¡Buenos días';
            } else if (horas >= 12 && horas < 19) {
                saludo = '¡Buenas tardes';
            }

            const nombreUsuario = "<?php echo explode(' ', trim($_SESSION['nombres']))[0]; ?>";
            document.getElementById('saludo-dinamico').innerHTML = `${saludo}, ${nombreUsuario}! 👋`;
            
            const opcionesFecha = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const fechaTxt = ahora.toLocaleDateString('es-PE', opcionesFecha);
            
            document.getElementById('reloj-digital').innerHTML = `📅 ${fechaTxt.charAt(0).toUpperCase() + fechaTxt.slice(1)} | ⏰ ${horas}:${minutos}:${segundos}`;
        }
        
        setInterval(actualizarReloj, 1000);
        actualizarReloj();

        function lanzarAlerta(tipo, titulo, mensaje) {
            Swal.fire({
                icon: tipo,
                title: titulo,
                text: mensaje,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end'
            });
        }

        <?php if (isset($_GET['alerta'])): ?>
            <?php if ($_GET['alerta'] == 'guardado'): ?>
                lanzarAlerta('success', '¡Registrado!', 'Los datos se guardaron correctamente.');
            <?php elseif ($_GET['alerta'] == 'actualizado'): ?>
                lanzarAlerta('info', '¡Comprobado!', 'La información ha sido actualizada.');
            <?php elseif ($_GET['alerta'] == 'duplicado'): ?>
                lanzarAlerta('warning', '¡Atención!', 'El trabajador ya tiene una asistencia registrada hoy.');
            <?php elseif ($_GET['alerta'] == 'error'): ?>
                lanzarAlerta('error', '¡Atención!', 'No se pudo completar la operación.');
            <?php endif; ?>
        <?php endif; ?>
    </script>
</body>
</html>