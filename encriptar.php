<?php
$mi_contrasena = "123456";
$encriptada = password_hash($mi_contrasena, PASSWORD_DEFAULT);

echo "<h3>Tu contraseña original: " . $mi_contrasena . "</h3>";
echo "<h3>Tu contraseña encriptada (Copia esto):</h3>";
echo "<p style='background:#eee; padding:10px; font-family:monospace; font-weight:bold;'>" . $encriptada . "</p>";
?>