<?php
include("db-config.php");
/**
 * Creates PDO connection Data Source Name (DSN).
 * 
 * @return string The PDO DSN.
 */
function create_dsn()
{
    $dsn_format = "mysql:host=%s;dbname=%s;port=%s;charset=utf8";
    return sprintf($dsn_format, DB_HOST, DB_NAME, DB_PORT);
}

/**
 * Gets a PDO database connection.
 *
 * @return PDO|null A PDO database connection object, or null on failure.
 */
function get_database_connection(): PDO|null
{
    static $pdo;

    if (!$pdo) {
        try {
            $pdo = new PDO(create_dsn(), DB_USER, DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo $exception;
            exit;
        }
    }
    return $pdo;
}