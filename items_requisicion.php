<?php
include_once 'validarSesion.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no" />
    <link rel="icon" type="image/jpg" href="./images/TheFuenteIcon.png" />
    <!--llamar a la extension de sweet alert-->
    <link rel="stylesheet" href="plugins/sweetalert/sweetalert2.min.css">
    <!-- fuente de Roboto flex-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:opsz,wght@8..144,100..1000&display=swap" rel="stylesheet">
    <!--Fuentes de Iconos-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!--llamar a la extension de bootstrap-->
    <!-- esta es la llamada via CDN-
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">-->
    <!-- esta es la llamada local-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <!--Esta es la llamada CSS de data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
    <!--llamar a mi documento de CSS-->
    <link rel="stylesheet" href="main.css">
    <title>ITEMS DE LA REQUISICION</title>
</head>

<body style="display: flex;">
    <div id="AppItems">
        <!--sidebar-->
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white position-fixed top-0 start-0 h-100" style="width: 15%;" id="sidebar">
            <div class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <div class="d-flex flex-row">
                    <div class="d-flex align-items-center me-3">
                        <img src="images/icons/user.svg" alt="user-icon" height="60" width="60">
                    </div>
                    <div class="d-flex flex-column my-3">
                        <span class="fs-5"> {{NameUser}}</span>
                    </div>
                </div>
            </div>
            <hr>
            <div id="sideBarItem" class="mb-auto overflow-auto">
                <ul class="nav nav-pills flex-column f-5" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <li v-if="this.users[0].user_directionAcess == 1">
                        <a href="#" class="nav-link text-white" id="v-pills-reports-tab" data-bs-toggle="pill" data-bs-target="#v-pills-reports" type="button" role="tab" aria-controls="v-pills-reports" aria-selected="false" @click="irDireecion">
                            <img class="me-2" src="images/icons/ceo.svg" alt="user-icon" height="24" width="24">
                            DIRECCION
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white" aria-current="page" id="v-pills-obras-tab" data-bs-toggle="pill" data-bs-target="#v-pills-obras" type="button" role="tab" aria-controls="v-pills-obras" aria-selected="true">
                            <img class="me-2" src="images/icons/obras.svg" alt="user-icon" height="24" width="24">
                            OBRAS
                        </a>
                        <div class="tab-content" id="v-pills-tabContent">
                            <ul class="tab-pane fade nav nav-pills flex-column mb-auto" id="v-pills-obras" role="tabpanel" aria-labelledby="v-pills-obras-tab">
                                <li v-for="obra in this.obrasLista">
                                    <a style="cursor: pointer" class="nav-link text-white ms-4" aria-current="page" @click="irObra(obra.obras_id)">{{obra.obras_nombre}}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white" aria-current="page" id="v-pills-catalago-tab" data-bs-toggle="pill" data-bs-target="#v-pills-catalago" type="button" role="tab" aria-controls="v-pills-catalago" aria-selected="false" @click="irMenuCatalago">
                            <img class="me-2" src="images/icons/catalagos.svg" alt="user-icon" height="24" width="24">
                            CATALAGOS
                        </a>
                    </li>
                </ul>
            </div>
            <hr>
            <div class="dropdown">
                <a href="./closeSesion.php" class="d-flex align-items-center text-white text-decoration-none f-5" aria-expanded="false">
                    <img class="me-2" src="images/icons/logout.svg" alt="user-icon" height="24" width="24">
                    <span>CERRAR SESION</span>
                </a>
            </div>
        </div>
        <div class="d-flex flex-column flex-shrink-0 h-100 position-fixed top-0 end-0" style="width: 85%;">
            <!--Navbar-->
            <nav class="navbar" style="background-color: #4468C1;">
                <div class="container-fluid">
                    <span class="navbar-brand text-light text-center w-100 fw-bolder">The Fuentes Corporation Workspace</span>
                </div>
            </nav>
            <nav class="nav shadow-sm d-flex align-items-center" id="navtab" aria-label="breadcrumb" aria-current="page">
                <ol class="breadcrumb py-2 px-3 my-0" v-if="this.validate == 'false'">
                    <li class="breadcrumb-item">
                        <a href="./index.php">
                            <img class="" src="images/icons/home.svg" alt="user-icon" height="24" width="24">
                            <span>Inicio</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="./obras.php"><span>Menu Obras</span></a></li>
                    <li class="breadcrumb-item"><a href="./requisiciones.php"><span>Requisiciones de la Obra</span></a></li>
                    <li class="breadcrumb-item"><a href="./hojas_requisicion.php"><span>Hojas de la Requisicion</span></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><span>{{this.Numero_Req}} HOJA N° {{this.hojas[0].hojaRequisicion_numero}} </span></li>
                </ol>
                <ol class="breadcrumb py-2 px-3 my-0" v-if="this.validate == 'true'">
                    <li class="breadcrumb-item">
                        <a href="./index.php">
                            <img class="" src="images/icons/home.svg" alt="user-icon" height="24" width="24">
                            <span>Inicio</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="./obras.php"><span>Menu de Obra: {{this.obras[0].obras_nombre}}</span></a></li>
                    <li class="breadcrumb-item"><a href="./presiones.php"><span>Presiones de {{this.obras[0].obras_nombre}}</span></a></li>
                    <li class="breadcrumb-item"><a href="./enlazar_requisiciones.php"><span>Enlazar Requisiciones a la Presion</span></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><span>{{this.Numero_Req}} HOJA N° {{this.hojas[0].hojaRequisicion_numero}} </span></li>
                </ol>
            </nav>
            <div class="container px-5 overflow-auto">
                <div class="row">
                    <div class="col">
                        <h2 class="text-dark m-2 mt-5 mb-3 fw-bold">DETALLES DE LA REQUISICION {{this.Numero_Req}} HOJA NUMERO {{this.hojas[0].hojaRequisicion_numero}}</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col d-flex flex-row-reverse mb-3">
                        <button type="button" class="btn btn-danger ms-2" @click="imprimirReq">
                            <span class="fw-bold text-white">Imprimir Requisicion</span>
                        </button>
                        <button type="button" class="btn btn-primary ms-2" @click="cambiarFormaPago(hojas[0].hojaRequisicion_formaPago)" v-if="(hojas[0].hojaRequisicion_estatus == 'NUEVO' || hojas[0].hojaRequisicion_estatus == 'PENDIENTE' || hojas[0].hojaRequisicion_estatus == 'RECHAZADA') && this.users[0].user_editReq == 1">
                            <span class="fw-bold text-white">Cambiar Forma de Pago</span>
                        </button>
                        <button type="button" class="btn btn-success ms-2" @click="cambiarProveedor()" v-if="(hojas[0].hojaRequisicion_estatus == 'NUEVO' || hojas[0].hojaRequisicion_estatus == 'PENDIENTE' || hojas[0].hojaRequisicion_estatus == 'RECHAZADA') && this.users[0].user_editReq == 1">
                            <span class="fw-bold text-white">Cambiar Proveedor</span>
                        </button>
                    </div>
                </div>
                <div class="row card border-primary mb-3">
                    <div class="card-header">
                        <h5 class="card-title">Encabezado de la Requisicion {{this.Numero_Req}} Hoja Numero {{hojas[0].hojaRequisicion_numero}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mt-3">
                            <div class="col">
                                <h6 class="card-subtitle mb-2 text-muted">Datos de la Requisicion</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Clave de la Requisicion: <span class="text-primary">{{this.clve}}</span></p>
                            </div>
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Fecha de la Solicitud: <span class="text-primary">{{hojas[0].hojaRequisicion_FechaSolicitud}}</span></p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row mt-3">
                            <div class="col">
                                <h6 class="card-subtitle mb-2 text-muted">Datos de la Empresa</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Nombre de la Empresa: <span class="text-primary">{{hojas[0].emisor_nombre}}</span></p>
                            </div>
                            <div class="col-4">
                                <p class="card-subtitle mb-2 text-muted">RFC: <span class="text-primary">{{hojas[0].emisor_rfc}}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Direccion: <span class="text-primary">{{hojas[0].emisor_direccion}}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Telefono: <span class="text-primary">{{hojas[0].emisor_telefono}}</span></p>
                            </div>
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Fax: <span class="text-primary">{{hojas[0].emisor_fax}}</span></p>
                            </div>
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Codigo Postal: <span class="text-primary">{{hojas[0].emisor_zipCode}}</span></p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row mt-3">
                            <div class="col">
                                <h6 class="card-subtitle mb-2 text-muted">Datos del Proveedor</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Nombre de la Empresa: <span class="text-primary">{{hojas[0].proveedor_nombre}}</span></p>
                            </div>
                            <div class="col-4">
                                <p class="card-subtitle mb-2 text-muted">RFC: <span class="text-primary">{{hojas[0].proveedor_rfc}}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">CLABE Bancaria: <span class="text-primary">{{hojas[0].proveedor_clabe}}</span></p>
                            </div>
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Cuenta Bancaria: <span class="text-primary">{{hojas[0].proveedor_numeroCuenta}} </span></p>
                            </div>
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Banco: <span class="text-primary">{{hojas[0].proveedor_banco}}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Referencia Bancaria: <span class="text-primary">{{hojas[0].presiones_tarjetaBanco}} </span></p>
                            </div>
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Correo electronico: <span class="text-primary">{{hojas[0].proveedor_email}}</span></p>
                            </div>
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Telefono: <span class="text-primary">{{hojas[0].proveedor_telefono}}</span></p>
                            </div>
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Sucursal Bancaria: <span class="text-primary">{{hojas[0].proveedor_sucursal}}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="(hojas[0].hojaRequisicion_estatus == 'NUEVO' || hojas[0].hojaRequisicion_estatus == 'PENDIENTE' || hojas[0].hojaRequisicion_estatus == 'RECHAZADA') && this.users[0].user_editReq == 1">
                    <div class="col d-flex align-items-end mb-3">
                        <button type="button" class="btn btn-primary ms-auto" @click="agregarItem" id="btnAddItem">
                            <span class="fw-bold text-white">Agregar item a esta requisicion</span>
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <table id="example" class="table table-hover w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center">Unidad</th>
                                    <th scope="col" class="text-center">Producto</th>
                                    <th scope="col" class="text-center">Cantidad</th>
                                    <th scope="col" class="text-center">Precio Unitario</th>
                                    <th scope="col" class="text-center">IVA</th>
                                    <th scope="col" class="text-center">Retenciones</th>
                                    <th scope="col" class="text-center">Subtotal</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody class="table-light" id="Tabla_Items">
                                <tr class="my-3" v-for="(item,indice) of itemsHoja">
                                    <td class="text-center align-middle">{{item.itemRequisicion_unidad}}</td>
                                    <td style="max-width: 150px;;">{{item.itemRequisicion_producto}}</td>
                                    <td class="text-center align-middle">{{formatearMoneda(item.itemRequisicion_cantidad,false)}}</td>
                                    <td class="text-center align-middle">{{formatearMoneda(item.itemRequisicion_precio,true)}}</td>
                                    <td class="text-center align-middle">{{formatearMoneda(item.itemRequisicion_iva,true)}}</td>
                                    <td class="text-center align-middle">{{formatearMoneda(item.itemRequisicion_retenciones,true)}}</td>
                                    <td class="text-center align-middle">{{ formatearMoneda((
                                        Number(item.itemRequisicion_cantidad ?? 0) * Number(item.itemRequisicion_precio ?? 0)
                                        + Number(item.itemRequisicion_iva ?? 0)
                                        - Number(item.itemRequisicion_retenciones ?? 0)
                                        ).toFixed(2), true) }}
                                    </td>
                                    <!--<td><span class="badge bg-danger">Pendiente</span></td>-->
                                    <td class="align-middle">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic mixed styles example" v-if="hojas[0].hojaRequisicion_estatus == 'NUEVO' || hojas[0].hojaRequisicion_estatus == 'PENDIENTE' || hojas[0].hojaRequisicion_estatus == 'RECHAZADA'|| hojas[0].hojaRequisicion_estatus == 'RECHAZADA'">
                                            <button type="button" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar item" @click="editItem(item.itemRequisicion_producto,item.itemRequisicion_cantidad,item.itemRequisicion_precio,item.itemRequisicion_iva,item.itemRequisicion_banderaFlete,item.itemRequisicion_banderaFisica,item.itemRequisicion_banderaResico,item.itemRequisicion_banderaISR,item.itemRequisicion_id)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill text-white" viewBox="0 0 16 16">
                                                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                                </svg>
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar Item" @click="eliminarItem(item.itemRequisicion_id,item.itemRequisicion_cantidad,item.itemRequisicion_precio,item.itemRequisicion_iva,item.itemRequisicion_retenciones)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="table-dark">
                                <tr>
                                    <td colspan="6" class="text-end fw-bold">Total:</td>
                                    <td class="fw-bold text-center">{{ formatearMoneda(Number(hojas[0]?.hojaRequisicion_total ?? 0).toFixed(2), true) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row card border-primary mb-5">
                    <div class="card-header">
                        <h5 class="card-title">Comentarios de la Requisicion {{this.Numero_Req}} Hoja Numero {{hojas[0].hojaRequisicion_numero}}</h5>
                    </div>
                    <div class="card-body">
                        <div v-if="hojas[0].hojaRequisicion_estatus == 'LIGADA' || hojas[0].hojaRequisicion_estatus == 'AUTORIZADA' || hojas[0].hojaRequisicion_estatus == 'REVISION' || hojas[0].hojaRequisicion_estatus == 'PAGADA' || this.users[0].user_editReq == 0 ">
                            <div class="row mt-3">
                                <div class="col">
                                    <h6 class="card-subtitle mb-2 text-muted">Comentarios de la Operacion</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p class="card-subtitle mb-2 text-muted">{{hojas[0].hojaRequisicion_observaciones}}</span></p>
                                </div>
                            </div>
                        </div>
                        <div v-if="(hojas[0].hojaRequisicion_estatus == 'NUEVO' || hojas[0].hojaRequisicion_estatus == 'PENDIENTE' || hojas[0].hojaRequisicion_estatus == 'RECHAZADA') && this.users[0].user_editReq == 1">
                            <div class="row mt-3">
                                <div class="col">
                                    <h6 class="card-subtitle mb-2 text-muted">Comentarios de la Operacion</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <textarea class="form-control" id="comentsValidacion" v-model="hojas[0].hojaRequisicion_observaciones" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div v-if="hojas[0].hojaRequisicion_estatus == 'REVISION' && this.validate == 'true'">
                            <hr class="my-2">
                            <div class="row mt-3">
                                <div class="col">
                                    <h6 class="card-subtitle mb-2 text-muted">Comentarios de Validacion</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <textarea class="form-control" id="comentsValidacion" v-model="hojas[0].hojarequisicion_comentariosValidacion" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div v-if="hojas[0].hojaRequisicion_estatus == 'PENDIENTE'">
                            <hr class="my-2">
                            <div class="row mt-3">
                                <div class="col">
                                    <h6 class="card-subtitle mb-2 text-muted">Comentarios de Validacion</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p class="card-subtitle mb-2 text-muted">{{hojas[0].hojarequisicion_comentariosValidacion }}</span></p>
                                </div>
                            </div>
                        </div>
                        <div v-if="hojas[0].hojaRequisicion_estatus == 'PAGADA' || hojas[0].hojaRequisicion_estatus == 'RECHAZADA'">
                            <hr class="my-2">
                            <div class="row mt-3">
                                <div class="col">
                                    <h6 class="card-subtitle mb-2 text-muted">Comentarios de Autorizacion</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p class="card-subtitle mb-2 text-muted">{{hojas[0].hojarequisicion_comentariosAutorizacion}}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row w-100 mt-3 mb-5 mx-auto" v-if="(hojas[0].hojaRequisicion_estatus == 'NUEVO' || hojas[0].hojaRequisicion_estatus == 'PENDIENTE' || hojas[0].hojaRequisicion_estatus == 'RECHAZADA') && this.users[0].user_editReq == 1">
                    <div class="col px-0 d-flex justify-content-center">
                        <button class="btn btn-success" @click="validarRequisicion(hojas[0].hojaRequisicion_observaciones)" title="Solicitud de Revision">
                            <span class="text-center">Solicitar Revision de la Requisicion</span>
                        </button>
                    </div>
                </div>
                <div class="row w-100 mt-3 mb-5 mx-auto" v-if="hojas[0].hojaRequisicion_estatus == 'REVISION' && this.validate == 'true'">
                    <div class="col px-0 d-flex justify-content-center">
                        <button class="btn btn-success" @click="asignarAPresion(hojas[0].hojarequisicion_comentariosValidacion, hojas[0].hojaRequisicion_total)" title="Solicitud de Revision">
                            <span class="text-center">Validar Requisicion</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--scripts de bootstrap, poppers y jquery-->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!--esta es la llamada cdn de datatable-->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>

    <!-- scripts de vue.js-->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>

    <!--Script de axios-->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!--scripts de sweetalert-->
    <script src="plugins/sweetalert/sweetalert2.min.js"></script>

    <!--CDN de la bibloteca JsPDF-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>

    <script src="./js/pdfGenerate.js"></script>

    <!-- scripts constume-->
    <script src="./js/item_requisicion.js"></script>
</body>

</html>