<?php
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=Presion_de Gastos_" . date('Y:m:d:m:s') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
include_once 'conexion.php';
$objeto = new \Google\Cloud\Samples\CloudSQL\MySQL\Conexion();
$conexion = $objeto->Conectar();

//Conexion con axios, por parametro POST
$_POST = json_decode(file_get_contents("php://input"), true);

$accion = (isset($_POST['accion'])) ? $_POST['accion'] : '';
$id_user = (isset($_POST['id_user'])) ? $_POST['id_user'] : '';
$obra = (isset($_POST['obra'])) ? $_POST['obra'] : '';
$dia = (isset($_POST['dia'])) ? $_POST['dia'] : '';
$semana = (isset($_POST['semana'])) ? $_POST['semana'] : '';
$idReq = (isset($_POST['idReq'])) ? $_POST['idReq'] : '';
$parcial = (isset($_POST['parcial'])) ? $_POST['parcial'] : '';
$fechaPago = (isset($_POST['fechaPago'])) ? $_POST['fechaPago'] : '';
$bancoPago = (isset($_POST['bancoPago'])) ? $_POST['bancoPago'] : '';
$estatus = (isset($_POST['status'])) ? $_POST['status'] : '';
$time = (isset($_POST['time'])) ? $_POST['time'] : '';
$output = "";

switch ($accion) {
    case 1:
        $consulta = "SELECT * FROM `users` WHERE `user_id` = '$id_user';";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 2:
        $consulta = "SELECT * FROM `obras` WHERE `obras_estatus` = 'ACTIVO'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 3:
        $consulta = "SELECT `itemRequisicion_id`,`requisicion_Clave`,`requisicion_Numero`,`proveedor_nombre`,`itemRequisicion_producto`,`hojaRequisicion_total`,`hojaRequisicion_observaciones`,`hojaRequisicion_formaPago`,`itemRequisicion_parcialidad`,`itemRequisicion_fechaPago`,`itemRequisicion_bancoPago` FROM `requisicionesligadas`\n"
            . "JOIN presiones\n"
            . "ON presiones.presiones_id = requisicionesLigada_presionID\n"
            . "JOIN requisiciones\n"
            . "ON requisiciones.requisicion_id = requisicionesLigadas_requisicionID\n"
            . "JOIN hojasrequisicion\n"
            . "ON hojasrequisicion.hojaRequisicion_id = requisicionesLigadas_hojaID\n"
            . "JOIN itemrequisicion\n"
            . "ON itemrequisicion.itemRequisicion_idHoja = hojasrequisicion.hojaRequisicion_id\n"
            . "JOIN provedores\n"
            . "ON hojasrequisicion.hojaRequisicion_proveedor = provedores.proveedor_id\n"
            . "WHERE presiones.presiones_obra = '$obra'\n"
            . "AND hojasrequisicion.hojaRequisicion_estatus = 'LIGADA'\n"
            . "OR hojasrequisicion.hojaRequisicion_estatus = 'PAGADA'\n"
            . "ORDER BY requisiciones.requisicion_Clave DESC;";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 4:
        $consulta = "SELECT `obras_nombre` FROM `obras` WHERE `obras_id`= '$obra'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 5:
        $consulta = "INSERT INTO `logs` (`log_id`, `log_accion`, `log_fechaAccion`, `log_usuario`, `log_horaAccion`, `log_moduloAccion`) VALUES (NULL, 'Agregar', '$fechaPago', '$id_user', '$time', 'Presion Detalle')";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $consulta = "UPDATE `itemrequisicion` SET `itemRequisicion_parcialidad` = '$parcial', `itemRequisicion_fechaPago` = '$fechaPago', `itemRequisicion_bancoPago` = '$bancoPago' WHERE `itemrequisicion`.`itemRequisicion_id` = '$idReq'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        break;
    case 6:
        $consulta = "SELECT `itemRequisicion_id`,`requisicion_Clave`,`requisicion_Numero`,`proveedor_nombre`,`itemRequisicion_producto`,`hojaRequisicion_total`,`hojaRequisicion_observaciones`,`hojaRequisicion_formaPago`,`itemRequisicion_parcialidad`,`itemRequisicion_fechaPago`,`itemRequisicion_bancoPago` FROM `requisicionesligadas`\n"
            . "JOIN presiones\n"
            . "ON presiones.presiones_id = requisicionesLigada_presionID\n"
            . "JOIN requisiciones\n"
            . "ON requisiciones.requisicion_id = requisicionesLigadas_requisicionID\n"
            . "JOIN hojasrequisicion\n"
            . "ON hojasrequisicion.hojaRequisicion_id = requisicionesLigadas_hojaID\n"
            . "JOIN itemrequisicion\n"
            . "ON itemrequisicion.itemRequisicion_idHoja = hojasrequisicion.hojaRequisicion_id\n"
            . "JOIN provedores\n"
            . "ON hojasrequisicion.hojaRequisicion_proveedor = provedores.proveedor_id\n"
            . "WHERE presiones.presiones_obra = '$obra'\n"
            . "AND hojasrequisicion.hojaRequisicion_estatus = 'LIGADA'\n"
            . "OR hojasrequisicion.hojaRequisicion_estatus = 'PAGADA'\n"
            . "ORDER BY requisiciones.requisicion_Clave DESC;";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);

        // Verificar que se hayan obtenido datos
        if (empty($data)) {
            // Definir el nombre del archivo CSV
            $filename = "presion.csv";

            // Abrir el archivo para escritura
            $file = fopen('php://output', 'w');
            // Escribir la cabecera del archivo CSV
            fputcsv($file, array_keys($data[0]));

            // Escribir los datos al archivo CSV
            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            // Cerrar el archivo
            fclose($file);

            // Forzar la descarga del archivo CSV
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        } else {
            echo "No hay datos para descargar";
        }
        exit;
}

print json_encode($data, JSON_UNESCAPED_UNICODE);
$conexion = NULL;
