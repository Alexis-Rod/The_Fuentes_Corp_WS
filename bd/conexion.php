<?php

namespace Google\Cloud\Samples\CloudSQL\MySQL;

use PDO;
use PDOException;
use RuntimeException;
use TypeError;

class Conexion
{
    public static function Conectar(): PDO
    {
        // Obtener las credenciales de las variables de entorno
        $host = getenv('DB_HOST') ?: '34.174.107.198'; // IP de tu Cloud SQL
        $dbname = getenv('DB_NAME') ?: 'The-Fuentes_Corp';
        $username = getenv('DB_USER') ?: 'the-fuentes-corp';
        $password = getenv('DB_PASSWORD') ?: 'TheFuentes2024';
        define('servidor', 'localhost');
        define('nombre_bd', 'fuentes_group');
        define('usuario', 'root');
        define('password', '');

        try {
            // Configuración del DSN para la conexión
            $dsn = sprintf('mysql:dbname=%s;host=%s', $dbname, $host);

            // Crear la conexión a la base de datos
            $conexion = new PDO($dsn, $username, $password);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexion;
        } catch (TypeError $e) {
            throw new RuntimeException(
                sprintf(
                    'Configuración inválida o faltante. Asegúrate de haber establecido ' .
                        '$username, $password, $dbname y $host. El error fue: %s',
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        } catch (PDOException $e) {
            throw new RuntimeException(
                sprintf(
                    'No se pudo conectar a la base de datos. Verifica que ' .
                        'tu nombre de usuario y contraseña sean correctos, que la base de datos ' .
                        '$username, $password, $dbname y $host. El error de PDO fue: %s',
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }
    }
}