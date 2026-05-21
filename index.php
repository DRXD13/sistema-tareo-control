<?php
// 1. Arrancamos la sesión de PHP (obligatorio para logueos)
session_start();

// --- 🛡️ MEJORA 1: CIERRE POR INACTIVIDAD SEGURA (1 MINUTO) ---
$tiempo_limite_inactividad = 60; // 60 segundos = 1 minuto exacto

// 2. Si el usuario ya está logueado, verificamos su actividad y qué acción quiere hacer
if (isset($_SESSION['usuario_id'])) {
    
    // Verificamos si la sesión ha expirado por inactividad
    if (isset($_SESSION['ultimo_acceso'])) {
        $tiempo_transcurrido = time() - $_SESSION['ultimo_acceso'];
        if ($tiempo_transcurrido > $tiempo_limite_inactividad) {
            session_unset();
            session_destroy();
            // Lo mandamos al login con una alerta de que su sesión expiró
            header("Location: index.php?error=expirado");
            exit();
        }
    }
    // Renovamos el temporizador porque el usuario acaba de interactuar con el sistema
    $_SESSION['ultimo_acceso'] = time();

    // Si hay una acción por ejecutar
    if (isset($_GET['accion'])) {
        
        // --- ÁREAS ---
        if (strpos($_GET['accion'], '_area') !== false) {
            require_once "controllers/AreaController.php";
            $controlador = new AreaController();
            if ($_GET['accion'] == 'guardar_area') $controlador->guardarArea();
            if ($_GET['accion'] == 'actualizar_area') $controlador->actualizarArea();
            if ($_GET['accion'] == 'cambiar_estado_area') $controlador->cambiarEstado();
        }
        
        // --- CARGOS ---
        if (strpos($_GET['accion'], '_cargo') !== false) {
            require_once "controllers/CargoController.php";
            $controlador = new CargoController();
            if ($_GET['accion'] == 'guardar_cargo') $controlador->guardarCargo();
            if ($_GET['accion'] == 'actualizar_cargo') $controlador->actualizarCargo();
            if ($_GET['accion'] == 'cambiar_estado_cargo') $controlador->cambiarEstado();
        }

        // --- CUADRILLAS ---
        if (strpos($_GET['accion'], '_cuadrilla') !== false) {
            require_once "controllers/CuadrillaController.php";
            $controlador = new CuadrillaController();
            if ($_GET['accion'] == 'guardar_cuadrilla') $controlador->guardarCuadrilla();
            if ($_GET['accion'] == 'actualizar_cuadrilla') $controlador->actualizarCuadrilla();
            if ($_GET['accion'] == 'cambiar_estado_cuadrilla') $controlador->cambiarEstado();
        }

        // --- TRABAJADORES ---
        if (strpos($_GET['accion'], '_trabajador') !== false) {
            require_once "controllers/TrabajadorController.php";
            $controlador = new TrabajadorController();
            if ($_GET['accion'] == 'guardar_trabajador') $controlador->guardarTrabajador();
            if ($_GET['accion'] == 'actualizar_trabajador') $controlador->actualizarTrabajador();
            if ($_GET['accion'] == 'cambiar_estado_trabajador') $controlador->cambiarEstado();
        }

        // --- TAREO ---
        if (strpos($_GET['accion'], '_tareo') !== false) {
            require_once "controllers/TareoController.php";
            $controlador = new TareoController();
            if ($_GET['accion'] == 'guardar_tareo') $controlador->guardarTareo();
        }

        // --- ACTIVIDADES ---
        if (strpos($_GET['accion'], '_actividad') !== false) {
            require_once "controllers/ActividadController.php";
            $controlador = new ActividadController();
            if ($_GET['accion'] == 'guardar_actividad') $controlador->guardarActividad();
            if ($_GET['accion'] == 'actualizar_actividad') $controlador->actualizarActividad();
            if ($_GET['accion'] == 'cambiar_estado_actividad') $controlador->cambiarEstado();
        }

        // --- JORNALES (PLANILLAS) ---
        if ($_GET['accion'] == 'guardar_planilla') {
            require_once "controllers/JornalController.php";
            $controlador = new JornalController();
            $controlador->guardarPlanillaMasiva();
        }

        // --- ASISTENCIAS ---
        if ($_GET['accion'] == 'guardar_ingreso') {
            require_once "controllers/AsistenciaController.php";
            $controlador = new AsistenciaController();
            $controlador->guardarIngreso();
        }
        if ($_GET['accion'] == 'guardar_salida') {
            require_once "controllers/AsistenciaController.php";
            $controlador = new AsistenciaController();
            $controlador->guardarSalida();
        }

        // --- ACTIVIDADES DIARIAS ---
        if ($_GET['accion'] == 'guardar_actividad_diaria') {
            require_once "controllers/ActividadDiariaController.php";
            $controlador = new ActividadDiariaController();
            $controlador->guardarActividad();
        }

        // --- REPORTES EXCEL ---
        if (isset($_GET['accion']) && $_GET['accion'] == 'exportar_excel') {
            require_once "controllers/ReporteController.php";
            $controlador = new ReporteController();
            $controlador->exportarExcel();
        }
    }

    // Finalmente, mostramos el Dashboard
    require_once "views/dashboard.php";
    exit(); 
}

// 3. Verificamos si el formulario de login fue enviado por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "config/conexion.php";
    $conexion = new Conexion();
    $db = $conexion->getConexion();

    $correo = trim($_POST['correo']);
    $password = $_POST['password'];

    // Buscamos al usuario por su correo
    $sql = "SELECT * FROM usuarios WHERE correo = :correo AND estado = 1";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();
    
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    $login_exitoso = false;

    if ($usuario) {
        // 1. Verificamos si usó su contraseña principal (La de siempre)
        if (password_verify($password, $usuario['password']) || $password === $usuario['password']) {
            $login_exitoso = true;
        } 
        // 2. Si falló la principal, verificamos si usó la Clave Temporal
        elseif (!empty($usuario['clave_temporal']) && password_verify($password, $usuario['clave_temporal'])) {
            $login_exitoso = true;
            
            // Como ya la usó, borramos la clave temporal por seguridad
            $sql_limpiar = "UPDATE usuarios SET clave_temporal = NULL WHERE id_usuario = :id";
            $stmt_limpiar = $db->prepare($sql_limpiar);
            $stmt_limpiar->bindParam(':id', $usuario['id_usuario']);
            $stmt_limpiar->execute();
        }
    }

    if ($login_exitoso) {
        
        // --- 🛡️ MEJORA 2: PREVENCIÓN DE ROBO DE SESIÓN (Session Fixation) ---
        session_regenerate_id(true);

        // Guardamos sus datos en la sesión
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['nombres'] = $usuario['nombres'];
        $_SESSION['id_rol'] = $usuario['id_usuario_rol'] ?? $usuario['id_rol']; // Mantiene tu mapeo de roles
        $_SESSION['ultimo_acceso'] = time(); // Iniciamos el reloj de inactividad
        
        // Recargamos la página para que entre
        header("Location: index.php");
        exit();
    } else {
        // Si falla, creamos un mensaje de error
        $error = "Correo o contraseña incorrectos.";
    }
}

// 4. Capturamos si el sistema expulsó al usuario por inactividad para mostrarle un mensaje
if (isset($_GET['error']) && $_GET['error'] == 'expirado') {
    $error = "Tu sesión ha expirado por inactividad. Vuelve a ingresar.";
}

// 5. Si no está logueado, mostramos la vista del formulario
require_once "views/login.php";
?>