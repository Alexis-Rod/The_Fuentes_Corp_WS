<?php
include_once 'conexion.php';
$objeto = new \Google\Cloud\Samples\CloudSQL\MySQL\Conexion();
$conexion = $objeto->Conectar();

//Conexion con axios, por parametro POST
$_POST = json_decode(file_get_contents("php://input"), true);

$accion = (isset($_POST['accion'])) ? $_POST['accion'] : '';
$id_user = (isset($_POST['id_user'])) ? $_POST['id_user'] : '';
$idReq = (isset($_POST['id_req'])) ? $_POST['id_req'] : '';
$idHoja = (isset($_POST['id_Hoja'])) ? $_POST['id_Hoja'] : '';
$unidad = (isset($_POST['unidad'])) ? $_POST['unidad'] : '';
$producto = (isset($_POST['producto'])) ? $_POST['producto'] : '';
$iva = (isset($_POST['iva'])) ? $_POST['iva'] : '';
$retenciones = (isset($_POST['retenciones'])) ? $_POST['retenciones'] : '';
$banderaFlete = (isset($_POST['banderaFlete'])) ? (int)$_POST['banderaFlete'] : '';
$banderaFisica = (isset($_POST['banderaFisica'])) ? (int)$_POST['banderaFisica'] : '';
$banderaResico = (isset($_POST['banderaResico'])) ? (int)$_POST['banderaResico'] : '';
$precio = (isset($_POST['precio'])) ? $_POST['precio'] : '';
$cantidad = (isset($_POST['cantidad'])) ? $_POST['cantidad'] : '';
$total = (isset($_POST['total'])) ? $_POST['total'] : '';
$id = (isset($_POST['id'])) ? $_POST['id'] : '';
$obra = (isset($_POST['obra'])) ? $_POST['obra'] : '';
$idPresion = (isset($_POST['idPresion'])) ? $_POST['idPresion'] : '';
$comentarios = (isset($_POST['comentarios'])) ? $_POST['comentarios'] : '';

switch ($accion) {
    case 1:
        $consulta = "SELECT `itemRequisicion_id`, `itemRequisicion_unidad`, `itemRequisicion_producto`, `itemRequisicion_iva`, `itemRequisicion_retenciones`, `itemRequisicion_banderaFlete`, `itemRequisicion_banderaFisica`, `itemRequisicion_banderaResico`, `itemRequisicion_precio`, `itemRequisicion_cantidad`, `itemRequisicion_estatus`, `hojaRequisicion_total` FROM itemrequisicion INNER JOIN hojasrequisicion ON itemRequisicion_idHoja = hojasrequisicion.hojaRequisicion_id WHERE itemRequisicion_idHoja = '$idHoja' ORDER BY itemRequisicion_id ASC";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 2:
        $consulta = "SELECT * FROM `users` WHERE `user_id` = '$id_user';";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 3:
        $consulta = "UPDATE `itemrequisicion` SET `itemRequisicion_unidad`='$unidad', `itemRequisicion_producto`='$producto', `itemRequisicion_iva`='$iva', `itemRequisicion_retenciones`='$retenciones', `itemRequisicion_banderaFlete`='$banderaFlete', `itemRequisicion_banderaFisica`='$banderaFisica', `itemRequisicion_banderaResico`='$banderaResico', `itemRequisicion_precio`='$precio', `itemRequisicion_cantidad`='$cantidad' WHERE itemRequisicion_id ='$id'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $consulta = "SELECT SUM((`itemRequisicion_cantidad` * `itemRequisicion_precio`)+`itemRequisicion_iva`-`itemRequisicion_retenciones`) AS `TotalItem` FROM `itemrequisicion` WHERE `itemRequisicion_idHoja` = '$idHoja';";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        $totalUpdate = $data[0]['TotalItem'];
        $consulta = "UPDATE `hojasrequisicion` SET `hojaRequisicion_total`='$totalUpdate' WHERE `hojaRequisicion_id` =" . $idHoja;
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        break;
    case 4:
        $consulta = "DELETE FROM itemrequisicion WHERE itemRequisicion_id =" . $id;
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $consulta = "SELECT SUM((`itemRequisicion_cantidad` * `itemRequisicion_precio`)+`itemRequisicion_iva`-`itemRequisicion_retenciones`) AS `TotalItem` FROM `itemrequisicion` WHERE `itemRequisicion_idHoja` = '$idHoja';";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        $totalDelete = $data[0]['TotalItem'];
        $consulta = "UPDATE `hojasrequisicion` SET `hojaRequisicion_total`='$totalDelete' WHERE `hojaRequisicion_id` =" . $idHoja;
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        break;
    case 5:
        $consulta = "SELECT * FROM `hojasrequisicion` INNER JOIN emisores ON hojasrequisicion.hojaRequisicion_empresa = emisores.emisor_id INNER JOIN provedores ON hojasrequisicion.hojaRequisicion_proveedor = provedores.proveedor_id WHERE hojaRequisicion_id ='$idHoja'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 6:
        $consulta = "INSERT INTO `itemrequisicion` (`itemRequisicion_id`, `itemRequisicion_idHoja`, `itemRequisicion_unidad`, `itemRequisicion_producto`, `itemRequisicion_iva`, `itemRequisicion_retenciones`, `itemRequisicion_banderaFlete`, `itemRequisicion_banderaFisica`, `itemRequisicion_banderaResico`, `itemRequisicion_precio`, `itemRequisicion_cantidad`, `itemRequisicion_estatus`) VALUES (NULL, '$idHoja', '$unidad', '$producto', '$iva', '$retenciones', '$banderaFlete', '$banderaFisica', '$banderaResico', '$precio', '$cantidad', 'N')";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $consulta = "SELECT SUM((`itemRequisicion_cantidad` * `itemRequisicion_precio`)+`itemRequisicion_iva`-`itemRequisicion_retenciones`) AS `TotalItem` FROM `itemrequisicion` WHERE `itemRequisicion_idHoja` = '$idHoja';";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        $totalInsert = $data[0]['TotalItem'];
        $consulta = "UPDATE `hojasrequisicion` SET `hojaRequisicion_total`='$totalInsert' WHERE `hojaRequisicion_id` =" . $idHoja;
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        break;
    case 7:
        $consulta = "UPDATE `hojasrequisicion` SET `hojaRequisicion_estatus` = 'REVISION', `hojaRequisicion_observaciones` = '$comentarios' WHERE `hojasrequisicion`.`hojaRequisicion_id` = '$idReq'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        break;
    case 8:
        $consulta = "SELECT `obras_nombre`,`ciudadesObras_nombre` FROM `obras` JOIN estadosobra ON estadosobra.ciudadesObras_id = obras.obras_cuidad WHERE `obras_id` = '$obra'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 9:
        $consulta = "SELECT `requisicion_Clave`, `requisicion_Numero` FROM `requisiciones` WHERE `requisicion_id` ='$idReq'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 10:
        $consulta = "SELECT * FROM `obras` WHERE `obras_estatus` = 'ACTIVO'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 11:
        $consulta = "INSERT INTO `requisicionesligadas` (`requisicionesLigada_id`, `requisicionesLigada_presionID`, `requisicionesLigadas_requisicionID`, `requisicionesLigadas_hojaID`) VALUES (NULL, '$idPresion', '$idReq', '$idHoja')";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $consulta = "UPDATE `hojasrequisicion` SET `hojaRequisicion_estatus` = 'LIGADA', `hojarequisicion_comentariosValidacion` = 'OK' WHERE `hojasrequisicion`.`hojaRequisicion_id` = '$idHoja'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        break;
    case 12:
        $consulta = "UPDATE `hojasrequisicion` SET `hojaRequisicion_estatus` = 'PENDIENTE', `hojarequisicion_comentariosValidacion` = '$comentarios' WHERE `hojasrequisicion`.`hojaRequisicion_id` = '$idHoja'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        break;
}

print json_encode($data, JSON_UNESCAPED_UNICODE);
$conexion = NULL;
