<?php
require_once "config/conexion.php";

class Jornal {
    private $conexion;
    private $db;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->db = $this->conexion->getConexion();
    }

    public function calcularJornales($fecha_inicio, $fecha_fin) {
        $sql = "SELECT t.id_trabajador, t.numero_documento, t.nombres, t.apellidos, t.jornal_diario,
                       SUM(td.horas_trabajadas) as total_horas,
                       (t.jornal_diario / 8) * SUM(td.horas_trabajadas) as total_pagar
                FROM trabajadores t
                INNER JOIN tareo_diario td ON t.id_trabajador = td.id_trabajador
                WHERE td.fecha BETWEEN :fecha_inicio AND :fecha_fin
                GROUP BY t.id_trabajador, t.numero_documento, t.nombres, t.apellidos, t.jornal_diario
                ORDER BY t.apellidos ASC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':fecha_fin', $fecha_fin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Adaptado a tu tabla original 'jornales'
    public function guardarPlanilla($id_trabajador, $fecha, $monto_calculado, $horas_trabajadas) {
        try {
            // Verificamos si ya se guardó para evitar duplicados en la misma fecha
            $sqlCheck = "SELECT id_jornal FROM jornales WHERE fecha = :fecha AND id_trabajador = :id_trabajador";
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->bindParam(':fecha', $fecha);
            $stmtCheck->bindParam(':id_trabajador', $id_trabajador);
            $stmtCheck->execute();

            if ($stmtCheck->rowCount() == 0) {
                // Usamos exclusivamente tus campos
                $sql = "INSERT INTO jornales (id_trabajador, fecha, monto_calculado, horas_trabajadas) 
                        VALUES (:id_trabajador, :fecha, :monto_calculado, :horas_trabajadas)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id_trabajador', $id_trabajador);
                $stmt->bindParam(':fecha', $fecha);
                $stmt->bindParam(':monto_calculado', $monto_calculado);
                $stmt->bindParam(':horas_trabajadas', $horas_trabajadas);
                return $stmt->execute();
            }
            return true; 
        } catch (Exception $e) {
            return false;
        }
    }
}
?>