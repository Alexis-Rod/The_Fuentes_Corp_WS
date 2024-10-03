<?php
include_once 'conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

//Conexion con axios, por parametro POST
$_POST = json_decode(file_get_contents("php://input"), true);

$accion = (isset($_POST['accion'])) ? $_POST['accion'] : '';
$id_user = (isset($_POST['id_user'])) ? $_POST['id_user'] : '';
$nombre = (isset($_POST['nombre'])) ? $_POST['nombre'] : '';
$direccion = (isset($_POST['direccion'])) ? $_POST['direccion'] : '';
$rfc = (isset($_POST['rfc'])) ? $_POST['rfc'] : '';
$clabe = (isset($_POST['clabe'])) ? $_POST['clabe'] : '';
$cuenta = (isset($_POST['cuenta'])) ? $_POST['cuenta'] : '';
$tarjeta = (isset($_POST['tarjeta'])) ? $_POST['tarjeta'] : '';
$referencia = (isset($_POST['referencia'])) ? $_POST['referencia'] : '';
$banco = (isset($_POST['banco'])) ? $_POST['banco'] : '';
$tipoProv = (isset($_POST['tipoProv'])) ? $_POST['tipoProv'] : '';
$sucursal = (isset($_POST['sucursal'])) ? $_POST['sucursal'] : '';
$telefono = (isset($_POST['telefono'])) ? $_POST['telefono'] : '';
$correo = (isset($_POST['correo'])) ? $_POST['correo'] : '';

switch ($accion) {
    case 1:
        $consulta = "SELECT * FROM `bancos` WHERE `banco_activo` = 1";
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
        $consulta = "SELECT * FROM `obras` WHERE `obras_estatus` = 'ACTIVO'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 4:
        $consulta = "INSERT INTO `provedores` (`proveedor_id`, `proveedor_nombre`, `presiones_type`, `proveedor_rfc`, `proveedor_clabe`, `proveedor_numeroCuenta`, `proveedor_sucursal`, `proveedor_refBanco`, `presiones_tarjetaBanco`, `proveedor_banco`, `proveedor_email`, `proveedor_telefono`, `proveedor_estatus`) VALUES (NULL, '$nombre', '$tipoProv', '$rfc', '$clabe', '$cuenta', '$sucursal', '$referencia', '$tarjeta', '$banco', '$correo', '$telefono', 'ACTIVO')";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        break;
}

print json_encode($data, JSON_UNESCAPED_UNICODE);
$conexion = NULL;
