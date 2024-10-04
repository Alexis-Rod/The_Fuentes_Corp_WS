<?php
    class Conexion{
        public static function Conectar(){
            define('servidor', '34.174.107.198');
            define('nombre_bd', 'fuentes_group');
            define('usuario', 'the-fuentes-corp');
            define('password', 'TheFuentes2024');
            $opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
            try{
                $conexion = new PDO("mysql:host=".servidor."; dbname=".nombre_bd,usuario,password,$opciones);
                return $conexion;
            }catch(Exception $e){
                die("El error de conexion es: ". $e->getMessage());
            }
        }
    }
?>