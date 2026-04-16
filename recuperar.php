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
            <div class="alert alert-success text-center shadow-sm p-4 rounded-3">
                <h5 class="alert-heading fw-bold mb-2">✅ ¡Identidad Verificada!</h5>
                <p class="mb-2 small">Hola <b>' . $usuario['nombres'] . '</b>, tu contraseña ha sido restablecida.</p>
                <hr>
                <p class="mb-2 small fw-bold text-secondary">Tu nueva clave temporal es:</p>
                <h3 class="fw-bold text-primary bg-white p-2 border border-primary rounded d-inline-block shadow-sm">' . $clave_temporal . '</h3>
                <p class="small mt-3 text-muted" style="font-size: 0.8rem;">Por favor, cópiala e inicia sesión.</p>
                <a href="index.php" class="btn btn-success w-100 fw-bold py-2 mt-2 shadow-sm">Ir al Login</a>
            </div>';
        }
    } else {
        $mensaje = '
        <div class="alert alert-danger text-center shadow-sm fw-bold small py-3">
            ❌ Error: El correo ingresado no existe en nuestro sistema o el usuario está inactivo.
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
    <link href="assets/css/estilos.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

    <div class="card shadow-lg login-card p-4" style="width: 26rem;">
        <div class="card-body p-2">
            
            <div class="text-center mb-4">
                <div class="display-4 text-primary mb-2">⚙️</div>
                <h3 class="text-primary fw-bold">Sistema de Tareo</h3>
                <p class="text-muted small">Recuperación de Acceso</p>
            </div>
            
            <?php echo $mensaje; ?>

            <?php if (empty($mensaje) || strpos($mensaje, 'Error') !== false): ?>
                <p class="text-muted small text-center mb-4">Ingresa tu correo electrónico registrado y generaremos una clave temporal para ti.</p>
                
                <form action="recuperar.php" method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary small">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">📧</span>
                            <input type="email" name="correo" class="form-control border-start-0" placeholder="ejemplo@empresa.com" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">Restablecer Contraseña</button>
                </form>
            <?php endif; ?>

            <div class="text-center mt-4 border-top pt-3">
                <a href="index.php" class="text-decoration-none small text-secondary fw-bold">⬅️ Volver al Inicio de Sesión</a>
            </div>
            
        </div>
    </div>

</body>
</html>