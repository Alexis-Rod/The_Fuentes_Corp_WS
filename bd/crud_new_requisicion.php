<?php
include_once 'conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

//Conexion con axios, por parametro POST
$_POST = json_decode(file_get_contents("php://input"), true);

$dig1 = random_int(1, 9);
$dig2 = random_int(1, 9);
$dig3 = random_int(1, 9);
$Folio = "" . $dig1 . $dig2 . $dig3;

//Recepcion de datos por Axios
$id_Req = intval($Folio);
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : '';
$clv_Req = (isset($_POST['id_claveReq'])) ? $_POST['id_claveReq'] : '';
$clv_Obra = (isset($_POST['id_obra'])) ? $_POST['id_obra'] : '';
$clv_Emisor = (isset($_POST['id_emisor'])) ? $_POST['id_emisor'] : '';
$clv_Prov = (isset($_POST['id_prov'])) ? $_POST['id_prov'] : '';
$totalPagar = (isset($_POST['Total'])) ? $_POST['Total'] : '';
$formaPago = (isset($_POST['formaPago'])) ? $_POST['formaPago'] : '';
$fechaSolicitud = (isset($_POST['fechaSolicitud'])) ? $_POST['fechaSolicitud'] : '';
$datos = json_decode((isset($_POST['items'])) ? $_POST['items'] : '');
$id_user = (isset($_POST['id_user'])) ? $_POST['id_user'] : '';
$id_presion = (isset($_POST['id_Presion'])) ? $_POST['id_Presion'] : '';
$observaciones = (isset($_POST['observaciones'])) ? $_POST['observaciones'] : '';
$time = (isset($_POST['time'])) ? $_POST['time'] : '';

switch ($accion) {
    case 1:
        $consulta = "INSERT INTO `logs` (`log_id`, `log_accion`, `log_fechaAccion`, `log_usuario`, `log_horaAccion`, `log_moduloAccion`) VALUES (NULL, 'Agregar', '$fechaSolicitud', '$id_user', '$time', 'Requesiciones')";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $consulta = "SELECT `presiones_nombre`,`presiones_hojas` FROM `presiones` WHERE `presiones_id` = " . $id_presion;
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        $hoja = $data[0]['presiones_hojas'];
        $NumeroReq = $data[0]['presiones_nombre'] . "-HOJA N°" . $hoja;
        $consulta = "INSERT INTO `requisiciones` (`requisicion_id`, `requisicion_idPresion`, `requisicion_Numero`, `requisicion_hojaNumero`, `requisicion_emisorID`, `requisicion_receptorID`, `requisicion_fechaSolicitud`, `requisicion_total`, `requisicion_observaciones`, `requisicion_formaPago`, `requisicion_estatus`) VALUES ('$id_Req', '$id_presion', '$NumeroReq', '$hoja', '$clv_Emisor', '$clv_Prov', '$fechaSolicitud', '$totalPagar', '$observaciones', '$formaPago', 'PENDIENTE')";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $hoja++;
        $consulta = "UPDATE `presiones` SET `presiones_hojas` = '$hoja' WHERE `presiones`.`presiones_id` =" . $id_presion;
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        var_dump($datos);
        foreach ($datos as $item) {
            $Unidad = $item->Unidad;
            $Producto =  $item->Nombre;
            $Precio = $item->UnitedPrice;
            $IVA = $item->IVA;
            $Ret = $item->Retenciones;
            $cantidad = $item->Cantidad;
            $banderaFlete = $item->bandFlete;
            $banderaFisica = $item->bandFisico;
            $banderaResico = $item->bandResico;
            $consulta = "INSERT INTO `itemrequisicion` (`itemRequisicion_id`, `itemRequisicion_idReq`, `itemRequisicion_unidad`, `itemRequisicion_producto`, `itemRequisicion_iva`, `itemRequisicion_retenciones`, `itemRequisicion_banderaFlete`, `itemRequisicion_banderaFisica`, `itemRequisicion_banderaResico`, `itemRequisicion_precio`, `itemRequisicion_cantidad`, `itemRequisicion_parcialidad`, `itemRequisicion_fechaPago`, `itemRequisicion_bancoPago`, `itemRequisicion_estatus`) VALUES (NULL, '$id_Req', '$Unidad', '$Producto', '$IVA', '$Ret', '$banderaFlete', '$banderaFisica', '$banderaResico', '$Precio', '$cantidad', '', NULL, '', 'N')";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
        }
        unset($item);
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
