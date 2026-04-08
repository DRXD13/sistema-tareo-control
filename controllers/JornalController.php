<?php
require_once "models/Jornal.php";

class JornalController {
    public function guardarPlanillaMasiva() {
        if (isset($_POST['fecha_fin']) && isset($_POST['id_trabajador'])) {
            
            $fecha_inicio = $_POST['fecha_inicio'];
            // Usaremos la fecha de fin de la quincena/semana como la 'fecha' de registro
            $fecha = $_POST['fecha_fin']; 
            
            $trabajadores = $_POST['id_trabajador']; 
            $horas = $_POST['total_horas']; 
            $pagos = $_POST['total_pagar']; 

            $jornalModel = new Jornal();
            $exito = true;

            for ($i = 0; $i < count($trabajadores); $i++) {
                // Pasamos los 4 campos exactos de tu base de datos
                $resultado = $jornalModel->guardarPlanilla(
                    $trabajadores[$i], 
                    $fecha, 
                    $pagos[$i],
                    $horas[$i]
                );
                
                if (!$resultado) {
                    $exito = false; 
                }
            }

            if ($exito) {
                header("Location: index.php?vista=jornales&fecha_inicio=$fecha_inicio&fecha_fin=$fecha&mensaje=exito");
            } else {
                header("Location: index.php?vista=jornales&fecha_inicio=$fecha_inicio&fecha_fin=$fecha&mensaje=error");
            }
            exit();
        }
    }
}
?>