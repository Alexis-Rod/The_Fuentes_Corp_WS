<?php

//Conexion con axios, por parametro POST
$_POST = json_decode(file_get_contents("php://input"), true);
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : '';

print $accion;