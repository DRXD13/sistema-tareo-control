<?php
require_once "config/conexion.php";

class Jornal {
    private $conexion;
    private $db;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->db = $this->conexion->getConexion();
    }

    // Método para calcular cuánto pagarle a cada trabajador en un rango de fechas
    public function calcularJornales($fecha_inicio, $fecha_fin) {
        // La matemática: (jornal_diario / 8 horas) = pago por hora. 
        // Luego lo multiplicamos por la suma total de horas trabajadas en esos días.
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
}
?>