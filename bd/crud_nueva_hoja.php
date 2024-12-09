<?php
include_once 'conexion.php';
$objeto = new \Google\Cloud\Samples\CloudSQL\MySQL\Conexion();
$conexion = $objeto->Conectar();

//Conexion con axios, por parametro POST
$_POST = json_decode(file_get_contents("php://input"), true);

$dig1 = random_int(1, 9);
$dig2 = random_int(1, 9);
$dig3 = random_int(1, 9);
$dig4 = random_int(1, 9);
$dig5 = random_int(1, 9);
$dig6 = random_int(1, 9);
$Folio = "" . $dig1 . $dig2 . $dig3 . $dig4 . $dig5 . $dig6;

//Recepcion de datos por Axios
$id_hoja = intval($Folio);
$id_Req = (isset($_POST['idReq'])) ? $_POST['idReq'] : '';
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : '';
$clv_Emisor = (isset($_POST['id_emisor'])) ? $_POST['id_emisor'] : '';
$clv_Prov = (isset($_POST['id_prov'])) ? $_POST['id_prov'] : '';
$totalPagar = (isset($_POST['Total'])) ? $_POST['Total'] : '';
$formaPago = (isset($_POST['formaPago'])) ? $_POST['formaPago'] : '';
$fechaSolicitud = (isset($_POST['fechaSolicitud'])) ? $_POST['fechaSolicitud'] : '';
$datos = json_decode((isset($_POST['items'])) ? $_POST['items'] : '');
$id_user = (isset($_POST['id_user'])) ? $_POST['id_user'] : '';
$observaciones = (isset($_POST['observaciones'])) ? $_POST['observaciones'] : '';
$time = (isset($_POST['time'])) ? $_POST['time'] : '';

switch ($accion) {
    case 1:
        /*  $consulta = "INSERT INTO `logs` (`log_id`, `log_accion`, `log_fechaAccion`, `log_usuario`, `log_horaAccion`, `log_moduloAccion`) VALUES (NULL, 'Agregar', '$fechaSolicitud', 0, '$time', 'Requesiciones')";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute(); */
        $consulta = "SELECT `requisicion_Hojas` FROM `requisiciones` WHERE `requisicion_id` = " . $id_Req;
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        $hoja = $data[0]['requisicion_Hojas'];
        $hoja++;
        $consulta = "INSERT INTO `hojasrequisicion` (`hojaRequisicion_id`, `hojaRequisicion_idReq`, `hojaRequisicion_numero`, `hojaRequisicion_FechaSolicitud`, `hojaRequisicion_empresa`, `hojaRequisicion_proveedor`, `hojaRequisicion_observaciones`, `hojarequisicion_comentariosValidacion`, `hojarequisicion_comentariosAutorizacion`, `hojaRequisicion_formaPago`, `hojaRequisicion_fechaPago`, `hojasRequisicion_bancoPago`, `hojaRequisicion_total`, `hojarequisicion_adeudo`, `hojaRequisicion_estatus`) VALUES ('$id_hoja', '$id_Req', '$hoja', '$fechaSolicitud', '$clv_Emisor', '$clv_Prov', '$observaciones', NULL, NULL, '$formaPago',NULL, NULL, '$totalPagar',0,'NUEVO')";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $consulta = "UPDATE `requisiciones` SET `requisicion_Hojas` = '$hoja' WHERE `requisiciones`.`requisicion_id` =" . $id_Req;
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        foreach ($datos as $item) {
            $Unidad = $item->Unidad;
            $Producto =  $item->Nombre;
            $Precio = (float)$item->UnitedPrice;
            $IVA = $item->IVA;
            $Ret = $item->Retenciones;
            $cantidad = $item->Cantidad;
            $banderaFlete = (int)$item->bandFlete;
            $banderaFisica = (int)$item->bandFisico;
            $banderaResico = (int)$item->bandResico;
            $consulta = "INSERT INTO `itemrequisicion` (`itemRequisicion_id`, `itemRequisicion_idHoja`, `itemRequisicion_unidad`, `itemRequisicion_producto`, `itemRequisicion_iva`, `itemRequisicion_retenciones`, `itemRequisicion_banderaFlete`, `itemRequisicion_banderaFisica`, `itemRequisicion_banderaResico`, `itemRequisicion_precio`, `itemRequisicion_cantidad`, `itemRequisicion_parcialidad`, `itemRequisicion_estatus`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, 'N')";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([$id_hoja, $Unidad, $Producto, $IVA, $Ret, $banderaFlete, $banderaFisica, $banderaResico, $Precio, $cantidad]);
        }
        $data = $id_hoja;
        break;
    case 2:
        $consulta = "SELECT `emisor_id`,`emisor_nombre`,`emisor_rfc`,`emisor_direccion`,`emisor_telefono`,`emisor_fax`,`emisor_zipCode` FROM `emisores`;";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 3:
        $consulta = "SELECT `proveedor_id`,`proveedor_nombre` FROM `provedores`;";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 4:
        $consulta = "SELECT `proveedor_id`, `proveedor_rfc`,`proveedor_clabe`,`proveedor_numeroCuenta`,`proveedor_sucursal`,`proveedor_refBanco`,`proveedor_banco`,`proveedor_email`,`proveedor_telefono` FROM `provedores` WHERE `proveedor_id` =" . $clv_Prov . ";";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 5:
        $consulta = "SELECT * FROM `users` WHERE `user_id` = '$id_user';";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 6:
        $consulta = "SELECT * FROM `obras` WHERE `obras_estatus` = 'ACTIVO'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
}

print json_encode($data, JSON_UNESCAPED_UNICODE);
$conexion = NULL;

function convertFolio($folioInt)
{
    if ($folioInt < 10) {
        return "0" . "0" . $folioInt;
    } else if ($folioInt < 100) {
        return "0" . $folioInt;
    } else {
        return $folioInt;
    }
}
