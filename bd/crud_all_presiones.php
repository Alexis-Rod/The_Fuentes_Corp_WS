<?php
include_once 'conexion.php';
$objeto = new \Google\Cloud\Samples\CloudSQL\MySQL\Conexion();
$conexion = $objeto->Conectar();

//Conexion con axios, por parametro POST
$_POST = json_decode(file_get_contents("php://input"), true);

$accion = (isset($_POST['accion'])) ? $_POST['accion'] : '';
$id_user = (isset($_POST['id_user'])) ? $_POST['id_user'] : '';
$obras = json_decode((isset($_POST['obras'])) ? $_POST['obras'] : '', true);
$autorizado = (isset($_POST['autorizado'])) ? $_POST['autorizado'] : '';
$idHoja = (isset($_POST['idHoja'])) ? $_POST['idHoja'] : '';
$adeudo = (isset($_POST['parcial'])) ? $_POST['parcial'] : '';
$coments = (isset($_POST['coments'])) ? $_POST['coments'] : '';

switch ($accion) {
    case 1:
        $consulta = "SELECT * FROM `users` WHERE `user_id` = '$id_user';";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 2:
        $consulta = "SELECT * FROM `obras` WHERE `obras_estatus` = 'ACTIVO' ORDER BY `obras_nombre`";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 3:
        $data = array();
        $primeraInt = 0;
        $colapseAtr = "";
        $colapseband = "true";
        $colapseShow = "show";
        foreach ($obras as $obra) {
            $consulta = "SELECT 
                hojaRequisicion_id, 
                requisicion_Clave, 
                requisicion_Numero, 
                hojaRequisicion_observaciones, 
                hojaRequisicion_numero, 
                requisicion_Nombre, 
                proveedor_nombre, 
                hojaRequisicion_total, 
                hojaRequisicion_formaPago, 
                hojaRequisicion_estatus, 
                presiones_estatus, 
                hojaRequisicion_fechaPago, 
                hojasRequisicion_bancoPago,
                hojarequisicion_adeudo,
                presiones_id,
                hojarequisicion_conceptoUnico
            FROM 
                requisicionesligadas 
            JOIN 
                presiones ON presiones.presiones_id =  requisicionesLigada_presionID
            JOIN 
                requisiciones ON requisiciones.requisicion_id = requisicionesLigadas_requisicionID 
            JOIN 
                hojasrequisicion ON hojasrequisicion.hojaRequisicion_id = requisicionesLigadas_hojaID 
            JOIN 
                provedores ON hojasrequisicion.hojaRequisicion_proveedor = provedores.proveedor_id 
            WHERE 
                presiones.presiones_nombre LIKE :nombre 
            AND
                presiones.presiones_estatus = 'PENDIENTE'
            ORDER BY 
                requisicion_Clave DESC";
            // Prepara la consulta
            $resultado = $conexion->prepare($consulta);
            // Define el valor del parámetro
            $nombre = '%' . $obra['obras_nombre'] . '%';
            // Asigna el valor al marcador de posición
            $resultado->bindParam(':nombre', $nombre);
            // Ejecuta la consulta
            $resultado->execute();
            // Obtiene los resultados
            $dataBD = $resultado->fetchAll(PDO::FETCH_ASSOC);
            if (count($dataBD) > 0) {
                $dataPresion = array();
                foreach ($dataBD as $hoja) {
                    $consulta = "SELECT `itemRequisicion_producto` FROM `itemrequisicion` WHERE itemRequisicion_idHoja =" . $hoja["hojaRequisicion_id"];
                    $resultado = $conexion->prepare($consulta);
                    $resultado->execute();
                    $dataitms = $resultado->fetchAll(PDO::FETCH_ASSOC);
                    array_push($dataPresion, array(
                        'id_hoja' => $hoja['hojaRequisicion_id'],
                        'id_presion' => $hoja['presiones_id'],
                        'formaPago' => $hoja['hojaRequisicion_formaPago'],
                        'NumReq' => $hoja['requisicion_Numero'] . " Hoja Numero: " . $hoja['hojaRequisicion_numero'] . " " . $hoja['requisicion_Nombre'],
                        'clave' => $hoja['requisicion_Clave'],
                        'concepto' => empty($hoja['hojarequisicion_conceptoUnico']) ? convertToString($dataitms) : $hoja['hojarequisicion_conceptoUnico'],
                        'proveedor' => $hoja['proveedor_nombre'],
                        'total' => $hoja['hojaRequisicion_total'],
                        'adeudo' => $hoja['hojarequisicion_adeudo'],
                        'Observaciones' => $hoja['hojaRequisicion_observaciones'],
                        "Banco" => $hoja['hojasRequisicion_bancoPago'],
                        "Fecha" => $hoja['hojaRequisicion_fechaPago'],
                        "HojaEstatus" => $hoja['hojaRequisicion_estatus'],
                        "PresionEstatus" => $hoja['presiones_estatus'],
                        "showDetail" => false,
                        "atrClass" => "inline-block text-truncate fs-6",
                        "strStyle" => "max-width: 100px;",
                        'edit_Auto' => false
                    ));
                };
                if ($primeraInt > 0) {
                    $colapseAtr = "collapsed";
                    $colapseband = "false";
                    $colapseShow = "";
                }
                array_push($data, array(
                    'Nombre_Obra' => $obra['obras_nombre'],
                    'Presion_Obra' => $dataPresion,
                    'colapse_Atr' => $colapseAtr,
                    'colapse_band' => $colapseband,
                    'colapse_show' => $colapseShow,
                    'total_Glabal' => sumaTotal($conexion, $dataBD[0]['presiones_id'], "Propuesto"),
                    'total_Global_Aut' => sumaTotal($conexion, $dataBD[0]['presiones_id'], "Auturizado"),
                    'total_Efectivo' => sumaTotalEfectivo($conexion, $dataBD[0]['presiones_id'], "Propuesto"),
                    'total_Efectivo_Aut' => sumaTotalEfectivo($conexion, $dataBD[0]['presiones_id'], "Auturizado"),
                    'total_Transferencia' => sumaTotalTrans($conexion, $dataBD[0]['presiones_id'], "Propuesto"),
                    'total_Transferencia_Aut' => sumaTotalTrans($conexion, $dataBD[0]['presiones_id'], "Auturizado"),
                    'total_Global_Rechazado' => sumaTotal($conexion, $dataBD[0]['presiones_id'], "Rechazado"),
                    'total_Efectivo_Rechazado' => sumaTotalEfectivo($conexion, $dataBD[0]['presiones_id'], "Rechazado"),
                    'total_Transferencia_Rechazado' => sumaTotalTrans($conexion, $dataBD[0]['presiones_id'], "Rechazado")
                ));
                $primeraInt++;
            }
        }
        break;
    case 4:
        if ($autorizado) {
            $consulta = "UPDATE `hojasrequisicion` SET `hojaRequisicion_estatus` = 'AUTORIZADA', `hojarequisicion_adeudo` = '$adeudo' WHERE `hojasrequisicion`.`hojaRequisicion_id` = '$idHoja'";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
        } else {
            $consulta = "UPDATE `hojasrequisicion` SET `hojaRequisicion_estatus` = 'RECHAZADA', `hojarequisicion_adeudo` = 0, `hojarequisicion_comentariosAutorizacion` = '$coments', `hojarequisicion_comentariosValidacion` = '' WHERE `hojasrequisicion`.`hojaRequisicion_id` = '$idHoja'";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
        }
        break;
    case 5:
        $consulta = "UPDATE `hojasrequisicion` SET `hojaRequisicion_estatus` = 'LIGADA' WHERE `hojasrequisicion`.`hojaRequisicion_id` = :id_hoja";
        $resultado = $conexion->prepare($consulta);
        $resultado->bindParam(':id_hoja', $idHoja, PDO::PARAM_INT);
        $resultado->execute();
        $data = 0;
        break;
    case 6:
        $consulta = "UPDATE `hojasrequisicion` SET `hojarequisicion_adeudo` = :adeudo , `hojaRequisicion_observaciones` = :observaciones WHERE `hojasrequisicion`.`hojaRequisicion_id` = :id_hoja";
        $resultado = $conexion->prepare($consulta);
        $resultado->bindValue(':adeudo', (float)$adeudo, PDO::PARAM_STR);
        $resultado->bindValue(':observaciones', $coments, PDO::PARAM_STR);
        $resultado->bindParam(':id_hoja', $idHoja, PDO::PARAM_INT);
        $resultado->execute();
        $data = 0;
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

    // Formatear la cantidad como moneda
    return "$" . number_format($cantidad, 2, '.', '');
}

function sumaTotal($conexion, $idPresion, $tipoDeCuenta)
{
    switch ($tipoDeCuenta) {
        case "Propuesto":
            $consulta = "SELECT SUM(hojasrequisicion.hojaRequisicion_total) AS total_sumatoria
            FROM requisicionesligadas
            INNER JOIN hojasrequisicion
            ON requisicionesligadas.requisicionesLigadas_hojaID = hojasrequisicion.hojaRequisicion_id
            WHERE requisicionesligadas.requisicionesLigada_presionID = :idPresion";

            $resultado = $conexion->prepare($consulta);
            $resultado->bindParam(':idPresion', $idPresion, PDO::PARAM_INT); // Vincula la variable $idPresion al parámetro :idPresion
            $resultado->execute();
            $respuestaBD = $resultado->fetch(PDO::FETCH_ASSOC);
            return $respuestaBD['total_sumatoria'];
            break;
        case "Auturizado":
            $consulta = "SELECT SUM(hojasrequisicion.hojarequisicion_adeudo) AS total_sumatoria
            FROM requisicionesligadas
            INNER JOIN hojasrequisicion
            ON requisicionesligadas.requisicionesLigadas_hojaID = hojasrequisicion.hojaRequisicion_id
            WHERE requisicionesligadas.requisicionesLigada_presionID = :idPresion";

            $resultado = $conexion->prepare($consulta);
            $resultado->bindParam(':idPresion', $idPresion, PDO::PARAM_INT); // Vincula la variable $idPresion al parámetro :idPresion
            $resultado->execute();
            $respuestaBD = $resultado->fetch(PDO::FETCH_ASSOC);
            return $respuestaBD['total_sumatoria'];
            break;
        case "Rechazado":
            $consulta = "SELECT SUM(hojasrequisicion.hojaRequisicion_total - hojasrequisicion.hojarequisicion_adeudo) AS total_sumatoria
            FROM requisicionesligadas
            INNER JOIN hojasrequisicion
            ON requisicionesligadas.requisicionesLigadas_hojaID = hojasrequisicion.hojaRequisicion_id
            WHERE requisicionesligadas.requisicionesLigada_presionID = :idPresion
            AND hojasrequisicion.hojaRequisicion_estatus != 'LIGADA'";

            $resultado = $conexion->prepare($consulta);
            $resultado->bindParam(':idPresion', $idPresion, PDO::PARAM_INT); // Vincula la variable $idPresion al parámetro :idPresion
            $resultado->execute();
            $respuestaBD = $resultado->fetch(PDO::FETCH_ASSOC);
            return $respuestaBD['total_sumatoria'] ?? 0;
            break;
        default:
            return "Dato no válido";
            break;
    }
}

function sumaTotalEfectivo($conexion, $idPresion, $tipoDeCuenta)
{
    switch ($tipoDeCuenta) {
        case "Propuesto":
            $consulta = "SELECT SUM(hojasrequisicion.hojaRequisicion_total) AS total_sumatoria
            FROM requisicionesligadas
            INNER JOIN hojasrequisicion
            ON requisicionesligadas.requisicionesLigadas_hojaID = hojasrequisicion.hojaRequisicion_id
            WHERE requisicionesligadas.requisicionesLigada_presionID = :idPresion
            AND hojasrequisicion.hojaRequisicion_formaPago = 'Efectivo'";

            $resultado = $conexion->prepare($consulta);
            $resultado->bindParam(':idPresion', $idPresion, PDO::PARAM_INT); // Vincula la variable $idPresion al parámetro :idPresion
            $resultado->execute();
            $respuestaBD = $resultado->fetch(PDO::FETCH_ASSOC);
            return $respuestaBD['total_sumatoria'];
            break;
        case "Auturizado":
            $consulta = "SELECT SUM(hojasrequisicion.hojarequisicion_adeudo) AS total_sumatoria
            FROM requisicionesligadas
            INNER JOIN hojasrequisicion
            ON requisicionesligadas.requisicionesLigadas_hojaID = hojasrequisicion.hojaRequisicion_id
            WHERE requisicionesligadas.requisicionesLigada_presionID = :idPresion
            AND hojasrequisicion.hojaRequisicion_formaPago = 'Efectivo'";

            $resultado = $conexion->prepare($consulta);
            $resultado->bindParam(':idPresion', $idPresion, PDO::PARAM_INT); // Vincula la variable $idPresion al parámetro :idPresion
            $resultado->execute();
            $respuestaBD = $resultado->fetch(PDO::FETCH_ASSOC);
            return $respuestaBD['total_sumatoria'];
            break;
        case "Rechazado":
            $consulta = "SELECT SUM(hojasrequisicion.hojaRequisicion_total - hojasrequisicion.hojarequisicion_adeudo) AS total_sumatoria
            FROM requisicionesligadas
            INNER JOIN hojasrequisicion
            ON requisicionesligadas.requisicionesLigadas_hojaID = hojasrequisicion.hojaRequisicion_id
            WHERE requisicionesligadas.requisicionesLigada_presionID = :idPresion
            AND hojasrequisicion.hojaRequisicion_estatus != 'LIGADA'
            AND hojasrequisicion.hojaRequisicion_formaPago = 'Efectivo'";

            $resultado = $conexion->prepare($consulta);
            $resultado->bindParam(':idPresion', $idPresion, PDO::PARAM_INT); // Vincula la variable $idPresion al parámetro :idPresion
            $resultado->execute();
            $respuestaBD = $resultado->fetch(PDO::FETCH_ASSOC);
            return $respuestaBD['total_sumatoria'] ?? 0;
            break;
        default:
            return "Dato no válido";
            break;
    }
}

function sumaTotalTrans($conexion, $idPresion, $tipoDeCuenta)
{
    switch ($tipoDeCuenta) {
        case "Propuesto":
            $consulta = "SELECT SUM(hojasrequisicion.hojaRequisicion_total) AS total_sumatoria
            FROM requisicionesligadas
            INNER JOIN hojasrequisicion
            ON requisicionesligadas.requisicionesLigadas_hojaID = hojasrequisicion.hojaRequisicion_id
            WHERE requisicionesligadas.requisicionesLigada_presionID = :idPresion
            AND hojasrequisicion.hojaRequisicion_formaPago = 'Transferencia'";

            $resultado = $conexion->prepare($consulta);
            $resultado->bindParam(':idPresion', $idPresion, PDO::PARAM_INT); // Vincula la variable $idPresion al parámetro :idPresion
            $resultado->execute();
            $respuestaBD = $resultado->fetch(PDO::FETCH_ASSOC);
            return $respuestaBD['total_sumatoria'];
            break;
        case "Auturizado":
            $consulta = "SELECT SUM(hojasrequisicion.hojarequisicion_adeudo) AS total_sumatoria
            FROM requisicionesligadas
            INNER JOIN hojasrequisicion
            ON requisicionesligadas.requisicionesLigadas_hojaID = hojasrequisicion.hojaRequisicion_id
            WHERE requisicionesligadas.requisicionesLigada_presionID = :idPresion
            AND hojasrequisicion.hojaRequisicion_formaPago = 'Transferencia'";

            $resultado = $conexion->prepare($consulta);
            $resultado->bindParam(':idPresion', $idPresion, PDO::PARAM_INT); // Vincula la variable $idPresion al parámetro :idPresion
            $resultado->execute();
            $respuestaBD = $resultado->fetch(PDO::FETCH_ASSOC);
            return $respuestaBD['total_sumatoria'];
            break;
        case "Rechazado":
            $consulta = "SELECT SUM(hojasrequisicion.hojaRequisicion_total - hojasrequisicion.hojarequisicion_adeudo) AS total_sumatoria
            FROM requisicionesligadas
            INNER JOIN hojasrequisicion
            ON requisicionesligadas.requisicionesLigadas_hojaID = hojasrequisicion.hojaRequisicion_id
            WHERE requisicionesligadas.requisicionesLigada_presionID = :idPresion
            AND hojasrequisicion.hojaRequisicion_estatus != 'LIGADA'
            AND hojasrequisicion.hojaRequisicion_formaPago = 'Transferencia'";

            $resultado = $conexion->prepare($consulta);
            $resultado->bindParam(':idPresion', $idPresion, PDO::PARAM_INT); // Vincula la variable $idPresion al parámetro :idPresion
            $resultado->execute();
            $respuestaBD = $resultado->fetch(PDO::FETCH_ASSOC);
            return $respuestaBD['total_sumatoria'] ?? 0;
            break;
        default:
            return "Dato no válido";
            break;
    }
}
