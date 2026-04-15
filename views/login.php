<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Tareo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/estilos.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    
    <div class="card shadow-lg login-card p-4" style="width: 26rem;">
        <div class="text-center mb-4">
            <div class="display-4 text-primary mb-2">⚙️</div>
            <h3 class="text-primary fw-bold">Sistema de Tareo</h3>
            <p class="text-muted small">Ingresa tus credenciales para continuar</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger text-center fw-bold py-2 small">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="correo" class="form-label fw-bold text-secondary small">Correo Electrónico</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">📧</span>
                    <input type="email" class="form-control border-start-0" id="correo" name="correo" placeholder="ejemplo@empresa.com" required>
                </div>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label fw-bold text-secondary small">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">🔑</span>
                    <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="********" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">Ingresar al Sistema</button>
        </form>

        <div class="text-center mt-4 border-top pt-3">
            <a href="recuperar.php" class="text-decoration-none small fw-bold text-primary">¿Olvidaste tu contraseña?</a>
        </div>
        
    </div>

</body>
</html>