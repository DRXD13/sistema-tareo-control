<?php
require_once "models/Tareo.php";

class TareoController {
    
    public function guardarTareo() {
        if (isset($_POST['fecha_tareo']) && isset($_POST['id_trabajador'])) {
            
            $fecha = $_POST['fecha_tareo'];
            
            $trabajadores = $_POST['id_trabajador']; 
            $actividades = $_POST['id_actividad']; // Atrapamos las actividades
            $estados = $_POST['estado_asistencia']; 
            $horas = $_POST['horas_trabajadas']; 
            $observaciones = $_POST['observaciones']; 

            $tareoModel = new Tareo();
            $exito = true;

            for ($i = 0; $i < count($trabajadores); $i++) {
                $id_trabajador = $trabajadores[$i];
                $id_actividad = $actividades[$i]; // Pasamos la actividad
                $estado = $estados[$i];
                $hora = $horas[$i];
                $obs = $observaciones[$i];

                $resultado = $tareoModel->registrarAsistencia($fecha, $id_trabajador, $id_actividad, $estado, $hora, $obs);
                
                if (!$resultado) {
                    $exito = false; 
                }
            }

            if ($exito) {
                header("Location: index.php?vista=tareo&fecha=" . $fecha . "&mensaje=exito");
            } else {
                header("Location: index.php?vista=tareo&fecha=" . $fecha . "&mensaje=error");
            }
            exit();
        }
    }
}
?>