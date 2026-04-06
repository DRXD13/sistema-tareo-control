<?php
require_once "config/conexion.php";

class Cargo {
    private $conexion;
    private $db;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->db = $this->conexion->getConexion();
    }

    public function listarCargos() {
        $sql = "SELECT * FROM cargos ORDER BY id_cargo DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarCargo($nombre_cargo) {
        try {
            $sql = "INSERT INTO cargos (nombre_cargo) VALUES (:nombre_cargo)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre_cargo', $nombre_cargo);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function obtenerCargo($id_cargo) {
        $sql = "SELECT * FROM cargos WHERE id_cargo = :id_cargo";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cargo', $id_cargo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarCargo($id_cargo, $nombre_cargo) {
        try {
            $sql = "UPDATE cargos SET nombre_cargo = :nombre_cargo WHERE id_cargo = :id_cargo";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre_cargo', $nombre_cargo);
            $stmt->bindParam(':id_cargo', $id_cargo);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function cambiarEstado($id_cargo, $estado_actual) {
        try {
            $nuevo_estado = ($estado_actual == 1) ? 0 : 1; 
            $sql = "UPDATE cargos SET estado = :nuevo_estado WHERE id_cargo = :id_cargo";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nuevo_estado', $nuevo_estado);
            $stmt->bindParam(':id_cargo', $id_cargo);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>