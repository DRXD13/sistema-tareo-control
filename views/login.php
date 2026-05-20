<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Tareo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="assets/css/estilos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            /* Fondo corporativo moderno con imagen difuminada y oscurecida */
            background: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.9)),
                        url('https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat fixed;
        }
        .login-card {
            border-radius: 1rem;
            border: none;
            /* Agregamos un borde sutil para separarlo del fondo oscuro */
            border-top: 5px solid #0d6efd; 
        }
        /* Efecto premium en el botón */
        .btn-login {
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.4);
        }
        /* Limpiando los bordes de los inputs */
        .input-group-text {
            background-color: transparent;
            color: #6c757d;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }
        .form-control:focus + .input-group-text,
        .input-group:focus-within .input-group-text {
            color: #0d6efd;
            border-color: #0d6efd;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    
    <div class="card shadow-lg login-card p-4 p-md-5 bg-white" style="width: 28rem;">
        
        <div class="text-center mb-4">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex justify-content-center align-items-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                <i class="bi bi-person-badge fs-1"></i>
            </div>
            <h3 class="text-primary fw-bold mb-1">Tareo Web</h3>
            <p class="text-muted small">Ingresa tus credenciales corporativas</p>
        </div>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="correo" class="form-label fw-bold text-secondary small">Correo Electrónico</label>
                <div class="input-group shadow-sm">
                    <span class="input-group-text border-end-0"><i class="bi bi-envelope-fill"></i></span>
                    <input type="email" class="form-control border-start-0 py-2 bg-light" id="correo" name="correo" placeholder="" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label fw-bold text-secondary small">Contraseña</label>
                <div class="input-group shadow-sm">
                    <span class="input-group-text border-end-0"><i class="bi bi-key-fill"></i></span>
                    <input type="password" class="form-control border-start-0 py-2 bg-light" id="password" name="password" placeholder="••••••••" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-login w-100 fw-bold py-2 fs-5">Ingresar al Sistema</button>
        </form>

        <div class="text-center mt-4 border-top pt-3">
            <a href="recuperar.php" class="text-decoration-none small fw-bold text-secondary hover-primary">¿Olvidaste tu contraseña?</a>
        </div>
        
        <div class="text-center mt-3">
            <small class="text-muted" style="font-size: 0.75rem;">© <?php echo date('Y'); ?> Sistema de Tareo. Todos los derechos reservados.</small>
        </div>
        
    </div>

    <?php if(isset($error)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Acceso Denegado',
                    text: '<?php echo $error; ?>',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            });
        </script>
    <?php endif; ?>

</body>
</html>