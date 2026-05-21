<?php
session_start();
require_once "config/conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['correo'])) {
    $correo = trim($_POST['correo']);
    
    $conexion = new Conexion();
    $db = $conexion->getConexion();

    // 🛠️ AUTO-SANACIÓN DE BASE DE DATOS (El sistema crea la columna solo si no existe)
    $check_columna = $db->query("SHOW COLUMNS FROM usuarios LIKE 'clave_temporal'");
    if ($check_columna->rowCount() == 0) {
        $db->query("ALTER TABLE usuarios ADD clave_temporal VARCHAR(255) NULL AFTER password");
    }

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

        // 3. Actualizamos la base de datos (Guardamos en clave_temporal, conservando el password original)
        $sql_update = "UPDATE usuarios SET clave_temporal = :pass WHERE correo = :correo";
        $stmt_upd = $db->prepare($sql_update);
        $stmt_upd->bindParam(':pass', $clave_encriptada);
        $stmt_upd->bindParam(':correo', $correo);
        
        if ($stmt_upd->execute()) {
            // Diseño premium para el mensaje de éxito
            $mensaje = '
            <div class="alert alert-success text-center shadow-sm p-4 rounded-3 mb-0 border-0" style="position: relative; z-index: 50;">
                <i class="bi bi-check-circle-fill fs-1 text-success mb-2 d-block"></i>
                <h5 class="alert-heading fw-bold mb-2">¡Identidad Verificada!</h5>
                <p class="mb-2 small">Hola <b>' . $usuario['nombres'] . '</b>, tu contraseña ha sido restablecida.</p>
                <hr>
                <p class="mb-2 small fw-bold text-secondary">Tu nueva clave temporal es:</p>
                <h3 class="fw-bold text-primary bg-light p-2 border border-primary rounded d-inline-block shadow-sm" style="user-select: all;">' . $clave_temporal . '</h3>
                <p class="small mt-3 text-muted" style="font-size: 0.8rem;">Por favor, cópiala e inicia sesión.</p>
                <a href="index.php" class="btn btn-success w-100 fw-bold py-2 mt-2 shadow-sm">Ir al Login</a>
            </div>';
        }
    } else {
        // Diseño premium para el mensaje de error
        $mensaje = '
        <div class="alert alert-danger text-center shadow-sm fw-bold small py-3 border-0">
            <i class="bi bi-x-circle-fill me-1"></i> Error: El correo ingresado no existe en nuestro sistema.
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="assets/css/estilos.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.9)),
                        url('https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat fixed;
        }
        .login-card {
            border-radius: 1rem;
            border: none;
            border-top: 5px solid #0d6efd; 
            position: relative;
            z-index: 10;
        }
        .btn-login {
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.4);
        }
        .input-group-text {
            background-color: transparent;
            color: #6c757d;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    
    <div class="card shadow-lg login-card p-4 p-md-5 bg-white" style="width: 28rem;">
        
        <div class="text-center mb-4">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex justify-content-center align-items-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                <i class="bi bi-shield-lock-fill fs-1"></i>
            </div>
            <h3 class="text-primary fw-bold mb-1">Recuperar Acceso</h3>
            <p class="text-muted small">Ingresa tu correo para restablecer tu contraseña</p>
        </div>

        <?php echo $mensaje; ?>

        <?php if (empty($mensaje) || strpos($mensaje, 'Error') !== false): ?>
        <form action="" method="POST" style="position: relative; z-index: 20;">
            <div class="mb-4 mt-3">
                <label for="correo" class="form-label fw-bold text-secondary small">Correo Electrónico Registrado</label>
                <div class="input-group shadow-sm">
                    <span class="input-group-text border-end-0"><i class="bi bi-envelope-fill"></i></span>
                    <input type="email" class="form-control border-start-0 py-2 bg-light" id="correo" name="correo" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-login w-100 fw-bold py-2 fs-5">Enviar Instrucciones</button>
        </form>
        <?php endif; ?>

        <div class="text-center mt-4 border-top pt-3">
            <a href="index.php" class="text-decoration-none small fw-bold text-secondary hover-primary">
                <i class="bi bi-arrow-left me-1"></i> Volver al Inicio de Sesión
            </a>
        </div>
        
    </div>

</body>
</html>