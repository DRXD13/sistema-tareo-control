<?php
// Aseguramos la sesión para la bitácora
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "models/Reporte.php";
require_once "models/Bitacora.php";

class ReporteController {
    
    public function exportarExcel() {
        if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_fin'])) {
            $fecha_inicio = $_GET['fecha_inicio'];
            $fecha_fin = $_GET['fecha_fin'];

            $reporteModel = new Reporte();
            $listaReporte = $reporteModel->resumenPlanilla($fecha_inicio, $fecha_fin);

            // 1. Auditoría: Registramos la acción en la bitácora
            $bitacora = new Bitacora();
            $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 1;
            $bitacora->registrarAccion($id_usuario, "Exportó reporte a EXCEL (Rango: $fecha_inicio al $fecha_fin)");

            // 2. Cabeceras para forzar la descarga en Excel
            header("Content-Type: application/vnd.ms-excel; charset=utf-8");
            header("Content-Disposition: attachment; filename=Reporte_Planillas_".$fecha_inicio."_al_".$fecha_fin.".xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            // 3. Forzamos codificación UTF-8 para las tildes y las 'ñ'
            echo "\xEF\xBB\xBF"; 

            // 4. Dibujamos la tabla que irá dentro del Excel
            echo '<table border="1">';
            echo '<tr><th colspan="5" style="background-color:#212529; color:white; font-size:16px; padding:10px;">Reporte de Planillas: '.$fecha_inicio.' al '.$fecha_fin.'</th></tr>';
            echo '<tr>
                    <th style="background-color:#e9ecef; font-weight:bold;">Trabajador</th>
                    <th style="background-color:#e9ecef; font-weight:bold;">Documento</th>
                    <th style="background-color:#e9ecef; font-weight:bold;">Cargo</th>
                    <th style="background-color:#e9ecef; font-weight:bold;">Total Horas</th>
                    <th style="background-color:#e9ecef; font-weight:bold;">Total Pagado (S/)</th>
                  </tr>';

            $gran_total_dinero = 0;
            $gran_total_horas = 0;

            foreach ($listaReporte as $r) {
                $gran_total_dinero += $r['total_pagado'];
                $gran_total_horas += $r['total_horas'];
                echo '<tr>';
                echo '<td>' . $r['nombres'] . ' ' . $r['apellidos'] . '</td>';
                echo '<td>' . $r['numero_documento'] . '</td>';
                echo '<td>' . $r['nombre_cargo'] . '</td>';
                echo '<td>' . $r['total_horas'] . '</td>';
                echo '<td>' . number_format($r['total_pagado'], 2) . '</td>';
                echo '</tr>';
            }

            echo '<tr>
                    <td colspan="3" style="text-align:right; font-weight:bold;">GRAN TOTAL:</td>
                    <td style="font-weight:bold;">' . $gran_total_horas . '</td>
                    <td style="font-weight:bold;">S/ ' . number_format($gran_total_dinero, 2) . '</td>
                  </tr>';
            echo '</table>';
            
            // Matamos el proceso para que no imprima el resto de la página web en el Excel
            exit(); 
        }
    }
}
?>