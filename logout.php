<?php
// 1. Retomamos la sesión actual
session_start();

// 2. Destruimos todas las variables de sesión
session_destroy();

// 3. Redirigimos al usuario de vuelta a la página principal (Login)
header("Location: index.php");
exit();
?>