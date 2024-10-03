<?php
include_once 'conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

//Conexion con axios, por parametro POST
$_POST = json_decode(file_get_contents("php://input"), true);

$accion = (isset($_POST['accion'])) ? $_POST['accion'] : '';
$id_user = (isset($_POST['id_user'])) ? $_POST['id_user'] : '';
$idReq = (isset($_POST['id_req'])) ? $_POST['id_req'] : '';
$unidad = (isset($_POST['unidad'])) ? $_POST['unidad'] : '';
$producto = (isset($_POST['producto'])) ? $_POST['producto'] : '';
$iva = (isset($_POST['iva'])) ? $_POST['iva'] : '';
$retenciones = (isset($_POST['retenciones'])) ? $_POST['retenciones'] : '';
$banderaFlete = (isset($_POST['banderaFlete'])) ? $_POST['banderaFlete'] : '';
$banderaFisica = (isset($_POST['banderaFisica'])) ? $_POST['banderaFisica'] : '';
$banderaResico = (isset($_POST['banderaResico'])) ? $_POST['banderaResico'] : '';
$precio = (isset($_POST['precio'])) ? $_POST['precio'] : '';
$cantidad = (isset($_POST['cantidad'])) ? $_POST['cantidad'] : '';
$total = (isset($_POST['total'])) ? $_POST['total'] : '';
$id = (isset($_POST['id'])) ? $_POST['id'] : '';
$obra = (isset($_POST['obra'])) ? $_POST['obra'] : '';
$idPresion = (isset($_POST['idPresion'])) ? $_POST['idPresion'] : '';

switch ($accion) {
    case 1:
        $consulta = "SELECT `itemRequisicion_id`, `itemRequisicion_unidad`, `itemRequisicion_producto`, `itemRequisicion_iva`, `itemRequisicion_retenciones`, `itemRequisicion_banderaFlete`, `itemRequisicion_banderaFisica`, `itemRequisicion_banderaResico`, `itemRequisicion_precio`, `itemRequisicion_cantidad`, `itemRequisicion_estatus`, `requisicion_total` FROM itemrequisicion INNER JOIN requisiciones ON itemRequisicion_idReq = requisiciones.requisicion_id WHERE itemRequisicion_idReq = '$idReq'";
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
        $consulta = "UPDATE `itemrequisicion` INNER JOIN requisiciones ON requisiciones.requisicion_id = itemrequisicion.itemRequisicion_idReq SET `itemRequisicion_unidad`='$unidad', `itemRequisicion_producto`='$producto', `itemRequisicion_iva`='$iva', `itemRequisicion_retenciones`='$retenciones', `itemRequisicion_banderaFlete`='$banderaFlete', `itemRequisicion_banderaFisica`='$banderaFisica', `itemRequisicion_banderaResico`='$banderaResico', `itemRequisicion_precio`='$precio', `itemRequisicion_cantidad`='$cantidad', `requisicion_total`='$total' WHERE itemRequisicion_id ='$id'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        break;
    case 4:
        echo $id . " " . $idReq;
        $consulta = "DELETE FROM itemrequisicion WHERE itemRequisicion_id =" . $id;
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $consulta = "UPDATE `requisiciones` SET `requisicion_total`='$total' WHERE `requisicion_id` =" . $idReq;
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        break;
    case 5:
        $consulta = "SELECT * FROM `requisiciones` INNER JOIN emisores ON requisiciones.requisicion_emisorID = emisores.emisor_id INNER JOIN provedores ON requisiciones.requisicion_receptorID = provedores.proveedor_id WHERE requisicion_id ='$idReq'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 6:
        $consulta = "INSERT INTO `itemrequisicion` (`itemRequisicion_id`, `itemRequisicion_idReq`, `itemRequisicion_unidad`, `itemRequisicion_producto`, `itemRequisicion_iva`, `itemRequisicion_retenciones`, `itemRequisicion_banderaFlete`, `itemRequisicion_banderaFisica`, `itemRequisicion_banderaResico`, `itemRequisicion_precio`, `itemRequisicion_cantidad`, `itemRequisicion_estatus`) VALUES (NULL, '$idReq', '$unidad', '$producto', '$iva', '$retenciones', '$banderaFlete', '$banderaFisica', '$banderaResico', '$precio', '$cantidad', 'N')";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $consulta = "UPDATE `requisiciones` SET `requisicion_total`='$total' WHERE `requisicion_id` =" . $idReq;
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        break;
    case 7:
        $consulta = "UPDATE `requisiciones` SET `requisicion_estatus` = 'REVISION' WHERE `requisiciones`.`requisicion_id` = '$idReq'";
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
        $consulta = "SELECT `presiones_clave` FROM `presiones` WHERE `presiones_id` ='$idPresion'";
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
}

print json_encode($data, JSON_UNESCAPED_UNICODE);
$conexion = NULL;
