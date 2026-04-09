<?php
require_once "config/conexion.php";

class Reporte {
    private $conexion;
    private $db;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->db = $this->conexion->getConexion();
    }

    // Consolida el total de horas y dinero pagado por trabajador en un rango de fechas
    public function resumenPlanilla($fecha_inicio, $fecha_fin) {
        $sql = "SELECT t.numero_documento, t.nombres, t.apellidos, c.nombre_cargo,
                       SUM(j.horas_trabajadas) as total_horas,
                       SUM(j.monto_calculado) as total_pagado
                FROM jornales j
                INNER JOIN trabajadores t ON j.id_trabajador = t.id_trabajador
                INNER JOIN cargos c ON t.id_cargo = c.id_cargo
                WHERE j.fecha BETWEEN :inicio AND :fin
                GROUP BY t.id_trabajador, t.numero_documento, t.nombres, t.apellidos, c.nombre_cargo
                ORDER BY t.apellidos ASC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':inicio', $fecha_inicio);
        $stmt->bindParam(':fin', $fecha_fin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>