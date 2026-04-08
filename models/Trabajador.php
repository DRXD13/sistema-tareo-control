<?php
require_once "config/conexion.php";

class Trabajador {
    private $conexion;
    private $db;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->db = $this->conexion->getConexion();
    }

    public function listarTrabajadores() {
        $sql = "SELECT t.*, a.nombre_area, c.nombre_cargo, cu.nombre_cuadrilla 
                FROM trabajadores t
                INNER JOIN areas a ON t.id_area = a.id_area
                INNER JOIN cargos c ON t.id_cargo = c.id_cargo
                LEFT JOIN cuadrillas cu ON t.id_cuadrilla = cu.id_cuadrilla
                ORDER BY t.id_trabajador DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Agregamos $jornal_diario aquí
    public function registrarTrabajador($id_area, $id_cargo, $id_cuadrilla, $tipo_documento, $numero_documento, $nombres, $apellidos, $jornal_diario, $fecha_ingreso) {
        try {
            $id_cuadrilla = empty($id_cuadrilla) ? null : $id_cuadrilla;

            $sql = "INSERT INTO trabajadores (id_area, id_cargo, id_cuadrilla, tipo_documento, numero_documento, nombres, apellidos, jornal_diario, fecha_ingreso) 
                    VALUES (:id_area, :id_cargo, :id_cuadrilla, :tipo_documento, :numero_documento, :nombres, :apellidos, :jornal_diario, :fecha_ingreso)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_area', $id_area);
            $stmt->bindParam(':id_cargo', $id_cargo);
            $stmt->bindParam(':id_cuadrilla', $id_cuadrilla);
            $stmt->bindParam(':tipo_documento', $tipo_documento);
            $stmt->bindParam(':numero_documento', $numero_documento);
            $stmt->bindParam(':nombres', $nombres);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':jornal_diario', $jornal_diario);
            $stmt->bindParam(':fecha_ingreso', $fecha_ingreso);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function obtenerTrabajador($id_trabajador) {
        $sql = "SELECT * FROM trabajadores WHERE id_trabajador = :id_trabajador";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_trabajador', $id_trabajador);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Agregamos $jornal_diario aquí
    public function actualizarTrabajador($id_trabajador, $id_area, $id_cargo, $id_cuadrilla, $tipo_documento, $numero_documento, $nombres, $apellidos, $jornal_diario, $fecha_ingreso) {
        try {
            $id_cuadrilla = empty($id_cuadrilla) ? null : $id_cuadrilla;
            
            $sql = "UPDATE trabajadores SET 
                    id_area = :id_area, 
                    id_cargo = :id_cargo, 
                    id_cuadrilla = :id_cuadrilla, 
                    tipo_documento = :tipo_documento, 
                    numero_documento = :numero_documento, 
                    nombres = :nombres, 
                    apellidos = :apellidos, 
                    jornal_diario = :jornal_diario,
                    fecha_ingreso = :fecha_ingreso 
                    WHERE id_trabajador = :id_trabajador";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_area', $id_area);
            $stmt->bindParam(':id_cargo', $id_cargo);
            $stmt->bindParam(':id_cuadrilla', $id_cuadrilla);
            $stmt->bindParam(':tipo_documento', $tipo_documento);
            $stmt->bindParam(':numero_documento', $numero_documento);
            $stmt->bindParam(':nombres', $nombres);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':jornal_diario', $jornal_diario);
            $stmt->bindParam(':fecha_ingreso', $fecha_ingreso);
            $stmt->bindParam(':id_trabajador', $id_trabajador);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function cambiarEstado($id_trabajador, $estado_actual) {
        try {
            $nuevo_estado = ($estado_actual == 1) ? 0 : 1; 
            $sql = "UPDATE trabajadores SET estado = :nuevo_estado WHERE id_trabajador = :id_trabajador";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nuevo_estado', $nuevo_estado);
            $stmt->bindParam(':id_trabajador', $id_trabajador);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>