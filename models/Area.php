<?php
// Requerimos la conexión a la base de datos
require_once "config/conexion.php";

class Area {
    private $conexion;
    private $db;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->db = $this->conexion->getConexion();
    }

    // Método para listar todas las áreas registradas
    public function listarAreas() {
        $sql = "SELECT * FROM areas ORDER BY id_area DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para guardar una nueva área
    public function registrarArea($nombre_area) {
        try {
            $sql = "INSERT INTO areas (nombre_area) VALUES (:nombre_area)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre_area', $nombre_area);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
    // Método para obtener una sola área por su ID (para el formulario de editar)
    public function obtenerArea($id_area) {
        $sql = "SELECT * FROM areas WHERE id_area = :id_area";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_area', $id_area);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para actualizar el nombre del área
    public function actualizarArea($id_area, $nombre_area) {
        try {
            $sql = "UPDATE areas SET nombre_area = :nombre_area WHERE id_area = :id_area";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre_area', $nombre_area);
            $stmt->bindParam(':id_area', $id_area);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    // Método para cambiar el estado (Desactivar/Activar)
    public function cambiarEstado($id_area, $estado_actual) {
        try {
            // Si el estado actual es 1 (Activo), lo pasamos a 0. Si es 0, lo pasamos a 1.
            $nuevo_estado = ($estado_actual == 1) ? 0 : 1; 
            
            $sql = "UPDATE areas SET estado = :nuevo_estado WHERE id_area = :id_area";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nuevo_estado', $nuevo_estado);
            $stmt->bindParam(':id_area', $id_area);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>