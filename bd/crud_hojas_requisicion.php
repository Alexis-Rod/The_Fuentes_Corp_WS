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
$clave =   (isset($_POST['clave'])) ? $_POST['clave'] : '';
$IdReq =   (isset($_POST['IdReq'])) ? $_POST['IdReq'] : '';

switch ($accion) {
    case 1:
        $consulta = "SELECT * FROM `hojasrequisicion` WHERE `hojaRequisicion_idReq` = '$IdReq' ORDER BY `hojaRequisicion_numero`";
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
    case 4:
        $consulta = "SELECT SUM(`requisicion_total`) AS `totalPresion` FROM `requisiciones` WHERE `requisicion_idPresion`='$id_presion';";
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
    case 6:
        $consulta = "SELECT `obras_nombre`,`ciudadesObras_codigo` FROM `obras` JOIN estadosobra ON estadosobra.ciudadesObras_id = obras.obras_cuidad WHERE `obras_id` = '$obra'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        $numero_requesicion = $data[0]['ciudadesObras_codigo'] . "-" . $data[0]['obras_nombre'];
        $consulta = "SELECT * FROM `requisiciones` WHERE `requisicion_Clave` LIKE '$clave' AND `requisicion_Obra` = '$obra'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        if (count($data) == 0) {
            $numero_requesicion = $numero_requesicion . "-" . $clave . "-000";
            // Consulta para insertar datos en la tabla requisiciones
            $consulta = "INSERT INTO `requisiciones` (`requisicion_id`, `requisicion_Clave`, `requisicion_Numero`, `requisicion_Nombre`, `requisicion_Obra`, `requisicion_fechaSolicitud`, `requisicion_Folio`, `requisicion_total`, `requisicion_estatus`) 
            VALUES (NULL, :requisicion_clave, :requisicion_Numero, :requisicion_nombre, :requisicion_Obra , :requisicion_fechaSolicitud, '0', '0', 'ABIERTO')";
            $resultado = $conexion->prepare($consulta);
            // Vincular las variables a la consulta
            $resultado->bindParam(':requisicion_clave', $clave);
            $resultado->bindParam(':requisicion_nombre', $nombreReq);
            $resultado->bindParam(':requisicion_fechaSolicitud', $fechaReq);
            $resultado->bindParam(':requisicion_Obra', $obra);
            $resultado->bindParam(':requisicion_Numero', $numero_requesicion);
            // Ejecutar la consulta
            $resultado->execute();
        } else {
            $consulta = "SELECT `requisicion_Folio` FROM `requisiciones` WHERE `requisicion_Clave` =  '$clave';";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
            $folio = $data[count($data) - 1]['requisicion_Folio'] + 1;
            $numero_requesicion = $numero_requesicion . "-" . $clave . "-" . convertFolio($folio);

            // Consulta para insertar datos en la tabla requisiciones
            $consulta = "INSERT INTO `requisiciones` (`requisicion_id`, `requisicion_Clave`, `requisicion_Numero`, `requisicion_Nombre`, `requisicion_Obra`, `requisicion_fechaSolicitud`, `requisicion_Folio`, `requisicion_total`, `requisicion_estatus`) 
             VALUES (NULL, :requisicion_clave, :requisicion_Numero, :requisicion_nombre, :requisicion_Obra , :requisicion_fechaSolicitud, :requisicion_Folio, '0', 'ABIERTO')";
            $resultado = $conexion->prepare($consulta);
            // Vincular las variables a la consulta
            $resultado->bindParam(':requisicion_clave', $clave);
            $resultado->bindParam(':requisicion_nombre', $nombreReq);
            $resultado->bindParam(':requisicion_fechaSolicitud', $fechaReq);
            $resultado->bindParam(':requisicion_Obra', $obra);
            $resultado->bindParam(':requisicion_Numero', $numero_requesicion);
            $resultado->bindParam(':requisicion_Folio', $folio);
            // Ejecutar la consulta
            $resultado->execute();
        }
        break;
    case 7:
        $consulta = "SELECT `requisicion_Numero` FROM `requisiciones` WHERE `requisicion_id` =" . $IdReq;
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
