<?php
include_once 'conexion.php';
$objeto = new \Google\Cloud\Samples\CloudSQL\MySQL\Conexion();
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
        $consulta = "SELECT `presiones_id`,`presiones_nombre`, `presiones_alias`, `presiones_estatus`,`presiones_semana`,`presiones_dia`,`presiones_estatus` FROM `presiones` WHERE `presiones_obra` = " . $obra." ORDER BY presiones_fechaCreacion DESC";
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
        /*  $consulta = "INSERT INTO `logs` (`log_id`, `log_accion`, `log_fechaAccion`, `log_usuario`, `log_horaAccion`, `log_moduloAccion`) VALUES (NULL, 'Agregar', '$fecha', 0, '$time', 'Presiones')";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute(); */
        $consulta = "SELECT `obras_nombre`,`ciudadesObras_codigo` FROM `obras` JOIN estadosobra ON estadosobra.ciudadesObras_id = obras.obras_cuidad WHERE `obras_id` = '$obra'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        $nombre_presion = $data[0]['obras_nombre'] . "-" . $semana . "-" . $dia;
        $consulta = "INSERT INTO `presiones` (`presiones_id`, `presiones_nombre`, `presiones_alias`, `presiones_semana`, `presiones_dia`, `presiones_adeudo`, `presiones_fechaCreacion`, `presiones_gastosObra`, `presiones_obra`, `presiones_userCreado`, `presiones_userValidado`, `presiones_estatus`) VALUES (NULL, '$nombre_presion', '$alias', '$semana', '$dia', '0', '$fecha', '0', '$obra', '$user_creado', '', 'PENDIENTE')";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        break;
    case 4:
        $consulta = "SELECT `obras_nombre` FROM `obras` WHERE `obras_id` =" . $obra;
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
