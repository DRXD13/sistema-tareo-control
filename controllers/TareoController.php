<?php
// Llamamos al modelo
require_once "models/Tareo.php";

class TareoController {
    
    public function guardarTareo() {
        // Verificamos que se haya enviado la fecha y la lista de trabajadores
        if (isset($_POST['fecha_tareo']) && isset($_POST['id_trabajador'])) {
            
            $fecha = $_POST['fecha_tareo'];
            
            // Recibimos los arreglos (arrays) con los datos de todos los trabajadores
            $trabajadores = $_POST['id_trabajador']; 
            $estados = $_POST['estado_asistencia']; 
            $horas = $_POST['horas_trabajadas']; 
            $observaciones = $_POST['observaciones']; 

            $tareoModel = new Tareo();
            $exito = true;

            // Recorremos la lista completa usando un bucle
            for ($i = 0; $i < count($trabajadores); $i++) {
                $id_trabajador = $trabajadores[$i];
                $estado = $estados[$i];
                $hora = $horas[$i];
                $obs = $observaciones[$i];

                // Guardamos la asistencia de este trabajador específico
                $resultado = $tareoModel->registrarAsistencia($fecha, $id_trabajador, $estado, $hora, $obs);
                
                // Si ocurre un error al guardar uno, lo registramos, pero el bucle sigue
                if (!$resultado) {
                    $exito = false; 
                }
            }

            // Redireccionamos según el resultado final
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