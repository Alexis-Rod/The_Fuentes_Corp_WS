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
    <title>PRESION DE LA SEMANA</title>
</head>

<body style="display: flex;">
    <div id="AppPresionDetail">
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
                                <li v-for="obra in this.obras">
                                    <a style="cursor: pointer" class="nav-link text-white ms-4" aria-current="page" @click="irObra(obra.obras_id)">{{obra.obras_nombre}}</a>
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
                    <li class="breadcrumb-item"><a href="./obras.php"><span>Menu de Obra</span></a></li>
                    <li class="breadcrumb-item"><a href="./presiones.php"><span>Presiones de Obra</span></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><span>Presion de la Semana {{this.semana}} y del dia {{this.dia}} de la obra {{this.obraActiva[0].obras_nombre}}</span></li>
                </ol>
            </nav>
            <div class="container px-5 overflow-auto">
                <div class="row">
                    <div class="col">
                        <h2 class="text-dark m-2 mt-5 mb-3 fw-bold">PRESION DE LA SEMANA "{{this.semana}}" Y DIA "{{this.dia}}" DE LA OBRA "{{this.obraActiva[0].obras_nombre}}"</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6"></div>
                    <div class="col-6 d-flex align-items-end mb-3">
                        <button type="button" class="btn btn-success ms-auto" @click="exportarExcel">
                            <span class="fw-bold">Exportar Excel</span>
                        </button>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col table-responsive">
                        <table id="example" class="table align-middle table-hover w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center align-middle">CLAVE</th>
                                    <th scope="col" class="text-center align-middle">N° DE REQUISICION</th>
                                    <th scope="col" class="text-center align-middle">PROVEEDOR</th>
                                    <th scope="col" class="text-center align-middle">CONCEPTO</th>
                                    <th scope="col" class="text-center align-middle">ADEUDO</th>
                                    <th scope="col" class="text-center align-middle">NETO A PAGAR</th>
                                    <th scope="col" class="text-center align-middle">OBSERVACIONES</th>
                                    <th scope="col" class="text-center align-middle">FORMA DE PAGO</th>
                                    <th scope="col" class="text-center align-middle">FECHA DE PAGO</th>
                                    <th scope="col" class="text-center align-middle">BANCO DE PAGO</th>
                                    <th scope="col" class="text-center align-middle">ESTATUS</th>
                                    <th scope="col" class="text-center align-middle">APLICAR ACCIONES</th>
                                    <th class="text-center align-middle"></th>
                                </tr>
                            </thead>
                            <tbody class="table-light" id="Tabla_Items">
                                <tr class="my-3" v-for="(presion,indice) of presiones" v-if="presion.HojaEstatus == 'LIGADA' || presion.HojaEstatus == 'AUTORIZADA' || presion.HojaEstatus == 'PAGADA'">
                                    <td scope="row" class="text-center align-middle inline-block fs-6">{{presion.clave}}</td>
                                    <td :class="presion.atrClass" :style="presion.strStyle">{{presion.NumReq}}</td>
                                    <td :class="presion.atrClass" :style="presion.strStyle">{{presion.proveedor}}</td>
                                    <td :class="presion.atrClass" :style="presion.strStyle">{{presion.concepto}}</td>
                                    <td class="text-center align-middle fs-6">{{formatearMoneda(presion.total, true)}}</td>
                                    <td class="text-center align-middle fs-6">{{formatearMoneda(presion.adeudo, true)}}</td>
                                    <td :class="presion.atrClass" :style="presion.strStyle">{{presion.Observaciones}}</td>
                                    <td class="text-center align-middle fs-6">{{presion.formaPago}}</td>
                                    <td class="text-center align-middle fs-6">
                                        <input type="date" class="form-control" id="FechaPago" v-model="presion.Fecha">
                                    </td>
                                    <td class="text-center align-middle fs-6">
                                        <input type="text" class="form-control" id="BancoPago" v-model="presion.Banco" placeholder="Ingresa Banco">
                                    </td>
                                    <td class="text-center align-middle fs-6">
                                        <span class="badge bg-warning" v-if="presion.HojaEstatus == 'LIGADA'">PENDIENTE</span>
                                        <span class="badge bg-success" v-if="presion.HojaEstatus == 'AUTORIZADA'">AUTORIZADO</span>
                                        <span class="badge bg-success" v-if="presion.HojaEstatus == 'PAGADA'">PAGADA</span>
                                    </td>
                                    <td class="text-center align-middle inline-block fs-6">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic mixed styles example" v-if="presion.HojaEstatus == 'AUTORIZADA'">
                                            <button type="button" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" data-toggle="tooltip" :title="'Pagar ' + presion.NumReq" @click="pagarItem(presion.id_hoja, presion.Fecha, presion.Banco)">
                                                <img class="" src="images/icons/pay.svg" alt="user-icon" height="24" width="24">
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-toggle="tooltip" :title="'Descargar ' + presion.NumReq" @click="imprimirReq(presion.NumRequi,presion.clave,presion.id_hoja)">
                                                <img class="" src="images/icons/download.svg" alt="user-icon" height="24" width="24">
                                            </button>
                                        </div>
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic mixed styles example" v-if="presion.HojaEstatus == 'LIGADA'">
                                            <button type="button" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" data-toggle="tooltip" :title="'Pagar ' + presion.NumReq" @click="" disabled>
                                                <img class="" src="images/icons/pay.svg" alt="user-icon" height="24" width="24">
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-toggle="tooltip" :title="'Descargar ' + presion.NumReq" @click="imprimirReq(presion.NumRequi,presion.clave,presion.id_hoja)">
                                                <img class="" src="images/icons/download.svg" alt="user-icon" height="24" width="24">
                                            </button>
                                        </div>
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic mixed styles example" v-if="presion.HojaEstatus == 'PAGADA'">
                                            <button type="button" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" data-toggle="tooltip" :title="'Pagar ' + presion.NumReq" @click="" disabled>
                                                <img class="" src="images/icons/pay.svg" alt="user-icon" height="24" width="24">
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-toggle="tooltip" :title="'Descargar ' + presion.NumReq" @click="imprimirReq(presion.NumRequi,presion.clave,presion.id_hoja)">
                                                <img class="" src="images/icons/download.svg" alt="user-icon" height="24" width="24">
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <img class="me-2" v-if="presion.showDetail == false" src="images/icons/arrow_down.svg" alt="user-icon" height="24" width="24" style="cursor: pointer;" @click="cambiarBooleano(presion.showDetail,indice)">
                                        <img class="me-2" v-if="presion.showDetail == true" src="images/icons/arrow_up.svg" alt="user-icon" height="24" width="24" style="cursor: pointer;" @click="cambiarBooleano(presion.showDetail,indice)">
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="table-dark">
                                <tr class="my-3">
                                    <td colspan="4" class="text-end">Total: </td>
                                    <td class="text-center align-middle fs-6">{{formatearMoneda(sumatoria(presiones,"total"),true)}}</td>
                                    <td class="text-center align-middle fs-6">{{formatearMoneda(sumatoria(presiones,"adeudo"),true)}}</td>
                                    <td colspan="7"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row w-100 mt-0 mb-3 mx-auto" v-if="this.estatus == 'PENDIENTE'">
                    <div class="col px-0 d-flex justify-content-center">
                        <button class="btn btn-primary" @click="cerrarPresion" title="Cerrar Presion">
                            <span class="text-center">CERRAR PRESION</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--scripts de bootstrap, poppers y jquery-->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
    <script src="./bootstrap/js/bootstrap.min.js"></script>

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
    <script src="./js/presiones_detalles.js"></script>
</body>

</html>