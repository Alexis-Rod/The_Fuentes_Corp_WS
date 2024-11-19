<?php
include_once 'conexion.php';
$objeto = new \Google\Cloud\Samples\CloudSQL\MySQL\Conexion();
$conexion = $objeto->Conectar();

//Conexion con axios, por parametro POST
$_POST = json_decode(file_get_contents("php://input"), true);

$accion = (isset($_POST['accion'])) ? $_POST['accion'] : '';
$id_user = (isset($_POST['id_user'])) ? $_POST['id_user'] : '';
$obra = (isset($_POST['obra'])) ? $_POST['obra'] : '';
$nombreReq  = (isset($_POST['nombreReq'])) ? $_POST['nombreReq'] : '';
$fechaReq =   (isset($_POST['fechaReq'])) ? $_POST['fechaReq'] : '';
$idPresion =   (isset($_POST['idPresion'])) ? $_POST['idPresion'] : '';
$idReq =   (isset($_POST['idReq'])) ? $_POST['idReq'] : '';
$idHoja =   (isset($_POST['idHoja'])) ? $_POST['idHoja'] : '';

switch ($accion) {
    case 1:
        $consulta = "SELECT `hojaRequisicion_id`, `hojaRequisicion_numero`, `hojaRequisicion_estatus`,`requisicion_id`, `requisicion_Clave`, `requisicion_Numero`, `requisicion_Nombre` FROM `hojasrequisicion` INNER JOIN requisiciones ON requisiciones.requisicion_id = hojasrequisicion.hojaRequisicion_idReq WHERE requisiciones.requisicion_Obra = '$obra' AND (hojasrequisicion.hojaRequisicion_estatus = 'PENDIENTE' OR hojasrequisicion.hojaRequisicion_estatus = 'RECHAZADA' AND requisiciones.requisicion_estatus = 'ABIERTO');";
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
        $consulta = "SELECT `obras_nombre` FROM `obras` WHERE `obras_id` =" . $obra;
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 5:
        $consulta = "SELECT * FROM `obras` WHERE `obras_estatus` = 'ACTIVO'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case  6:
        $consulta = "SELECT `presiones_nombre` FROM `presiones` WHERE `presiones_id` = '$idPresion'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case  7:
        $consulta = "INSERT INTO `requisicionesligadas` (`requisicionesLigada_id`, `requisicionesLigada_presionID`, `requisicionesLigadas_requisicionID`, `requisicionesLigadas_hojaID`) VALUES (NULL, '$idPresion', '$idReq', '$idHoja')";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $consulta = "UPDATE `hojasrequisicion` SET `hojaRequisicion_estatus` = 'LIGADA' WHERE `hojasrequisicion`.`hojaRequisicion_id` = '$idHoja'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        break;
}

print json_encode($data, JSON_UNESCAPED_UNICODE);
$conexion = NULL;
