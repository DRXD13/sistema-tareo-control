<?php
require_once "models/Cuadrilla.php";

class CuadrillaController {
    public function guardarCuadrilla() {
        if (isset($_POST['nombre_cuadrilla'])) {
            $nombre_cuadrilla = trim($_POST['nombre_cuadrilla']);
            $cuadrillaModel = new Cuadrilla();
            $resultado = $cuadrillaModel->registrarCuadrilla($nombre_cuadrilla);

            // Redirigimos disparando la ALERTA ANIMADA
            if ($resultado) {
                header("Location: index.php?vista=cuadrillas&alerta=guardado");
            } else {
                header("Location: index.php?vista=cuadrillas&alerta=error");
            }
            exit();
        }
    }

    public function actualizarCuadrilla() {
        if (isset($_POST['id_cuadrilla']) && isset($_POST['nombre_cuadrilla'])) {
            $id_cuadrilla = $_POST['id_cuadrilla'];
            $nombre_cuadrilla = trim($_POST['nombre_cuadrilla']);
            
            $cuadrillaModel = new Cuadrilla();
            $resultado = $cuadrillaModel->actualizarCuadrilla($id_cuadrilla, $nombre_cuadrilla);

            // Redirigimos disparando la ALERTA ANIMADA de actualización
            if ($resultado) {
                header("Location: index.php?vista=cuadrillas&alerta=actualizado");
            } else {
                header("Location: index.php?vista=cuadrillas&alerta=error");
            }
            exit();
        }
    }

    public function cambiarEstado() {
        if (isset($_GET['id']) && isset($_GET['estado'])) {
            $id_cuadrilla = $_GET['id'];
            $estado_actual = $_GET['estado'];
            
            $cuadrillaModel = new Cuadrilla();
            $cuadrillaModel->cambiarEstado($id_cuadrilla, $estado_actual);

            // Redirigimos disparando la ALERTA ANIMADA
            header("Location: index.php?vista=cuadrillas&alerta=actualizado");
            exit();
        }
    }
}
?>