<?php
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
            // Si es una falta o permiso, la hora puede venir vacía, le ponemos 00:00:00
            $hora_ingreso = empty($_POST['hora_ingreso']) ? '00:00:00' : $_POST['hora_ingreso']; 
            $estado = $_POST['estado'];
            $observaciones = $_POST['observaciones'];

            $asistenciaModel = new Asistencia();

            // 🛑 APLICAMOS LA REGLA RN04 DEL DOCUMENTO: Bloquear si ya marcó hoy
            if ($asistenciaModel->verificarRegistroPrevio($id_trabajador, $fecha)) {
                header("Location: index.php?vista=asistencias&mensaje=duplicado");
                exit();
            }

            $resultado = $asistenciaModel->registrarIngreso($id_trabajador, $fecha, $hora_ingreso, $estado, $observaciones);

            if ($resultado) {
                $bitacora = new Bitacora();
                $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 1; 
                $bitacora->registrarAccion($id_usuario, "Registró estado de asistencia ($estado) del trabajador ID: $id_trabajador");
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