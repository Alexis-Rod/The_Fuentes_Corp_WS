<?php
include_once 'conexion.php';
$objeto = new \Google\Cloud\Samples\CloudSQL\MySQL\Conexion();
$conexion = $objeto->Conectar();

//Conexion con axios, por parametro POST
$_POST = json_decode(file_get_contents("php://input"), true);

$accion = (isset($_POST['accion'])) ? $_POST['accion'] : '';
$id_user = (isset($_POST['id_user'])) ? $_POST['id_user'] : '';
$obra = (isset($_POST['obra'])) ? $_POST['obra'] : '';

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
        $consulta = "SELECT `obras_nombre` FROM `obras` WHERE `obras_id` =" . $obra;
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
}

print json_encode($data, JSON_UNESCAPED_UNICODE);
$conexion = NULL;