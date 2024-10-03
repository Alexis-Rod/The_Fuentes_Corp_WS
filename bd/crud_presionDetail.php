<?php
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=Presion_de Gastos_".date('Y:m:d:m:s').".xls");
header("Pragma: no-cache");
header("Expires: 0");
include_once 'conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

//Conexion con axios, por parametro POST
$_POST = json_decode(file_get_contents("php://input"), true);

$accion = (isset($_POST['accion'])) ? $_POST['accion'] : '';
$id_user = (isset($_POST['id_user'])) ? $_POST['id_user'] : '';
$obra = (isset($_POST['obra'])) ? $_POST['obra'] : '';
$dia = (isset($_POST['dia'])) ? $_POST['dia'] : '';
$semana = (isset($_POST['semana'])) ? $_POST['semana'] : '';
$idReq = (isset($_POST['idReq'])) ? $_POST['idReq'] : '';
$parcial = (isset($_POST['parcial'])) ? $_POST['parcial'] : '';
$fechaPago = (isset($_POST['fechaPago'])) ? $_POST['fechaPago'] : '';
$bancoPago = (isset($_POST['bancoPago'])) ? $_POST['bancoPago'] : '';
$estatus = (isset($_POST['status'])) ? $_POST['status'] : '';
$time = (isset($_POST['time'])) ? $_POST['time'] : '';
$output = "";

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
        $consulta = "SELECT `itemRequisicion_id`,`presiones_clave`,`requisicion_Numero`,`proveedor_nombre`,`itemRequisicion_producto`,`requisicion_total`,`requisicion_observaciones`,`requisicion_formaPago`,`itemRequisicion_parcialidad`,`itemRequisicion_fechaPago`,`itemRequisicion_bancoPago` FROM `presiones`\n"
            . "JOIN requisiciones\n"
            . "ON presiones.presiones_id = requisiciones.requisicion_idPresion\n"
            . "JOIN itemrequisicion\n"
            . "on requisiciones.requisicion_id = itemrequisicion.itemRequisicion_idReq\n"
            . "JOIN provedores\n"
            . "on requisiciones.requisicion_receptorID = provedores.proveedor_id\n"
            . "WHERE`presiones_semana`= '$semana' AND `presiones_dia` = '$dia' AND `presiones_obra` = '$obra'\n"
            . "ORDER BY `presiones_clave` DESC;";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 4:
        $consulta = "SELECT `obras_nombre` FROM `obras` WHERE `obras_id`= '$obra'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 5:
        $consulta = "INSERT INTO `logs` (`log_id`, `log_accion`, `log_fechaAccion`, `log_usuario`, `log_horaAccion`, `log_moduloAccion`) VALUES (NULL, 'Agregar', '$fechaPago', '$id_user', '$time', 'Presion Detalle')";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $consulta = "UPDATE `itemrequisicion` SET `itemRequisicion_parcialidad` = '$parcial', `itemRequisicion_fechaPago` = '$fechaPago', `itemRequisicion_bancoPago` = '$bancoPago' WHERE `itemrequisicion`.`itemRequisicion_id` = '$idReq'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        break;
    case 6:
        if(isset($_POST["export"])){
            $output .="
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID</th>
                            <th>ID</th>
                            <th>ID</th>
                        </tr>
                    </thead>
                    <tbody>
                         <tr>
                            <th>ID</th>
                            <th>ID</th>
                            <th>ID</th>
                            <th>ID</th>
                        </tr>
                         <tr>
                            <th>ID</th>
                            <th>ID</th>
                            <th>ID</th>
                            <th>ID</th>
                        </tr>
                    </tbody>
                </table>
                ";
             echo $output; 
             $data = $output;  
        }
        break;
}

print json_encode($data, JSON_UNESCAPED_UNICODE);
$conexion = NULL;
