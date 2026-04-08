<?php
require_once "config/conexion.php";

class ActividadDiaria {
    private $conexion;
    private $db;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->db = $this->conexion->getConexion();
    }

    public function listarPorFecha($fecha) {
        $sql = "SELECT ad.*, t.nombres, t.apellidos, t.numero_documento 
                FROM actividades_diarias ad
                INNER JOIN trabajadores t ON ad.id_trabajador = t.id_trabajador
                WHERE ad.fecha = :fecha
                ORDER BY ad.id_actividad DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarActividad($id_trabajador, $fecha, $descripcion, $observaciones) {
        try {
            $sql = "INSERT INTO actividades_diarias (id_trabajador, fecha, descripcion_actividad, observaciones_supervisor) 
                    VALUES (:id_trabajador, :fecha, :descripcion, :observaciones)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_trabajador', $id_trabajador);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':observaciones', $observaciones);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>