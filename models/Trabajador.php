<?php
require_once "config/conexion.php";

class Trabajador {
    private $conexion;
    private $db;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->db = $this->conexion->getConexion();
    }

    // Listar todos los trabajadores uniendo sus datos con las tablas de áreas, cargos y cuadrillas
    public function listarTrabajadores() {
        // Usamos JOIN para traer los nombres reales y no solo los números de ID
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

    // Registrar un nuevo trabajador
    public function registrarTrabajador($id_area, $id_cargo, $id_cuadrilla, $tipo_documento, $numero_documento, $nombres, $apellidos, $fecha_ingreso) {
        try {
            // Si no seleccionan cuadrilla, la mandamos como NULL (vacía) a la base de datos
            $id_cuadrilla = empty($id_cuadrilla) ? null : $id_cuadrilla;

            $sql = "INSERT INTO trabajadores (id_area, id_cargo, id_cuadrilla, tipo_documento, numero_documento, nombres, apellidos, fecha_ingreso) 
                    VALUES (:id_area, :id_cargo, :id_cuadrilla, :tipo_documento, :numero_documento, :nombres, :apellidos, :fecha_ingreso)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_area', $id_area);
            $stmt->bindParam(':id_cargo', $id_cargo);
            $stmt->bindParam(':id_cuadrilla', $id_cuadrilla);
            $stmt->bindParam(':tipo_documento', $tipo_documento);
            $stmt->bindParam(':numero_documento', $numero_documento);
            $stmt->bindParam(':nombres', $nombres);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->bindParam(':fecha_ingreso', $fecha_ingreso);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    // Obtener datos de un solo trabajador (para editar)
    public function obtenerTrabajador($id_trabajador) {
        $sql = "SELECT * FROM trabajadores WHERE id_trabajador = :id_trabajador";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_trabajador', $id_trabajador);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar datos de un trabajador
    public function actualizarTrabajador($id_trabajador, $id_area, $id_cargo, $id_cuadrilla, $tipo_documento, $numero_documento, $nombres, $apellidos, $fecha_ingreso) {
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
            $stmt->bindParam(':fecha_ingreso', $fecha_ingreso);
            $stmt->bindParam(':id_trabajador', $id_trabajador);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    // Desactivar o Activar trabajador
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