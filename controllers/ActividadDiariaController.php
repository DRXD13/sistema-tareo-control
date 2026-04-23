<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "models/ActividadDiaria.php";
require_once "models/Bitacora.php";

class ActividadDiariaController {
    
    public function guardarActividad() {
        if (isset($_POST['id_trabajador'])) {
            $id_trabajador = $_POST['id_trabajador'];
            $fecha = $_POST['fecha'];
            $descripcion = trim($_POST['descripcion_actividad']);
            $observaciones = trim($_POST['observaciones_supervisor']);

            $actividadModel = new ActividadDiaria();
            $resultado = $actividadModel->registrarActividad($id_trabajador, $fecha, $descripcion, $observaciones);

            if ($resultado) {
                // BITÁCORA: Registramos quién guardó este reporte
                $bitacora = new Bitacora();
                $id_usuario = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 1;
                $bitacora->registrarAccion($id_usuario, "Registró actividad diaria para el trabajador ID: $id_trabajador");
                
                // Redirigimos disparando la ALERTA ANIMADA
                header("Location: index.php?vista=actividades_diarias&fecha=$fecha&alerta=guardado");
            } else {
                header("Location: index.php?vista=actividades_diarias&fecha=$fecha&alerta=error");
            }
            exit();
        }
    }
}
?>