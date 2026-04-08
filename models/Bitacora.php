<?php
require_once "config/conexion.php";

class Bitacora {
    private $conexion;
    private $db;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->db = $this->conexion->getConexion();
    }

    // Listar todo el historial de acciones para la pantalla del Admin
    public function listarBitacora() {
        $sql = "SELECT b.id_bitacora, b.accion, b.fecha_hora, u.nombres, u.apellidos, r.nombre_rol
                FROM bitacora b
                INNER JOIN usuarios u ON b.id_usuario = u.id_usuario
                INNER JOIN roles r ON u.id_rol = r.id_rol
                ORDER BY b.fecha_hora DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Esta función la usaremos en el futuro para guardar cosas 
    // (Ejemplo: "El usuario Dangelo guardó un nuevo trabajador")
    public function registrarAccion($id_usuario, $accion) {
        try {
            $sql = "INSERT INTO bitacora (id_usuario, accion) VALUES (:id_usuario, :accion)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':accion', $accion);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>