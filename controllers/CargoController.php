<?php
require_once "models/Cargo.php";

class CargoController {
    public function guardarCargo() {
        if (isset($_POST['nombre_cargo'])) {
            $nombre_cargo = trim($_POST['nombre_cargo']);
            $cargoModel = new Cargo();
            $resultado = $cargoModel->registrarCargo($nombre_cargo);

            // Redirigimos disparando la ALERTA ANIMADA
            if ($resultado) {
                header("Location: index.php?vista=cargos&alerta=guardado");
            } else {
                header("Location: index.php?vista=cargos&alerta=error");
            }
            exit();
        }
    }

    public function actualizarCargo() {
        if (isset($_POST['id_cargo']) && isset($_POST['nombre_cargo'])) {
            $id_cargo = $_POST['id_cargo'];
            $nombre_cargo = trim($_POST['nombre_cargo']);
            
            $cargoModel = new Cargo();
            $resultado = $cargoModel->actualizarCargo($id_cargo, $nombre_cargo);

            // Redirigimos disparando la ALERTA ANIMADA de actualización
            if ($resultado) {
                header("Location: index.php?vista=cargos&alerta=actualizado");
            } else {
                header("Location: index.php?vista=cargos&alerta=error");
            }
            exit();
        }
    }

    public function cambiarEstado() {
        if (isset($_GET['id']) && isset($_GET['estado'])) {
            $id_cargo = $_GET['id'];
            $estado_actual = $_GET['estado'];
            
            $cargoModel = new Cargo();
            $cargoModel->cambiarEstado($id_cargo, $estado_actual);

            // Redirigimos disparando la ALERTA ANIMADA
            header("Location: index.php?vista=cargos&alerta=actualizado");
            exit();
        }
    }
}
?>