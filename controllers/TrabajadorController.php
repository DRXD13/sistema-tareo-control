<?php
require_once "models/Trabajador.php";

class TrabajadorController {
    
    public function guardarTrabajador() {
        if (isset($_POST['nombres'])) {
            $tipo_documento = $_POST['tipo_documento'];
            $numero_documento = trim($_POST['numero_documento']);

            // --- 🛡️ VALIDACIÓN DE SEGURIDAD (BACKEND) ---
            if ($tipo_documento == 'DNI') {
                // Obliga a que sean exactamente 8 números (sin letras, sin espacios)
                if (!preg_match('/^[0-9]{8}$/', $numero_documento)) {
                    header("Location: index.php?vista=trabajadores&alerta=error_formato_dni");
                    exit();
                }
            } elseif ($tipo_documento == 'CE') {
                // Obliga a que sean letras y números (entre 9 y 12 caracteres)
                if (!preg_match('/^[a-zA-Z0-9]{9,12}$/', $numero_documento)) {
                    header("Location: index.php?vista=trabajadores&alerta=error_formato_ce");
                    exit();
                }
            }

            $id_area = $_POST['id_area'];
            $id_cargo = $_POST['id_cargo'];
            // Validamos por si envían la cuadrilla vacía
            $id_cuadrilla = empty($_POST['id_cuadrilla']) ? null : $_POST['id_cuadrilla']; 
            
            $nombres = trim($_POST['nombres']);
            $apellidos = trim($_POST['apellidos']);
            $jornal_diario = $_POST['jornal_diario']; 
            $fecha_ingreso = $_POST['fecha_ingreso'];

            $trabajadorModel = new Trabajador();
            $resultado = $trabajadorModel->registrarTrabajador(
                $id_area, $id_cargo, $id_cuadrilla, $tipo_documento, 
                $numero_documento, $nombres, $apellidos, $jornal_diario, $fecha_ingreso
            );

            if ($resultado) {
                header("Location: index.php?vista=trabajadores&alerta=guardado");
            } else {
                header("Location: index.php?vista=trabajadores&alerta=error");
            }
            exit();
        }
    }

    public function actualizarTrabajador() {
        if (isset($_POST['id_trabajador'])) {
            $tipo_documento = $_POST['tipo_documento'];
            $numero_documento = trim($_POST['numero_documento']);

            // --- 🛡️ VALIDACIÓN DE SEGURIDAD (BACKEND) ---
            if ($tipo_documento == 'DNI') {
                if (!preg_match('/^[0-9]{8}$/', $numero_documento)) {
                    header("Location: index.php?vista=trabajadores&alerta=error_formato_dni");
                    exit();
                }
            } elseif ($tipo_documento == 'CE') {
                if (!preg_match('/^[a-zA-Z0-9]{9,12}$/', $numero_documento)) {
                    header("Location: index.php?vista=trabajadores&alerta=error_formato_ce");
                    exit();
                }
            }

            $id_trabajador = $_POST['id_trabajador'];
            $id_area = $_POST['id_area'];
            $id_cargo = $_POST['id_cargo'];
            $id_cuadrilla = empty($_POST['id_cuadrilla']) ? null : $_POST['id_cuadrilla'];
            
            $nombres = trim($_POST['nombres']);
            $apellidos = trim($_POST['apellidos']);
            $jornal_diario = $_POST['jornal_diario']; 
            $fecha_ingreso = $_POST['fecha_ingreso'];

            $trabajadorModel = new Trabajador();
            $resultado = $trabajadorModel->actualizarTrabajador(
                $id_trabajador, $id_area, $id_cargo, $id_cuadrilla, 
                $tipo_documento, $numero_documento, $nombres, $apellidos, $jornal_diario, $fecha_ingreso
            );

            if ($resultado) {
                header("Location: index.php?vista=trabajadores&alerta=actualizado");
            } else {
                header("Location: index.php?vista=trabajadores&alerta=error");
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

            header("Location: index.php?vista=trabajadores&alerta=actualizado");
            exit();
        }
    }
}
?>