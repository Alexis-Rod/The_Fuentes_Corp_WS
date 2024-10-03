<?php
include_once 'conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

//Conexion con axios, por parametro POST
$_POST = json_decode(file_get_contents("php://input"), true);

$accion = (isset($_POST['accion'])) ? $_POST['accion'] : '';
$id_user = (isset($_POST['id_user'])) ? $_POST['id_user'] : '';
$semana = (isset($_POST['semana'])) ? $_POST['semana'] : '';
$dia = (isset($_POST['dia'])) ? $_POST['dia'] : '';
$clave = (isset($_POST['clave'])) ? $_POST['clave'] : '';
$fecha = (isset($_POST['fecha'])) ? $_POST['fecha'] : '';
$user_creado = (isset($_POST['user_creado'])) ? $_POST['user_creado'] : '';
$obra = (isset($_POST['obra'])) ? $_POST['obra'] : '';
$alias = (isset($_POST['alias'])) ? $_POST['alias'] : '';
$time = (isset($_POST['time'])) ? $_POST['time'] : '';

switch ($accion) {
    case 1:
        $consulta = "SELECT `presiones_id`,`presiones_nombre`, `presiones_alias`, `presiones_estatus`,`presiones_semana`,`presiones_dia` FROM `presiones` WHERE `presiones_obra` = " . $obra;
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
        $consulta = "INSERT INTO `logs` (`log_id`, `log_accion`, `log_fechaAccion`, `log_usuario`, `log_horaAccion`, `log_moduloAccion`) VALUES (NULL, 'Agregar', '$fecha', '$id_user', '$time', 'Presiones')";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $consulta = "SELECT `obras_nombre`,`ciudadesObras_codigo` FROM `obras` JOIN estadosobra ON estadosobra.ciudadesObras_id = obras.obras_cuidad WHERE `obras_id` = '$obra'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        $nombre_presion = $data[0]['ciudadesObras_codigo'] . "-" . $data[0]['obras_nombre'];
        $consulta = "SELECT * FROM `presiones` WHERE `presiones_clave` LIKE '$clave' AND `presiones_obra` = '$obra'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        if (count($data) == 0) {
            $nombre_presion = $nombre_presion . "-" . $clave . "-000";
            $consulta = "INSERT INTO `presiones` (`presiones_id`, `presiones_nombre`, `presiones_alias`, `presiones_semana`, `presiones_dia`, `presiones_clave`, `presiones_adeudo`, `presiones_fechaCreacion`, `presiones_gastosObra`, `presiones_obra`, `presiones_userCreado`, `presiones_userValidado`, `presiones_folio`, `presiones_hojas`, `presiones_estatus`) VALUES (NULL, '$nombre_presion', '$alias', '$semana', '$dia', '$clave', '0', '$fecha', '0', '$obra', '$user_creado', '', '0', '1', 'PENDIENTE')";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
        } else {
            $consulta = "SELECT `presiones_folio` FROM `presiones` WHERE `presiones_clave` =  '$clave';";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
            $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
            $folio = $data[count($data) - 1]['presiones_folio'] + 1;
            $nombre_presion = $nombre_presion . "-" . $clave . "-" . convertFolio($folio);
            $consulta = "INSERT INTO `presiones` (`presiones_id`, `presiones_nombre`, `presiones_alias`, `presiones_semana`, `presiones_dia`, `presiones_clave`, `presiones_adeudo`, `presiones_fechaCreacion`, `presiones_gastosObra`, `presiones_obra`, `presiones_userCreado`, `presiones_userValidado`, `presiones_folio`, `presiones_hojas`, `presiones_estatus`) VALUES (NULL, '$nombre_presion', '$alias', '$semana', '$dia', '$clave', '0', '$fecha', '0', '$obra', '$user_creado', '', '$folio', '1', 'PENDIENTE')";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
        }
        break;
    case 4:
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
