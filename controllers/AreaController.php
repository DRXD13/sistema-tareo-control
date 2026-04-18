<?php
// Llamamos al Modelo para poder usar la base de datos
require_once "models/Area.php";

class AreaController {
    public function guardarArea() {
        // Verificamos si enviaron el formulario
        if (isset($_POST['nombre_area'])) {
            $nombre_area = trim($_POST['nombre_area']);
            
            // Instanciamos el Modelo y guardamos
            $areaModel = new Area();
            $resultado = $areaModel->registrarArea($nombre_area);

            // Redirigimos disparando la ALERTA ANIMADA
            if ($resultado) {
                header("Location: index.php?vista=areas&alerta=guardado");
            } else {
                header("Location: index.php?vista=areas&alerta=error");
            }
            exit();
        }
    }

    public function actualizarArea() {
        if (isset($_POST['id_area']) && isset($_POST['nombre_area'])) {
            $id_area = $_POST['id_area'];
            $nombre_area = trim($_POST['nombre_area']);
            
            $areaModel = new Area();
            $resultado = $areaModel->actualizarArea($id_area, $nombre_area);

            // Redirigimos disparando la ALERTA ANIMADA de actualización
            if ($resultado) {
                header("Location: index.php?vista=areas&alerta=actualizado");
            } else {
                header("Location: index.php?vista=areas&alerta=error");
            }
            exit();
        }
    }

    public function cambiarEstado() {
        if (isset($_GET['id']) && isset($_GET['estado'])) {
            $id_area = $_GET['id'];
            $estado_actual = $_GET['estado'];
            
            $areaModel = new Area();
            $resultado = $areaModel->cambiarEstado($id_area, $estado_actual);

            // Redirigimos disparando la ALERTA ANIMADA
            header("Location: index.php?vista=areas&alerta=actualizado");
            exit();
        }
    }
}
?>