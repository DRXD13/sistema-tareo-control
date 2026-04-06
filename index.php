<?php
// 1. Arrancamos la sesión de PHP (obligatorio para logueos)
session_start();

// 2. Si el usuario ya está logueado, verificamos qué acción quiere hacer
if (isset($_SESSION['usuario_id'])) {
    
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

    // Si el usuario existe y la contraseña (encriptada) coincide
    if ($usuario && password_verify($password, $usuario['password'])) {
        // Guardamos sus datos en la sesión
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['nombres'] = $usuario['nombres'];
        $_SESSION['id_rol'] = $usuario['id_rol'];
        
        // Recargamos la página para que entre
        header("Location: index.php");
        exit();
    } else {
        // Si falla, creamos un mensaje de error
        $error = "Correo o contraseña incorrectos.";
    }
}

// 4. Si no está logueado, mostramos la vista del formulario
require_once "views/login.php";
?>