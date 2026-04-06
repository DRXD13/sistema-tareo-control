<?php
// 1. Arrancamos la sesión de PHP (obligatorio para logueos)
session_start();

// 2. Si el usuario ya está logueado, le mostramos el Dashboard
if (isset($_SESSION['usuario_id'])) {
    require_once "views/dashboard.php";
    exit(); // Muy importante para que se detenga aquí y no cargue el Login
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