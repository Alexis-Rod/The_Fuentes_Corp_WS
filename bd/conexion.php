<?php
class Conexion {
    public static function Conectar() {
        $host = getenv('DB_HOST') ?: '34.174.107.198'; // IP de tu Cloud SQL
        $dbname = getenv('DB_NAME') ?: 'fuentes_group';
        $username = getenv('DB_USER') ?: 'the-fuentes-corp';
        $password = getenv('DB_PASSWORD') ?: 'TheFuentes2024';

        try {
            $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexion;
        } catch(PDOException $e) {
            die("El error de conexion es: " . $e->getMessage());
        }
    }
}
?>
