<?php
// Despertamos la sesión para recordar quién está usando el sistema
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "models/Asistencia.php";
require_once "models/Bitacora.php";

class AsistenciaController {
    
    public function guardarIngreso() {
        if (isset($_POST['id_trabajador'])) {
            $id_trabajador = $_POST['id_trabajador'];
            $fecha = $_POST['fecha'];
            $hora_ingreso = $_POST['hora_ingreso'];
            $estado = $_POST['estado'];
            $observaciones = $_POST['observaciones'];

            $asistenciaModel = new Asistencia();
            $resultado = $asistenciaModel->registrarIngreso($id_trabajador, $fecha, $hora_ingreso, $estado, $observaciones);

            if ($resultado) {
                // BITÁCORA: Capturamos el ID de forma segura
                $bitacora = new Bitacora();
                $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 1; 
                $bitacora->registrarAccion($id_usuario, "Registró hora de INGRESO ($hora_ingreso) del trabajador ID: $id_trabajador");
                
                header("Location: index.php?vista=asistencias&mensaje=exito_ingreso");
            } else {
                header("Location: index.php?vista=asistencias&mensaje=error");
            }
            exit();
        }
    }

    public function guardarSalida() {
        if (isset($_POST['id_asistencia'])) {
            $id_asistencia = $_POST['id_asistencia'];
            $hora_salida = $_POST['hora_salida'];

            $asistenciaModel = new Asistencia();
            $resultado = $asistenciaModel->registrarSalida($id_asistencia, $hora_salida);

            if ($resultado) {
                // BITÁCORA: Capturamos el ID de forma segura
                $bitacora = new Bitacora();
                $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 1;
                $bitacora->registrarAccion($id_usuario, "Registró hora de SALIDA ($hora_salida) en la asistencia ID: $id_asistencia");

                header("Location: index.php?vista=asistencias&mensaje=exito_salida");
            } else {
                header("Location: index.php?vista=asistencias&mensaje=error");
            }
            exit();
        }
    }
}
?>