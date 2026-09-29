<?php
class Conexion {
    // En producción (Render) los datos vienen de variables de entorno;
    // en local se usan los valores por defecto de XAMPP.
    private $host;
    private $port;
    private $user;
    private $password;
    private $db;
    private $conect;

    public function __construct() {
        $this->host     = getenv('DB_HOST') ?: "localhost";
        $this->port     = getenv('DB_PORT') ?: "3306";
        $this->user     = getenv('DB_USER') ?: "root";
        $this->password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : "";
        $this->db       = getenv('DB_NAME') ?: "sistema_tareo_control";

        // Configuramos la cadena de conexión asegurando el soporte para tildes y ñ (utf8mb4)
        $connectionString = "mysql:host=".$this->host.";port=".$this->port.";dbname=".$this->db.";charset=utf8mb4";

        $opciones = [];
        // Los proveedores MySQL en la nube (Aiven, TiDB...) exigen conexión SSL
        if (getenv('DB_SSL') === 'true') {
            $opciones[PDO::MYSQL_ATTR_SSL_CA] = getenv('DB_SSL_CA') ?: '/etc/ssl/certs/ca-certificates.crt';
            $opciones[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        }

        try {
            $this->conect = new PDO($connectionString, $this->user, $this->password, $opciones);
            $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
