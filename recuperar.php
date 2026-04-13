<?php
session_start();
require_once "config/conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['correo'])) {
    $correo = trim($_POST['correo']);
    
    $conexion = new Conexion();
    $db = $conexion->getConexion();

    // 1. Verificamos si el correo existe
    $sql = "SELECT id_usuario, nombres FROM usuarios WHERE correo = :correo AND estado = 1";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // 2. Generamos una clave temporal aleatoria (Ej: Tareo8451)
        $clave_temporal = "Tareo" . rand(1000, 9999);
        $clave_encriptada = password_hash($clave_temporal, PASSWORD_DEFAULT);

        // 3. Actualizamos la base de datos
        $sql_update = "UPDATE usuarios SET password = :pass WHERE correo = :correo";
        $stmt_upd = $db->prepare($sql_update);
        $stmt_upd->bindParam(':pass', $clave_encriptada);
        $stmt_upd->bindParam(':correo', $correo);
        
        if ($stmt_upd->execute()) {
            $mensaje = '
            <div class="alert alert-success text-center shadow-sm">
                <h5 class="alert-heading fw-bold mb-2">✅ ¡Identidad Verificada, ' . $usuario['nombres'] . '!</h5>
                <p class="mb-2">Tu contraseña ha sido restablecida exitosamente.</p>
                <hr>
                <p class="mb-1">Tu nueva clave temporal es:</p>
                <h3 class="fw-bold text-dark bg-white p-2 border rounded d-inline-block">' . $clave_temporal . '</h3>
                <p class="small mt-2 text-muted">Por favor, cópiala, inicia sesión y cámbiala lo antes posible por seguridad.</p>
                <a href="index.php" class="btn btn-sm btn-success fw-bold mt-2">Ir al Login</a>
            </div>';
        }
    } else {
        $mensaje = '
        <div class="alert alert-danger text-center shadow-sm">
            <strong>❌ Error:</strong> El correo ingresado no existe en nuestro sistema o el usuario está inactivo.
        </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Sistema de Tareo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-card { width: 100%; max-width: 400px; border-radius: 10px; border-top: 5px solid #0d6efd; }
    </style>
</head>
<body>

    <div class="card shadow login-card">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <h2 class="text-primary m-0">⚙️ Tareo Web</h2>
                <p class="text-muted small mt-1">Municipalidad Distrital</p>
            </div>

            <h5 class="fw-bold text-center mb-3">Recuperar Contraseña</h5>
            
            <?php echo $mensaje; ?>

            <?php if (empty($mensaje) || strpos($mensaje, 'Error') !== false): ?>
                <p class="text-muted small text-center mb-4">Ingresa tu correo electrónico registrado y generaremos una clave temporal para ti.</p>
                
                <form action="recuperar.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary small">Correo Electrónico</label>
                        <input type="email" name="correo" class="form-control border-primary" placeholder="ejemplo@empresa.com" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Restablecer Contraseña</button>
                </form>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="index.php" class="text-decoration-none small text-secondary fw-bold">⬅️ Volver al Inicio de Sesión</a>
            </div>
        </div>
    </div>

</body>
</html>