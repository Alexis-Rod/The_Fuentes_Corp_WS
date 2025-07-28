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
$folioReq =   (isset($_POST['folio'])) ? $_POST['folio'] : '';
$Hoja =   (isset($_POST['hoja'])) ? $_POST['hoja'] : '';
$idReq =   (isset($_POST['idReq'])) ? $_POST['idReq'] : '';
$numReq =   (isset($_POST['numeroReq'])) ? $_POST['numeroReq'] : '';

switch ($accion) {
    case 1:
        $consulta = "SELECT `requisicion_id` ,`requisicion_Numero` ,`requisicion_Clave` ,`requisicion_Nombre` ,`requisicion_estatus` FROM `requisiciones` WHERE `requisicion_Obra` = '$obra' ORDER BY `requisicion_Clave`, `requisicion_Numero` DESC";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $dataArray = $resultado->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        if (count($dataArray) > 0) {
            foreach ($dataArray as $row) {
                array_push($data, array(
                    'requisicion_id' => $row['requisicion_id'],
                    'requisicion_Numero' => $row['requisicion_Numero'],
                    'requisicion_Clave' => $row['requisicion_Clave'],
                    'requisicion_Nombre' => $row['requisicion_Nombre'],
                    'requisicion_estatus' => $row['requisicion_estatus'],
                    'requisicion_EditShow' => false
                ));
            }
        }
        break;
    case 2:
        $consulta = "SELECT * FROM `users` WHERE `user_id` = '$id_user';";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 3:
        $consulta = "SELECT `obras_nombre`, `obra_automatico` FROM `obras` WHERE `obras_id` =" . $obra;
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
        $consulta = "SELECT * FROM `obras` WHERE `obras_estatus` = 'ACTIVO' ORDER BY `obras_nombre`";
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
            $consulta = "INSERT INTO `requisiciones` (`requisicion_id`, `requisicion_Clave`, `requisicion_Numero`, `requisicion_Nombre`, `requisicion_Obra`, `requisicion_fechaSolicitud`, `requisicion_Folio`, `requisicion_Hojas`, `requisicion_total`, `requisicion_estatus`) 
            VALUES (NULL, :requisicion_clave, :requisicion_Numero, :requisicion_nombre, :requisicion_Obra , :requisicion_fechaSolicitud, '0', '0', '0', 'ABIERTO')";
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
            $consulta = "INSERT INTO `requisiciones` (`requisicion_id`, `requisicion_Clave`, `requisicion_Numero`, `requisicion_Nombre`, `requisicion_Obra`, `requisicion_fechaSolicitud`, `requisicion_Folio`, `requisicion_Hojas`, `requisicion_total`, `requisicion_estatus`) 
             VALUES (NULL, :requisicion_clave, :requisicion_Numero, :requisicion_nombre, :requisicion_Obra , :requisicion_fechaSolicitud, :requisicion_Folio, '0', '0', 'ABIERTO')";
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
        $consulta = "SELECT `obras_nombre`,`ciudadesObras_codigo` FROM `obras` JOIN estadosobra ON estadosobra.ciudadesObras_id = obras.obras_cuidad WHERE `obras_id` = '$obra'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        $numero_requesicion = $data[0]['ciudadesObras_codigo'] . "-" . $data[0]['obras_nombre'];
        $numero_requesicion = $numero_requesicion . "-" . $clave . "-" . convertFolio($folioReq);
        // Consulta para insertar datos en la tabla requisiciones
        $consulta = "INSERT INTO `requisiciones` (`requisicion_id`, `requisicion_Clave`, `requisicion_Numero`, `requisicion_Nombre`, `requisicion_Obra`, `requisicion_fechaSolicitud`, `requisicion_Folio`, `requisicion_Hojas`, `requisicion_total`, `requisicion_estatus`) 
            VALUES (NULL, :requisicion_clave, :requisicion_Numero, :requisicion_nombre, :requisicion_Obra , :requisicion_fechaSolicitud, :requisicion_Folio, :requisicion_Hojas, '0', 'ABIERTO')";
        $resultado = $conexion->prepare($consulta);
        // Vincular las variables a la consulta
        $resultado->bindParam(':requisicion_clave', $clave);
        $resultado->bindParam(':requisicion_nombre', $nombreReq);
        $resultado->bindParam(':requisicion_fechaSolicitud', $fechaReq);
        $resultado->bindParam(':requisicion_Obra', $obra);
        $resultado->bindParam(':requisicion_Numero', $numero_requesicion);
        $resultado->bindParam(':requisicion_Folio', $folioReq);
        $resultado->bindParam(':requisicion_Hojas', $Hoja);
        // Ejecutar la consulta
        $resultado->execute();
        break;
    case 8:
        $consulta = "UPDATE `requisiciones` SET `requisicion_Numero` = :newNumero, `requisicion_Nombre` = :newNombre WHERE `requisiciones`.`requisicion_id` = :idReq";
        $resultado = $conexion->prepare($consulta);
        $resultado->bindParam(':newNumero', $numReq, PDO::PARAM_STR);
        $resultado->bindParam(':newNombre', $nombreReq, PDO::PARAM_STR);
        $resultado->bindParam(':idReq', $idReq, PDO::PARAM_INT);
        $resultado->execute();
        $data = 0;
        break;
    case 9:
        $consulta = "SELECT `hojaRequisicion_id` FROM `hojasrequisicion` WHERE `hojaRequisicion_idReq` = :idReq";
        $resultado = $conexion->prepare($consulta);
        $resultado->bindParam(':idReq', $idReq, PDO::PARAM_INT);
        $resultado->execute();
        $idsHojas = $resultado->fetchAll(PDO::FETCH_ASSOC);
        foreach($idsHojas as $idHoja) {
$consulta = "DELETE FROM `itemrequisicion` WHERE `itemrequisicion_idHoja` = :idHoja";
            $resultado = $conexion->prepare($consulta);
            $resultado->bindParam(':idHoja', $idHoja, PDO::PARAM_INT);
            $resultado->execute();
        }
        $consulta = "DELETE FROM `hojasrequisicion` WHERE `hojaRequisicion_id` = :idReq";
        $resultado = $conexion->prepare($consulta);
        $resultado->bindParam(':idReq', $idReq, PDO::PARAM_INT);
        $resultado->execute();
        $consulta = "DELETE FROM `requisiciones` WHERE `requisicion_id` = :idReq";
        $resultado = $conexion->prepare($consulta);
        $resultado->bindParam(':idReq', $idReq, PDO::PARAM_INT);
        $resultado->execute();
        $data = 0;
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
