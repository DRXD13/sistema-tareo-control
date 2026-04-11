<?php
require_once "config/conexion.php";

class Asistencia {
    private $conexion;
    private $db;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->db = $this->conexion->getConexion();
    }

    public function listarAsistenciasHoy($fecha) {
        $sql = "SELECT a.*, t.nombres, t.apellidos, t.numero_documento 
                FROM asistencias a
                INNER JOIN trabajadores t ON a.id_trabajador = t.id_trabajador
                WHERE a.fecha = :fecha
                ORDER BY a.hora_ingreso DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔒 NUEVA FUNCIÓN: Validar Regla de Negocio RN04 (Cero duplicados)
    public function verificarRegistroPrevio($id_trabajador, $fecha) {
        $sql = "SELECT id_asistencia FROM asistencias WHERE id_trabajador = :id AND fecha = :fecha";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id_trabajador);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        return $stmt->rowCount() > 0; // Retorna true si ya existe
    }

    public function registrarIngreso($id_trabajador, $fecha, $hora_ingreso, $estado, $observaciones) {
        try {
            $sql = "INSERT INTO asistencias (id_trabajador, fecha, hora_ingreso, estado, observaciones) 
                    VALUES (:id, :fecha, :hora, :estado, :obs)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id_trabajador);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':hora', $hora_ingreso);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':obs', $observaciones);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function registrarSalida($id_asistencia, $hora_salida) {
        try {
            $sql = "UPDATE asistencias SET hora_salida = :hora WHERE id_asistencia = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':hora', $hora_salida);
            $stmt->bindParam(':id', $id_asistencia);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>