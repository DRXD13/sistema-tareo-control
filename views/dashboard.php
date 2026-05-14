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
            padding-left: 25px; /* Efecto de movimiento */
        }
        .sidebar-brand {
            background-color: rgba(0,0,0,0.2);
            font-size: 1.2rem;
            letter-spacing: 1px;
        }
        /* ESTILO PARA LA BARRA SUPERIOR */
        .topbar-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 3px solid #3b82f6;
        }
    </style>
</head>
<body class="bg-light">
    <?php 
    // Capturamos el rol actual para simplificar la lógica de validación
    $rol_actual = isset($_SESSION['id_rol']) ? $_SESSION['id_rol'] : 0; 
    
    // Diccionario de roles para mostrar el nombre real
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
                
                <?php if (in_array($rol_actual, [1])): // Solo Admin ?>
                    <div class="text-secondary small fw-bold px-4 mt-3 mb-1 text-uppercase">Organización</div>
                    <a href="index.php?vista=areas">🏢 Gestión de Áreas</a>
                    <a href="index.php?vista=cargos">💼 Gestión de Cargos</a>
                    <a href="index.php?vista=actividades">⚙️ Catálogo Actividades</a>
                <?php endif; ?>

                <?php if (in_array($rol_actual, [1, 2])): // Admin y Supervisor ?>
                    <div class="text-secondary small fw-bold px-4 mt-3 mb-1 text-uppercase">Campo</div>
                    <a href="index.php?vista=cuadrillas">🚜 Gestión de Cuadrillas</a>
                <?php endif; ?>

                <?php if (in_array($rol_actual, [1, 3, 4])): // Admin, RRHH, Jefe Área ?>
                    <div class="text-secondary small fw-bold px-4 mt-3 mb-1 text-uppercase">Personal</div>
                    <a href="index.php?vista=trabajadores">👥 Gestión de Personal</a>
                <?php endif; ?>

                <?php if (in_array($rol_actual, [1, 2, 3, 4])): // Todos los roles ?>
                    <div class="text-secondary small fw-bold px-4 mt-3 mb-1 text-uppercase">Operaciones diarias</div>
                    <a href="index.php?vista=asistencias">⏱️ Asistencias (Ingreso/Salida)</a>
                    <a href="index.php?vista=tareo">📋 Tareo Diario</a>
                    <a href="index.php?vista=actividades_diarias">🛠️ Actividades Diarias</a>
                <?php endif; ?>

                <?php if (in_array($rol_actual, [1, 3])): // Admin y RRHH ?>
                    <div class="text-secondary small fw-bold px-4 mt-3 mb-1 text-uppercase">Finanzas y Reportes</div>
                    <a href="index.php?vista=jornales">💰 Control de Jornales</a>
                    <a href="index.php?vista=reportes">📊 Reportes Generales</a>
                <?php endif; ?>

                <?php if (in_array($rol_actual, [1])): // Solo Admin ?>
                    <div class="text-secondary small fw-bold px-4 mt-3 mb-1 text-uppercase">Seguridad</div>
                    <a href="index.php?vista=bitacora">📓 Bitácora del Sistema</a>
                <?php endif; ?>
            </div>
            
            <div class="mt-auto border-top border-secondary pt-3 pb-4">
                <a href="logout.php" class="text-danger fw-bold hover-danger">🚪 Cerrar Sesión</a>
            </div>
        </div>

        <div class="flex-grow-1" style="max-height: 100vh; overflow-y: auto;">
            
            <div class="topbar-glass p-3 shadow-sm d-flex justify-content-between align-items-center mb-4 sticky-top">
                <div>
                    <h5 class="m-0 text-dark fw-bold" id="saludo-dinamico">Cargando...</h5>
                    <small class="text-muted fw-bold" id="reloj-digital">📍 Conectando...</small>
                </div>
                <div class="text-end bg-light px-3 py-2 rounded shadow-sm border">
                    <span class="d-block fw-bold text-dark" style="font-size: 0.9rem;">👤 <?php echo $_SESSION['nombres']; ?></span>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary mt-1"><?php echo $nombre_rol_mostrar; ?></span>
                </div>
            </div>

            <div class="container-fluid px-4 pb-4">
                <?php 
                $vista = isset($_GET['vista']) ? $_GET['vista'] : 'inicio';

                // Enrutador de Vistas
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
        // 1. Reloj en tiempo real y Saludo Dinámico
        function actualizarReloj() {
            const ahora = new Date();
            const horas = ahora.getHours();
            const minutos = ahora.getMinutes().toString().padStart(2, '0');
            const segundos = ahora.getSeconds().toString().padStart(2, '0');
            
            // Lógica del saludo según la hora
            let saludo = '¡Buenas noches';
            if (horas >= 6 && horas < 12) {
                saludo = '¡Buenos días';
            } else if (horas >= 12 && horas < 19) {
                saludo = '¡Buenas tardes';
            }

            const nombreUsuario = "<?php echo explode(' ', trim($_SESSION['nombres']))[0]; ?>"; // Saca solo el primer nombre
            document.getElementById('saludo-dinamico').innerHTML = `${saludo}, ${nombreUsuario}! 👋`;
            
            // Formatear Fecha
            const opcionesFecha = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const fechaTxt = ahora.toLocaleDateString('es-PE', opcionesFecha);
            
            // Mostrar info (Agregamos la ubicación operativa)
            document.getElementById('reloj-digital').innerHTML = `📍 Sede Pisco, Ica | 📅 ${fechaTxt.charAt(0).toUpperCase() + fechaTxt.slice(1)} | ⏰ ${horas}:${minutos}:${segundos}`;
        }
        
        setInterval(actualizarReloj, 1000);
        actualizarReloj(); // Ejecutar de inmediato

        // 2. Alertas Globales del Sistema
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