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
        $host = '34.174.107.198'; // IP de tu Cloud SQL
        $dbname = 'The-Fuentes_Corp';
        $username = 'the-fuentes-corp';
        $password = 'TheFuentes2024';
        $cloud_sql_conenction_name = 'the-fuentes-corporations:us-south1:the-fuentes-corp';
        $socket_dir = '/cloudsql';

        try {
            // Configuración del DSN para la conexión
            $dsn = sprintf('mysql:dbname=%s;unix_socket=%s%s', $dbname, $socket_dir,$cloud_sql_conenction_name);

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