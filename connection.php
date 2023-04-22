<?php
include ("db-config.php");
class Connector
{
    function getConnectionToDatabase()
    {

        $connection = mysqli_connect(HOST, USERNAME, PASSWORD, DBNAME, PORT);
        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        } else {
            return $connection;
        }
    }
}
?>