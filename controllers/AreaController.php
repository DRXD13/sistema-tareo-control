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

            // Redirigimos de vuelta a la pantalla de áreas
            if ($resultado) {
                header("Location: index.php?vista=areas&mensaje=exito");
            } else {
                header("Location: index.php?vista=areas&mensaje=error");
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

            if ($resultado) {
                header("Location: index.php?vista=areas&mensaje=exito_editar");
            } else {
                header("Location: index.php?vista=areas&mensaje=error");
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

            header("Location: index.php?vista=areas&mensaje=exito_estado");
            exit();
        }
    }
}
?>