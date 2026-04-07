<?php
require_once "config/conexion.php";

class Actividad {
    private $conexion;
    private $db;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->db = $this->conexion->getConexion();
    }

    public function listarActividades() {
        $sql = "SELECT * FROM actividades ORDER BY id_actividad DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarActividad($nombre_actividad) {
        try {
            $sql = "INSERT INTO actividades (nombre_actividad) VALUES (:nombre_actividad)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre_actividad', $nombre_actividad);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function obtenerActividad($id_actividad) {
        $sql = "SELECT * FROM actividades WHERE id_actividad = :id_actividad";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_actividad', $id_actividad);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarActividad($id_actividad, $nombre_actividad) {
        try {
            $sql = "UPDATE actividades SET nombre_actividad = :nombre_actividad WHERE id_actividad = :id_actividad";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre_actividad', $nombre_actividad);
            $stmt->bindParam(':id_actividad', $id_actividad);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function cambiarEstado($id_actividad, $estado_actual) {
        try {
            $nuevo_estado = ($estado_actual == 1) ? 0 : 1; 
            $sql = "UPDATE actividades SET estado = :nuevo_estado WHERE id_actividad = :id_actividad";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nuevo_estado', $nuevo_estado);
            $stmt->bindParam(':id_actividad', $id_actividad);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>