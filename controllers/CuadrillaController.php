<?php
require_once "models/Cuadrilla.php";

class CuadrillaController {
    public function guardarCuadrilla() {
        if (isset($_POST['nombre_cuadrilla'])) {
            $nombre_cuadrilla = trim($_POST['nombre_cuadrilla']);
            $cuadrillaModel = new Cuadrilla();
            $resultado = $cuadrillaModel->registrarCuadrilla($nombre_cuadrilla);

            if ($resultado) {
                header("Location: index.php?vista=cuadrillas&mensaje=exito");
            } else {
                header("Location: index.php?vista=cuadrillas&mensaje=error");
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

            if ($resultado) {
                header("Location: index.php?vista=cuadrillas&mensaje=exito_editar");
            } else {
                header("Location: index.php?vista=cuadrillas&mensaje=error");
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

            header("Location: index.php?vista=cuadrillas&mensaje=exito_estado");
            exit();
        }
    }
}
?>