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
    <title>REQUISICIONES DE LA PRESION</title>
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
                                <li v-for="obra in this.obras">
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
                    <li class="breadcrumb-item"><a href="./presiones.php"><span>Presiones</span></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><span>Requisiciones de la Presion {{presiones[0].presiones_nombre}}</span></li>
                </ol>
            </nav>
            <div class="container px-5 overflow-auto">
                <div class="row">
                    <div class="col-8">
                        <h2 class="text-dark m-2 mt-5 mb-3 fw-bold">REQUISICIONES DE LA PRESION {{presiones[0].presiones_nombre}}</h2>
                    </div>
                    <div class="col-4 d-flex align-items-end mb-3">
                        <button type="button" class="btn btn-success ms-auto">
                            <a href="./nueva_requisicion.php"><span class="fw-bold text-white">Agregar Nueva Requisicion</span></a>
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <label for="" class="fs-6">Creado por: <span class="text-primary">{{presiones[0].presiones_userCreado}}</span></label>
                    </div>
                    <div class="col-4">
                        <label for="" class="fs-6">Feche de creacion: <span class="text-primary">{{presiones[0].presiones_fechaCreacion}}</span></label>
                    </div>
                    <div class="col-4">
                        <label for="" class="fs-6">Gastos de la Obra: <span class="text-primary">${{gastosTotalPresion}}</span></label>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="" class="fs-6">Prevalidado por: <span class="text-primary">{{presiones[0].presiones_userValidado}}</span></label>
                    </div>
                    <div class="col-4">
                        <label for="" class="fs-6">Estatus: <span class="text-primary">{{presiones[0].presiones_estatus}}</span></label>
                    </div>
                    <div class="col-2">
                        <label for="" class="fs-6">Semana: <span class="text-primary">{{presiones[0].presiones_semana}}</span></label>
                    </div>
                    <div class="col-2">
                        <label for="" class="fs-6">DIa: <span class="text-primary">{{presiones[0].presiones_dia}}</span></label>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col">
                        <table id="example" class="table table-hover w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">Clave</th>
                                    <th scope="col">Numero de Factura</th>
                                    <th scope="col">Adeudo</th>
                                    <th scope="col">Forma de Pago</th>
                                    <th scope="col">Estatus</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody class="table-light" id="Tabla_Items">
                                <tr class="my-3" v-for="(req,indice) of requisiciones">
                                    <!--<td scope="row">123</td>
                                    <td>Presion Cactus_I</td>
                                    <td>22</td>
                                    <td>Lunes</td>
                                    <td><span class="badge bg-danger">Danger</span></td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                            <button type="button" class="btn btn-danger">C</button>
                                            <button type="button" class="btn btn-warning">E</button>
                                            <button type="button" class="btn btn-success">X</button>
                                        </div>
                                    </td>-->
                                    <td scope="row"></td>
                                    <td>{{req.requisicion_Numero}}</td>
                                    <td>${{req.requisicion_total}}</td>
                                    <td>{{req.requisicion_formaPago}}</td>
                                    <td>
                                        <span class="badge bg-danger" v-if="req.requisicion_estatus == 'PENDIENTE'">Pendiente</span>
                                        <span class="badge bg-success" v-if="req.requisicion_estatus == 'LIQUIDADO'">Liquidado</span>
                                        <span class="badge bg-warning" v-if="req.requisicion_estatus == 'REVISION'">Revision</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                            <button type="button" class="btn btn-success" @click="ConsultarItemRq(req.requisicion_id)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill text-white" viewBox="0 0 16 16">
                                                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                                </svg>
                                            </button>
                                            <!-- <button type="button" class="btn btn-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                                                </svg>
                                            </button> -->
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="table-dark">
                            </tfoot>
                        </table>
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

    <!-- scripts constume-->
    <script src="./js/requisiciones.js"></script>
</body>

</html>