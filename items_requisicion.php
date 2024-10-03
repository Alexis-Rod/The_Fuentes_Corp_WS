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
    <div id="AppPresion">
        <!--sidebar-->
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white position-fixed top-0 start-0 h-100" style="width: 25%;" id="sidebar">
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
                    <li>
                        <a href="#" class="nav-link text-white" aria-current="page" id="v-pills-obras-tab" data-bs-toggle="pill" data-bs-target="#v-pills-obras" type="button" role="tab" aria-controls="v-pills-obras" aria-selected="true">
                            <img class="me-2" src="images/icons/obras.svg" alt="user-icon" height="24" width="24">
                            OBRAS
                        </a>
                        <div class="tab-content" id="v-pills-tabContent">
                            <ul class="tab-pane fade nav nav-pills flex-column mb-auto" id="v-pills-obras" role="tabpanel" aria-labelledby="v-pills-obras-tab">
                                <li v-for="obra in this.obrasLista">
                                    <a style="cursor: pointer" class="nav-link text-white ms-4" aria-current="page" @click="irPresion(obra.obras_id)">{{obra.obras_nombre}}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white" id="v-pills-reports-tab" data-bs-toggle="pill" data-bs-target="#v-pills-reports" type="button" role="tab" aria-controls="v-pills-reports" aria-selected="false">
                            <img class="me-2" src="images/icons/reportes.svg" alt="user-icon" height="24" width="24">
                            REPORTES
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white" id="v-pills-reports-tab" data-bs-toggle="pill" data-bs-target="#v-pills-reports" type="button" role="tab" aria-controls="v-pills-reports" aria-selected="false">
                            <img class="me-2" src="images/icons/reportes.svg" alt="user-icon" height="24" width="24">
                            REPORTES
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white" id="v-pills-reports-tab" data-bs-toggle="pill" data-bs-target="#v-pills-reports" type="button" role="tab" aria-controls="v-pills-reports" aria-selected="false">
                            <img class="me-2" src="images/icons/reportes.svg" alt="user-icon" height="24" width="24">
                            REPORTES
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white" aria-current="page" id="v-pills-catalago-tab" data-bs-toggle="pill" data-bs-target="#v-pills-catalago" type="button" role="tab" aria-controls="v-pills-catalago" aria-selected="true">
                            <img class="me-2" src="images/icons/catalagos.svg" alt="user-icon" height="24" width="24">
                            CATALAGOS
                        </a>
                        <div class="tab-content" id="v-pills-tabContent">
                            <ul class="tab-pane fade nav nav-pills flex-column mb-auto" id="v-pills-catalago" role="tabpanel" aria-labelledby="v-pills-catalago-tab">
                                <li>
                                    <a href="agregar_proveedor.php" class="nav-link text-white ms-4" aria-current="page">PROVEEDORES</a>
                                </li>
                            </ul>
                        </div>
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
        <div class="d-flex flex-column flex-shrink-0 h-100 position-fixed top-0 end-0" style="width: 75%;">
            <!--Navbar-->
            <nav class="navbar" style="background-color: #4468C1;">
                <div class="container-fluid">
                    <span class="navbar-brand text-light text-center w-100 fw-bolder">The Fuentes Corporation Workspace</span>
                </div>
            </nav>
            <nav class="nav shadow-sm d-flex align-items-center" id="navtab" aria-label="breadcrumb" aria-current="page">
                <ol class="breadcrumb py-2 px-3 my-0">
                    <li class="breadcrumb-item">
                        <a href="./index.php">
                            <img class="" src="images/icons/home.svg" alt="user-icon" height="24" width="24">
                            <span>Inicio</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="./presiones.php"><span>Presion</span></a></li>
                    <li class="breadcrumb-item"><a href="./requisiciones.php"><span>Requisiciones</span></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><span>{{requisicion[0].requisicion_Numero}}</span></li>
                </ol>
            </nav>
            <div class="container px-5 overflow-auto">
                <div class="row">
                    <div class="col-6">
                        <h2 class="text-dark m-2 mt-5 mb-3 fw-bold">DETALLES DE LA REQUISICION {{requisicion[0].requisicion_Numero}}</h2>
                    </div>
                    <div class="col-6 d-flex align-items-end mb-3">
                        <button type="button" class="btn btn-danger ms-auto" @click="imprimirReq">
                            <span class="fw-bold text-white">Imprimir Requisicion</span>
                        </button>
                    </div>
                </div>
                <div class="row card border-primary">
                    <div class="card-header">
                        <h5 class="card-title">Encabezado de la Requisicion {{requisicion[0].requisicion_Numero}}</h5>
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
                                <p class="card-subtitle mb-2 text-muted">Fecha de la Solicitud: <span class="text-primary">{{requisicion[0].requisicion_fechaSolicitud}}</span></p>
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
                                <p class="card-subtitle mb-2 text-muted">Nombre de la Empresa: <span class="text-primary">{{requisicion[0].emisor_nombre}}</span></p>
                            </div>
                            <div class="col-4">
                                <p class="card-subtitle mb-2 text-muted">RFC: <span class="text-primary">{{requisicion[0].emisor_rfc}}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Direccion: <span class="text-primary">{{requisicion[0].emisor_direccion}}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Telefono: <span class="text-primary">{{requisicion[0].emisor_telefono}}</span></p>
                            </div>
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Fax: <span class="text-primary">{{requisicion[0].emisor_fax}}</span></p>
                            </div>
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Codigo Postal: <span class="text-primary">{{requisicion[0].emisor_zipCode}}</span></p>
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
                                <p class="card-subtitle mb-2 text-muted">Nombre de la Empresa: <span class="text-primary">{{requisicion[0].proveedor_nombre}}</span></p>
                            </div>
                            <div class="col-4">
                                <p class="card-subtitle mb-2 text-muted">RFC: <span class="text-primary">{{requisicion[0].proveedor_rfc}}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">CLABE Bancaria: <span class="text-primary">{{requisicion[0].proveedor_clabe}}</span></p>
                            </div>
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Cuenta Bancaria: <span class="text-primary">{{requisicion[0].proveedor_numeroCuenta}} </span></p>
                            </div>
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Banco: <span class="text-primary">{{requisicion[0].proveedor_banco}}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Referencia Bancaria: <span class="text-primary">{{requisicion[0].proveedor_refBanco}} </span></p>
                            </div>
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Correo electronico: <span class="text-primary">{{requisicion[0].proveedor_email}}</span></p>
                            </div>
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Telefono: <span class="text-primary">{{requisicion[0].proveedor_telefono}}</span></p>
                            </div>
                            <div class="col">
                                <p class="card-subtitle mb-2 text-muted">Sucursal Bancaria: <span class="text-primary">{{requisicion[0].proveedor_sucursal}}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
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
                                    <th scope="col">Unidad</th>
                                    <th scope="col">Producto</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Precio Unitario</th>
                                    <th scope="col">IVA</th>
                                    <th scope="col">Retenciones</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody class="table-light" id="Tabla_Items">
                                <tr class="my-3" v-for="(item,indice) of itemsRequisicion">
                                    <td>{{item.itemRequisicion_unidad}}</td>
                                    <td>{{item.itemRequisicion_producto}}</td>
                                    <td>{{item.itemRequisicion_cantidad}}</td>
                                    <td>{{item.itemRequisicion_precio}}</td>
                                    <td>{{item.itemRequisicion_iva}}</td>
                                    <td>{{item.itemRequisicion_retenciones}}</td>
                                    <!--<td><span class="badge bg-danger">Pendiente</span></td>-->
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic mixed styles example">
                                            <button type="button" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar item" @click="editItem(item.itemRequisicion_producto,item.itemRequisicion_cantidad,item.itemRequisicion_precio,item.itemRequisicion_iva,item.itemRequisicion_banderaFlete,item.itemRequisicion_banderaFisica,item.itemRequisicion_banderaResico,item.itemRequisicion_id)">
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
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row w-100 mt-3 mb-5 mx-auto">
                    <div class="col px-0 d-flex justify-content-center">
                        <button class="btn btn-success" @click="validarRequisicion" title="Solicitud de Revision">
                            <span class="text-center">Solicitar Revision de la Requisicion</span>
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

    <!-- scripts de vue.js-->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>

    <!--Script de axios-->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!--scripts de sweetalert-->
    <script src="plugins/sweetalert/sweetalert2.min.js"></script>

    <!--esta es la llamada cdn de datatable-->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>

    <!--CDN de la bibloteca JsPDF-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>

    <script src="./js/pdfGenerate.js"></script>

    <!-- scripts constume-->
    <script src="./js/item_requisicion.js"></script>
</body>

</html>