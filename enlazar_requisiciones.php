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
    <title>ENLAZAR REQUISICIONES</title>
</head>

<body style="display: flex;">
    <div id="AppPresion">
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
                <ol class="breadcrumb py-2 px-3 my-0">
                    <li class="breadcrumb-item">
                        <a href="./index.php">
                            <img class="" src="images/icons/home.svg" alt="user-icon" height="24" width="24">
                            <span>Inicio</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="./obras.php"><span>Menu de Obra: {{this.obras[0].obras_nombre}}</span></a></li>
                    <li class="breadcrumb-item"><a href="./presiones.php"><span>Presiones de {{this.obras[0].obras_nombre}}</span></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><span>Relacion de Requisiciones</span></li>
                </ol>
            </nav>
            <div class="container px-5 overflow-auto">
                <div class="row">
                    <div class="col">
                        <h2 class="text-dark m-2 mt-5 mb-3 fw-bold">REQUISICIONES DISPONIBLES DE LA OBRA DE {{this.obras[0].obras_nombre}}</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="text-dark m-2 mb-3">Las Requisiciones de la Obra de {{this.obras[0].obras_nombre}} se enlistan en la siguiente tabla estan disponibles para su adicción de la Presion "{{this.presiones[0].presiones_nombre}}"</p>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col">
                        <table id="example" class="table table-hover w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center align-middle">Numero de Requisicion</th>
                                    <th scope="col" class="text-center align-middle">Numero de Hoja</th>
                                    <th scope="col" class="text-center align-middle">Nombre de la Requisicion</th>
                                    <th scope="col" class="text-center align-middle">Clave</th>
                                    <th scope="col" class="text-center align-middle">Total</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody class="table-light" id="Tabla_Items">
                                <tr class="my-3" v-for="(req,indice) of requisiciones">
                                    <td scope="row" class="text-center align-middle">{{req.requisicion_Numero}}</td>
                                    <td class="text-center align-middle">Hoja N° {{req.hojaRequisicion_numero}}</td>
                                    <td class="text-left align-middle">{{req.requisicion_Nombre}}</td>
                                    <td class="text-center align-middle">{{req.requisicion_Clave}}</td>
                                    <td class="text-center align-middle">{{formatearMoneda(req.hojaRequisicion_total,true)}}</td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-primary" @click="enlazarConPresion(req.hojaRequisicion_id, req.requisicion_id)" data-toggle="tooltip" :title="'Revisar Hoja N°'+ req.hojaRequisicion_numero + ' de la Requisicion ' + req.requisicion_Numero">
                                            <img class="" src="images/icons/pay.svg" alt="user-icon" height="24" width="24">
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="table-dark">
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- <div class="row w-100 mt-0 mb-3 mx-auto">
                    <div class="col px-0 d-flex justify-content-center">
                        <button class="btn btn-success" @click="" title="Agregar Requisicion">
                            <span class="text-center">Enlazar Requisiciones con la Presion "{{this.presiones[0].presiones_nombre}}"</span>
                        </button>
                    </div>
                </div> -->
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
    <script src="./js/enlazar_requisiciones.js"></script>
</body>

</html>