<?php

try {
    $host = "localhost";
    $user = "root";
    $password = "root";
    $dbname = "informacion_financiera";

    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión a PostgreSQL: " . $e->getMessage();
    exit(); // Detén la ejecución si hay un error
}




?>
