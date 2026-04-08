<?php
require_once "models/Trabajador.php";

class TrabajadorController {
    
    public function guardarTrabajador() {
        if (isset($_POST['nombres'])) {
            $id_area = $_POST['id_area'];
            $id_cargo = $_POST['id_cargo'];
            $id_cuadrilla = $_POST['id_cuadrilla']; 
            $tipo_documento = $_POST['tipo_documento'];
            $numero_documento = trim($_POST['numero_documento']);
            $nombres = trim($_POST['nombres']);
            $apellidos = trim($_POST['apellidos']);
            $jornal_diario = $_POST['jornal_diario']; // Atrapamos el jornal
            $fecha_ingreso = $_POST['fecha_ingreso'];

            $trabajadorModel = new Trabajador();
            $resultado = $trabajadorModel->registrarTrabajador(
                $id_area, $id_cargo, $id_cuadrilla, $tipo_documento, 
                $numero_documento, $nombres, $apellidos, $jornal_diario, $fecha_ingreso
            );

            if ($resultado) {
                header("Location: index.php?vista=trabajadores&mensaje=exito");
            } else {
                header("Location: index.php?vista=trabajadores&mensaje=error");
            }
            exit();
        }
    }

    public function actualizarTrabajador() {
        if (isset($_POST['id_trabajador'])) {
            $id_trabajador = $_POST['id_trabajador'];
            $id_area = $_POST['id_area'];
            $id_cargo = $_POST['id_cargo'];
            $id_cuadrilla = $_POST['id_cuadrilla'];
            $tipo_documento = $_POST['tipo_documento'];
            $numero_documento = trim($_POST['numero_documento']);
            $nombres = trim($_POST['nombres']);
            $apellidos = trim($_POST['apellidos']);
            $jornal_diario = $_POST['jornal_diario']; // Atrapamos el jornal
            $fecha_ingreso = $_POST['fecha_ingreso'];

            $trabajadorModel = new Trabajador();
            $resultado = $trabajadorModel->actualizarTrabajador(
                $id_trabajador, $id_area, $id_cargo, $id_cuadrilla, 
                $tipo_documento, $numero_documento, $nombres, $apellidos, $jornal_diario, $fecha_ingreso
            );

            if ($resultado) {
                header("Location: index.php?vista=trabajadores&mensaje=exito_editar");
            } else {
                header("Location: index.php?vista=trabajadores&mensaje=error");
            }
            exit();
        }
    }

    public function cambiarEstado() {
        if (isset($_GET['id']) && isset($_GET['estado'])) {
            $id_trabajador = $_GET['id'];
            $estado_actual = $_GET['estado'];
            
            $trabajadorModel = new Trabajador();
            $trabajadorModel->cambiarEstado($id_trabajador, $estado_actual);

            header("Location: index.php?vista=trabajadores&mensaje=exito_estado");
            exit();
        }
    }
}
?>