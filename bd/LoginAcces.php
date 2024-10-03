<?php
include_once 'conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

//Conexion con axios, por parametro POST
$_POST = json_decode(file_get_contents("php://input"), true);

//Recepcion de datos por Axios
$Usuario = (isset($_POST['user'])) ? $_POST['user'] : '';
$Contrasena = (isset($_POST['password'])) ? $_POST['password'] : '';
$dato = array(
    'bandera' => 'false',
    'user_id' => 0
);

$consulta = "SELECT `user_password`,`user_id` FROM `users` WHERE `user_password`='$Contrasena';";
$resultado = $conexion->prepare($consulta);
$resultado->execute();
$userArr = $resultado->fetchAll(PDO::FETCH_ASSOC);

if ($userArr[0]["user_password"] == $Contrasena) {
    session_start();
    $_SESSION["Usuario"] = $Usuario;
    $dato['bandera'] = 'true';
    $dato['user_id'] = $userArr[0]["user_id"];
}

print json_encode($dato, JSON_UNESCAPED_UNICODE);
$conexion = NULL;
