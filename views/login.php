<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Tareo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    
    <div class="card shadow p-4" style="width: 25rem;">
        <div class="text-center mb-4">
            <h3 class="text-primary fw-bold">Sistema de Tareo</h3>
            <p class="text-muted">Ingresa tus credenciales para continuar</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger text-center fw-bold">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" placeholder="ejemplo@empresa.com" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="********" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold">Ingresar al Sistema</button>
        </form>

        <div class="text-center mt-3">
            <a href="recuperar.php" class="text-decoration-none small fw-bold">¿Olvidaste tu contraseña?</a>
        </div>
        
    </div>

</body>
</html>