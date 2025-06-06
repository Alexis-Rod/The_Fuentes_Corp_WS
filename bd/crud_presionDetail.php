<?php
header("Content-Type: application/xls");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=UTF-8');
header("Content-Disposition: attachment; filename=Presion_de Gastos_" . date('Y:m:d:m:s') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
include_once 'conexion.php';
$objeto = new \Google\Cloud\Samples\CloudSQL\MySQL\Conexion();
$conexion = $objeto->Conectar();

//Conexion con axios, por parametro POST
$_POST = json_decode(file_get_contents("php://input"), true);

$accion = (isset($_POST['accion'])) ? $_POST['accion'] : '';
$id_user = (isset($_POST['id_user'])) ? $_POST['id_user'] : '';
$obra = (isset($_POST['obra'])) ? $_POST['obra'] : '';
$dia = (isset($_POST['dia'])) ? $_POST['dia'] : '';
$semana = (isset($_POST['semana'])) ? $_POST['semana'] : '';
$idHoja = (isset($_POST['idHoja'])) ? $_POST['idHoja'] : '';
$idPresion = (isset($_POST['idPresion'])) ? $_POST['idPresion'] : '';
$fechaPago = (isset($_POST['fechaPago'])) ? $_POST['fechaPago'] : '';
$bancoPago = (isset($_POST['bancoPago'])) ? $_POST['bancoPago'] : '';
$estatus = (isset($_POST['status'])) ? $_POST['status'] : '';
$time = (isset($_POST['time'])) ? $_POST['time'] : '';
$autorizado = (isset($_POST['autorizado'])) ? $_POST['autorizado'] : '';
$DatosExport = json_decode((isset($_POST['datos'])) ? $_POST['datos'] : '', true);
$NombrePress = (isset($_POST['namePres'])) ? $_POST['namePres'] : '';
$total = (isset($_POST['total'])) ? $_POST['total'] : '';
$adeudo = (isset($_POST['adeudo'])) ? $_POST['adeudo'] : '';
$output = "";
$textExpecial = "";

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
        $consulta = "SELECT `hojaRequisicion_id`, `requisicion_Clave`, `requisicion_Numero`, `hojaRequisicion_observaciones`, `hojaRequisicion_numero`, `requisicion_Nombre`, `proveedor_nombre`, `hojaRequisicion_total`, `hojaRequisicion_formaPago`, `hojaRequisicion_estatus`, `presiones_estatus`, `hojaRequisicion_fechaPago`, `hojasRequisicion_bancoPago` , `hojarequisicion_adeudo`,  `hojarequisicion_conceptoUnico`\n"
            . "FROM `requisicionesligadas`\n"
            . "JOIN presiones ON presiones.presiones_id = requisicionesLigada_presionID\n"
            . "JOIN requisiciones ON requisiciones.requisicion_id = requisicionesLigadas_requisicionID\n"
            . "JOIN hojasrequisicion ON hojasrequisicion.hojaRequisicion_id = requisicionesLigadas_hojaID\n"
            . "JOIN provedores ON hojasrequisicion.hojaRequisicion_proveedor = provedores.proveedor_id\n"
            . "WHERE requisicionesLigada_presionID = '$idPresion'\n"
            . "ORDER BY `requisicion_Clave` DESC";

        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $dataBD = $resultado->fetchAll(PDO::FETCH_ASSOC);
        $data = array();

        foreach ($dataBD as $hoja) {
            $consulta = "SELECT `itemRequisicion_producto` FROM `itemrequisicion` WHERE itemRequisicion_idHoja =" . $hoja["hojaRequisicion_id"];
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
            $dataitms = $resultado->fetchAll(PDO::FETCH_ASSOC);
            array_push($data, array(
                'id_hoja' => $hoja['hojaRequisicion_id'],
                'formaPago' => $hoja['hojaRequisicion_formaPago'],
                'NumReq' => $hoja['requisicion_Numero'] . " Hoja Numero: " . $hoja['hojaRequisicion_numero'] . " " . $hoja['requisicion_Nombre'],
                'clave' => $hoja['requisicion_Clave'],
                'concepto' => empty($hoja['hojarequisicion_conceptoUnico']) ? convertToString($dataitms) : $hoja['hojarequisicion_conceptoUnico'],
                'proveedor' => $hoja['proveedor_nombre'],
                'total' => $hoja['hojaRequisicion_total'],
                'Observaciones' => $hoja['hojaRequisicion_observaciones'],
                "Banco" => $hoja['hojasRequisicion_bancoPago'],
                "Fecha" => $hoja['hojaRequisicion_fechaPago'],
                "HojaEstatus" => $hoja['hojaRequisicion_estatus'],
                "PresionEstatus" => $hoja['presiones_estatus'],
                "adeudo" => $hoja['hojarequisicion_adeudo'],
                "NumRequi" => $hoja['requisicion_Numero'],
                "showDetail" => false,
                "atrClass" => "text-left align-middle inline-block text-truncate fs-6",
                "strStyle" => "max-width: 100px;"
            ));
        };
        break;
    case 4:
        $consulta = "SELECT `obras_nombre` FROM `obras` WHERE `obras_id`= '$obra'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 5:
        /*  $consulta = "INSERT INTO `logs` (`log_id`, `log_accion`, `log_fechaAccion`, `log_usuario`, `log_horaAccion`, `log_moduloAccion`) VALUES (NULL, 'Agregar', '$fechaPago', '$id_user', '$time', 'Presion Detalle')";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute(); */
        $consulta = "UPDATE `hojasrequisicion` SET `hojaRequisicion_estatus` = 'PAGADA', `hojaRequisicion_fechaPago` = '$fechaPago', `hojasRequisicion_bancoPago` = '$bancoPago' WHERE `hojasrequisicion`.`hojaRequisicion_id` = '$idHoja'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        break;
    case 6:
        if (isset($_POST["export"])) {
            $textExpecial .= '
                <table border="1">
                    <thead>
                        <tr bgcolor="orange">
                            <th colspan="11">PRESION OBRA: ' . $NombrePress . ' (' . date('d/m/Y') . ')</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr bgcolor="yellow">
                            <th>CLAVE</th>
                            <th>NUMERO DE REQUISICION</th>
                            <th>PROVEEDOR</th>
                            <th>CONCEPTO</th>
                            <th>ADEUDO</th>
                            <th>PAGO PROGRAMADO</th>
                            <th>NETO</th>
                            <th>OBSERVACIONES</th>
                            <th>FORMA DE PAGO</th>
                            <th>FECHA DE PAGO</th>
                            <th>BANCO DE PAGO</th>
                        </tr>
                    </thead>';
            $textExpecial .= "<tbody>";
            $indexAct = 0;
            $indexNext = 1;
            foreach ($DatosExport as $datosExcel) {
                if ($indexAct == 0) {
                    $textExpecial .= '<tr bgcolor="Silver"><th colspan="11">' . putNameSection($datosExcel['clave']) . "</th></tr>";
                };
                if ($datosExcel['formaPago'] == 'Efectivo') {
                    $textExpecial .= '
                         <tr style="color: red;">
                           <th>' . $datosExcel['clave'] . '</th>
                            <th>' . $datosExcel['NumReq'] . '</th>
                            <th>' . $datosExcel['proveedor'] . '</th>
                            <th>' . $datosExcel['concepto'] . '</th>
                            <th>' . formatearMoneda($datosExcel['total']) . '</th>
                            <th>  </th>
                            <th>' . formatearMoneda($datosExcel['adeudo']) . '</th>
                            <th>' . $datosExcel['Observaciones'] . '</th>
                            <th>' . $datosExcel['formaPago'] . '</th>
                            <th>' . $datosExcel['Fecha'] . '</th>
                            <th>' . $datosExcel['Banco'] . '</th>
                        </tr>
                ';
                } else {
                    $textExpecial .= "
                         <tr>
                           <th>" . $datosExcel['clave'] . "</th>
                            <th>" . $datosExcel['NumReq'] . "</th>
                            <th>" . $datosExcel['proveedor'] . "</th>
                            <th>" . $datosExcel['concepto'] . "</th>
                            <th>" . formatearMoneda($datosExcel['total']) . "</th>
                            <th>  </th>
                            <th>" . formatearMoneda($datosExcel['adeudo']) . "</th>
                            <th>" . $datosExcel['Observaciones'] . "</th>
                            <th>" . $datosExcel['formaPago'] . "</th>
                            <th>" . $datosExcel['Fecha'] . "</th>
                            <th>" . $datosExcel['Banco'] . "</th>
                        </tr>
                ";
                }
                if ($indexNext < count($DatosExport)) {
                    if ($DatosExport[$indexAct]['clave'] != $DatosExport[$indexNext]['clave']) {
                        $textExpecial .= '<tr bgcolor="Silver"><th colspan="11">' . putNameSection($DatosExport[$indexNext]['clave']) . "</th></tr>";
                    };
                }
                $indexAct++;
                $indexNext++;
            }
            $textExpecial .= '<tr bgcolor="yellow">
                            <th colspan="4" style="text-align: right;">GRAN TOTAL ' . $NombrePress . "</th>
                            <th> " . formatearMoneda($total) . " </th>
                            <th>  </th>
                            <th> " . formatearMoneda($adeudo) . " </th>
                            <th>  </th>
                            <th>  </th>
                            <th>  </th>
                            <th>  </th>
                        </tr>";
            $textExpecial .= "</tbody></table>";
            $output = mb_convert_encoding($textExpecial, 'UTF-8', 'auto');
            echo $output;
            $data = "PRESION GENERADA DESDE EL SISTEMA THE FUENTES WS";
        }
        break;
    case 7:
        $consulta = "UPDATE `presiones` SET `presiones_estatus` = 'AUTORIZADO' WHERE `presiones`.`presiones_id` = '$idPresion'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        break;
    case 8:
        $data = array();
        $consulta = "SELECT * FROM `hojasrequisicion` INNER JOIN emisores ON hojasrequisicion.hojaRequisicion_empresa = emisores.emisor_id INNER JOIN provedores ON hojasrequisicion.hojaRequisicion_proveedor = provedores.proveedor_id WHERE hojaRequisicion_id ='$idHoja'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $dataHoja = $resultado->fetchAll(PDO::FETCH_ASSOC);
        $consulta = "SELECT `itemRequisicion_id`, `itemRequisicion_unidad`, `itemRequisicion_producto`, `itemRequisicion_iva`, `itemRequisicion_retenciones`, `itemRequisicion_banderaFlete`, `itemRequisicion_banderaFisica`, `itemRequisicion_banderaResico`, `itemRequisicion_precio`, `itemRequisicion_cantidad`, `itemRequisicion_estatus`, `hojaRequisicion_total` FROM itemrequisicion INNER JOIN hojasrequisicion ON itemRequisicion_idHoja = hojasrequisicion.hojaRequisicion_id WHERE itemRequisicion_idHoja = '$idHoja' ORDER BY itemRequisicion_id ASC";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $dataItems = $resultado->fetchAll(PDO::FETCH_ASSOC);
        array_push($data, array(
            'infoHoja' => $dataHoja[0],
            'items' => $dataItems
        ));
        break;
    case 9:
        $consulta = "SELECT `presiones_estatus` FROM `presiones` WHERE presiones_id = '$idPresion';";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
}

print json_encode($data, JSON_UNESCAPED_UNICODE);
$conexion = NULL;


function convertToString($Arr)
{
    $result = "";
    $indes = 0;
    foreach ($Arr as $cadenaAux) {
        $result = $result . $cadenaAux['itemRequisicion_producto'];
        if ($indes < count($Arr) - 1) {
            $result = $result . " /// ";
        }
        $indes++;
    };
    return $result;
}

function formatearMoneda($cantidad)
{
    // Asegurarse de que la cantidad sea un número
    if (!is_numeric($cantidad)) {
        return "Entrada no válida";
    }

    // Formatear la cantidad como moneda con separadores de miles
    return "$ " . number_format($cantidad, 2, '.', ',');
}

function putNameSection($clave)
{
    switch ($clave) {
        case 'MAT':
            return 'MATERIAL';
        case 'EQH':
            return 'MAQUINARIA';
        case 'IND':
            return 'INDIRECTOS';
        case 'MO':
            return 'MANO DE OBRA';
    }
}
