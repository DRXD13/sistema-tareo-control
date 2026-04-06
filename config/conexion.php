<?php
class Conexion {
    private $host = "localhost";
    private $user = "root"; // Usuario por defecto de XAMPP
    private $password = ""; // Contraseña por defecto de XAMPP (vacía)
    private $db = "sistema_tareo_control";
    private $conect;

    public function __construct() {
        // Configuramos la cadena de conexión asegurando el soporte para tildes y ñ (utf8mb4)
        $connectionString = "mysql:host=".$this->host.";dbname=".$this->db.";charset=utf8mb4";
        
        try {
            $this->conect = new PDO($connectionString, $this->user, $this->password);
            $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Si quieres probar que conecta, puedes descomentar la siguiente línea temporalmente:
            // echo "Conexión exitosa a la base de datos";
        } catch (Exception $e) {
            $this->conect = 'Error de conexión';
            echo "ERROR: " . $e->getMessage();
        }
    }

    public function getConexion() {
        return $this->conect;
    }
}
?>