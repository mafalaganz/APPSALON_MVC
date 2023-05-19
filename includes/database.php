<?php

$db = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_BD']);   // Variables de Entorno de la BD. Por parametro pasamos 
                                                                                              // (nombre host, usuario, password, nombre BD a la que conectaremos)

$db->set_charset("utf8");                                                                     // Establecemos la codificación de caracteres de la conexión con la BD en UTF-8

if (!$db) {
    echo "Error: No se pudo conectar a MySQL.";
    echo "errno de depuración: " . mysqli_connect_errno();
    echo "error de depuración: " . mysqli_connect_error();
    exit;
}