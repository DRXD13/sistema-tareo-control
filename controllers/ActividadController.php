<?php
require_once "models/Actividad.php";

class ActividadController {
    public function guardarActividad() {
        if (isset($_POST['nombre_actividad'])) {
            $nombre_actividad = trim($_POST['nombre_actividad']);
            $actividadModel = new Actividad();
            $resultado = $actividadModel->registrarActividad($nombre_actividad);

            if ($resultado) {
                header("Location: index.php?vista=actividades&mensaje=exito");
            } else {
                header("Location: index.php?vista=actividades&mensaje=error");
            }
            exit();
        }
    }

    public function actualizarActividad() {
        if (isset($_POST['id_actividad']) && isset($_POST['nombre_actividad'])) {
            $id_actividad = $_POST['id_actividad'];
            $nombre_actividad = trim($_POST['nombre_actividad']);
            
            $actividadModel = new Actividad();
            $resultado = $actividadModel->actualizarActividad($id_actividad, $nombre_actividad);

            if ($resultado) {
                header("Location: index.php?vista=actividades&mensaje=exito_editar");
            } else {
                header("Location: index.php?vista=actividades&mensaje=error");
            }
            exit();
        }
    }

    public function cambiarEstado() {
        if (isset($_GET['id']) && isset($_GET['estado'])) {
            $id_actividad = $_GET['id'];
            $estado_actual = $_GET['estado'];
            
            $actividadModel = new Actividad();
            $actividadModel->cambiarEstado($id_actividad, $estado_actual);

            header("Location: index.php?vista=actividades&mensaje=exito_estado");
            exit();
        }
    }
}
?>