<?php
// Llamamos a tu archivo de conexión
require_once "config/conexion.php";

$conexion = new Conexion();
$db = $conexion->getConexion();

// Los datos de nuestro primer super administrador
$id_rol = 1; // 1 es el ID del rol Administrador que creamos en SQL
$nombres = "Dangelo";
$apellidos = "Retis";
$correo = "admin@empresa.com";

// Encriptamos la contraseña "admin123" usando el estándar más seguro de PHP
$password_encriptada = password_hash("admin123", PASSWORD_DEFAULT);

try {
    // Preparamos la orden para insertar en la base de datos
    $sql = "INSERT INTO usuarios (id_rol, nombres, apellidos, correo, password) 
            VALUES (:id_rol, :nombres, :apellidos, :correo, :password)";
    
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id_rol', $id_rol);
    $stmt->bindParam(':nombres', $nombres);
    $stmt->bindParam(':apellidos', $apellidos);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':password', $password_encriptada);
    
    $stmt->execute();
    echo "<h1>¡Administrador creado con éxito!</h1>";
    echo "<p>Correo: <b>admin@empresa.com</b></p>";
    echo "<p>Contraseña: <b>admin123</b></p>";
    
} catch (Exception $e) {
    echo "Hubo un error o el usuario ya existe: " . $e->getMessage();
}
?>