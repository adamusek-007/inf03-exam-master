<?php
define('DB_HOST', 'kuczabinski.pl');
define('DB_NAME', 'adamusek_007');
define('DB_USER', 'adamk');
define('DB_PASSWORD', 'Vfr4*IK<');
class Connector
{
    function getConnectionToDatabase(): PDO
    {
        static $pdo;
        if (!$pdo) {
            return new PDO(
                sprintf("mysql:host=%s;dbname=%s;charset=UTF8", DB_HOST, DB_NAME),
                DB_USER,
                DB_PASSWORD,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        }
        return $pdo;
    }
}
?>