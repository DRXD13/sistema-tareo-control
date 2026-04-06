<?php
require_once "config/conexion.php";

class Cuadrilla {
    private $conexion;
    private $db;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->db = $this->conexion->getConexion();
    }

    public function listarCuadrillas() {
        $sql = "SELECT * FROM cuadrillas ORDER BY id_cuadrilla DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarCuadrilla($nombre_cuadrilla) {
        try {
            $sql = "INSERT INTO cuadrillas (nombre_cuadrilla) VALUES (:nombre_cuadrilla)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre_cuadrilla', $nombre_cuadrilla);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function obtenerCuadrilla($id_cuadrilla) {
        $sql = "SELECT * FROM cuadrillas WHERE id_cuadrilla = :id_cuadrilla";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cuadrilla', $id_cuadrilla);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarCuadrilla($id_cuadrilla, $nombre_cuadrilla) {
        try {
            $sql = "UPDATE cuadrillas SET nombre_cuadrilla = :nombre_cuadrilla WHERE id_cuadrilla = :id_cuadrilla";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre_cuadrilla', $nombre_cuadrilla);
            $stmt->bindParam(':id_cuadrilla', $id_cuadrilla);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function cambiarEstado($id_cuadrilla, $estado_actual) {
        try {
            $nuevo_estado = ($estado_actual == 1) ? 0 : 1; 
            $sql = "UPDATE cuadrillas SET estado = :nuevo_estado WHERE id_cuadrilla = :id_cuadrilla";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nuevo_estado', $nuevo_estado);
            $stmt->bindParam(':id_cuadrilla', $id_cuadrilla);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>