<?php
require_once "config/conexion.php";

class Tareo {
    private $conexion;
    private $db;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->db = $this->conexion->getConexion();
    }

    public function listarTareoPorFecha($fecha) {
        $sql = "SELECT td.*, t.nombres, t.apellidos, t.numero_documento 
                FROM tareo_diario td
                INNER JOIN trabajadores t ON td.id_trabajador = t.id_trabajador
                WHERE td.fecha = :fecha
                ORDER BY t.apellidos ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Se agrega el parámetro $id_actividad
    public function registrarAsistencia($fecha, $id_trabajador, $id_actividad, $estado_asistencia, $horas_trabajadas, $observaciones) {
        try {
            $sqlCheck = "SELECT id_tareo FROM tareo_diario WHERE fecha = :fecha AND id_trabajador = :id_trabajador";
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->bindParam(':fecha', $fecha);
            $stmtCheck->bindParam(':id_trabajador', $id_trabajador);
            $stmtCheck->execute();
            
            // Si la actividad viene vacía, se guarda como nulo
            $actividad_val = empty($id_actividad) ? null : $id_actividad;

            if ($stmtCheck->rowCount() > 0) {
                $sql = "UPDATE tareo_diario SET 
                        id_actividad = :id_actividad,
                        estado_asistencia = :estado_asistencia, 
                        horas_trabajadas = :horas_trabajadas, 
                        observaciones = :observaciones 
                        WHERE fecha = :fecha AND id_trabajador = :id_trabajador";
            } else {
                $sql = "INSERT INTO tareo_diario (fecha, id_trabajador, id_actividad, estado_asistencia, horas_trabajadas, observaciones) 
                        VALUES (:fecha, :id_trabajador, :id_actividad, :estado_asistencia, :horas_trabajadas, :observaciones)";
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':id_trabajador', $id_trabajador);
            $stmt->bindParam(':id_actividad', $actividad_val);
            $stmt->bindParam(':estado_asistencia', $estado_asistencia);
            $stmt->bindParam(':horas_trabajadas', $horas_trabajadas);
            $stmt->bindParam(':observaciones', $observaciones);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>