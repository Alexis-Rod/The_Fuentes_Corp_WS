<?php
include_once 'conexion.php';
$objeto = new \Google\Cloud\Samples\CloudSQL\MySQL\Conexion();
$conexion = $objeto->Conectar();

//Conexion con axios, por parametro POST
$_POST = json_decode(file_get_contents("php://input"), true);

$dig1 = random_int(1, 9);
$dig2 = random_int(1, 9);
$dig3 = random_int(1, 9);
$dig4 = random_int(1, 9);
$dig5 = random_int(1, 9);
$dig6 = random_int(1, 9);
$Folio = "" . $dig1 . $dig2 . $dig3 . $dig4 . $dig5 . $dig6;

//Recepcion de datos por Axios
$id_hoja = intval($Folio);
$id_Req = (isset($_POST['idReq'])) ? $_POST['idReq'] : '';
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : '';
$clv_Emisor = (isset($_POST['id_emisor'])) ? $_POST['id_emisor'] : '';
$clv_Prov = (isset($_POST['id_prov'])) ? $_POST['id_prov'] : '';
$totalPagar = (isset($_POST['Total'])) ? $_POST['Total'] : '';
$formaPago = (isset($_POST['formaPago'])) ? $_POST['formaPago'] : '';
$fechaSolicitud = (isset($_POST['fechaSolicitud'])) ? $_POST['fechaSolicitud'] : '';
$datos = json_decode((isset($_POST['items'])) ? $_POST['items'] : '');
$id_user = (isset($_POST['id_user'])) ? $_POST['id_user'] : '';
$observaciones = (isset($_POST['observaciones'])) ? $_POST['observaciones'] : '';
$time = (isset($_POST['time'])) ? $_POST['time'] : '';
$conceptoUnico = (isset($_POST['conceptoUnico'])) ? $_POST['conceptoUnico'] : '';

switch ($accion) {
    case 1:
         try {
        $conexion->beginTransaction();

        // 1) Lee y bloquea la fila para calcular $hoja sin condiciones de carrera
        $consulta = "SELECT `requisicion_Hojas` 
                    FROM `requisiciones` 
                    WHERE `requisicion_id` = :id_req
                    FOR UPDATE";
        $resultado = $conexion->prepare($consulta);
        $resultado->bindValue(':id_req', $id_Req, PDO::PARAM_INT);
        $resultado->execute();

        $row = $resultado->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            throw new RuntimeException('No existe la requisición con id ' . $id_Req);
        }

        $hoja = (int)$row['requisicion_Hojas'];
        $hoja++; // siguiente número de hoja

        // 2) Inserta la hoja
        $consulta = "INSERT INTO `hojasrequisicion` (
            `hojaRequisicion_id`,
            `hojaRequisicion_idReq`,
            `hojaRequisicion_numero`,
            `hojaRequisicion_FechaSolicitud`,
            `hojaRequisicion_empresa`,
            `hojaRequisicion_proveedor`,
            `hojaRequisicion_observaciones`,
            `hojarequisicion_comentariosValidacion`,
            `hojarequisicion_comentariosAutorizacion`,
            `hojarequisicion_conceptoUnico`,
            `hojaRequisicion_formaPago`,
            `hojaRequisicion_fechaPago`,
            `hojasRequisicion_bancoPago`,
            `hojaRequisicion_total`,
            `hojarequisicion_adeudo`,
            `hojaRequisicion_estatus`
        ) VALUES (
            :id_hoja,
            :id_req,
            :numero,
            :fecha_solicitud,
            :empresa,
            :proveedor,
            :observaciones,
            :coment_val,
            :coment_aut,
            :concepto_unico,
            :forma_pago,
            :fecha_pago,
            :banco_pago,
            :total,
            :adeudo,
            :estatus
        )";

        $resultado = $conexion->prepare($consulta);

        // OJO: aquí había un bug: usabas $resultado = bindValue(...). Debe ser ->bindValue
        $resultado->bindValue(':id_hoja',        $id_hoja, PDO::PARAM_INT);
        $resultado->bindValue(':id_req',         $id_Req,  PDO::PARAM_INT);
        $resultado->bindValue(':numero',         $hoja,    PDO::PARAM_INT);

        // fecha: si viene vacía, manda NULL real
        if (!isset($fechaSolicitud) || $fechaSolicitud === '' || $fechaSolicitud === null) {
            $resultado->bindValue(':fecha_solicitud', null, PDO::PARAM_NULL);
        } else {
            $resultado->bindValue(':fecha_solicitud', $fechaSolicitud, PDO::PARAM_STR);
        }

        $resultado->bindValue(':empresa',        $clv_Emisor, PDO::PARAM_INT);
        $resultado->bindValue(':proveedor',      $clv_Prov,   PDO::PARAM_INT);
        $resultado->bindValue(':observaciones',  $observaciones ?? '', PDO::PARAM_STR);

        // estos dos los estás usando como NULL explícito
        $resultado->bindValue(':coment_val',     $comentariosValidacion,   PDO::PARAM_NULL);
        $resultado->bindValue(':coment_aut',     $comentariosAutorizacion, PDO::PARAM_NULL);

        $resultado->bindValue(':concepto_unico', $conceptoUnico ?? '', PDO::PARAM_STR);
        $resultado->bindValue(':forma_pago',     $formaPago, PDO::PARAM_STR);

        // también NULL reales
        $resultado->bindValue(':fecha_pago',     $fechaPago,  PDO::PARAM_NULL);
        $resultado->bindValue(':banco_pago',     $bancoPago,  PDO::PARAM_NULL);

        // DECIMAL como string para evitar líos de locale/comas
        $resultado->bindValue(':total',          $totalPagar, PDO::PARAM_STR);
        $resultado->bindValue(':adeudo',         $totalPagar, PDO::PARAM_STR);

        $resultado->bindValue(':estatus',        'NUEVO', PDO::PARAM_STR);

        $resultado->execute();

        // 3) Actualiza el contador de hojas con parámetros (sin concatenar)
        $consulta = "UPDATE `requisiciones` 
                    SET `requisicion_Hojas` = :hoja 
                    WHERE `requisiciones`.`requisicion_id` = :id_req";
        $resultado = $conexion->prepare($consulta);
        $resultado->bindValue(':hoja',   $hoja,   PDO::PARAM_INT);
        $resultado->bindValue(':id_req', $id_Req, PDO::PARAM_INT);
        $resultado->execute();

        // 4) Inserta los ítems. Reusa el statement para rendimiento.
        $consulta = "INSERT INTO `itemrequisicion` (
            `itemRequisicion_id`,
            `itemRequisicion_idHoja`,
            `itemRequisicion_unidad`,
            `itemRequisicion_producto`,
            `itemRequisicion_iva`,
            `itemRequisicion_retenciones`,
            `itemRequisicion_banderaFlete`,
            `itemRequisicion_banderaFisica`,
            `itemRequisicion_banderaResico`,
            `itemRequisicion_banderaISR`,
            `itemRequisicion_precio`,
            `itemRequisicion_cantidad`,
            `itemRequisicion_parcialidad`,
            `itemRequisicion_estatus`
        ) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, 'N')";
        $stmtItem = $conexion->prepare($consulta);

        foreach ($datos as $item) {
            $Unidad        = $item->Unidad;
            $Producto      = $item->Nombre;
            $Precio        = (float)$item->UnitedPrice;
            $IVA           = $item->IVA;
            $Ret           = $item->Retenciones;
            $cantidad      = $item->Cantidad;
            $banderaFlete  = (int)$item->bandFlete;
            $banderaFisica = (int)$item->bandFisico;
            $banderaResico = (int)$item->bandResico;
            $banderaISR    = (int)$item->bandISR;

            $stmtItem->execute([
                $id_hoja, $Unidad, $Producto, $IVA, $Ret,
                $banderaFlete, $banderaFisica, $banderaResico, $banderaISR,
                $Precio, $cantidad
            ]);
        }

        $conexion->commit();

        $data = $id_hoja;
        // break; // si esto está dentro de un switch, déjalo
        } catch (Throwable $e) {
            if ($conexion->inTransaction()) {
                $conexion->rollBack();
            }
            throw $e; // o maneja/loguea como prefieras
        }
        break;
    case 2:
        $consulta = "SELECT `emisor_id`,`emisor_nombre`,`emisor_rfc`,`emisor_direccion`,`emisor_telefono`,`emisor_fax`,`emisor_zipCode` FROM `emisores`;";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 3:
        $consulta = "SELECT `proveedor_id`,`proveedor_nombre` FROM `provedores`;";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 4:
        $consulta = "SELECT `proveedor_id`, `proveedor_rfc`,`proveedor_clabe`,`proveedor_numeroCuenta`,`proveedor_sucursal`,`proveedor_refBanco`,`proveedor_banco`,`proveedor_email`,`proveedor_telefono`, `presiones_tarjetaBanco` FROM `provedores` WHERE `proveedor_id` =" . $clv_Prov . ";";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 5:
        $consulta = "SELECT * FROM `users` WHERE `user_id` = '$id_user';";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 6:
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
