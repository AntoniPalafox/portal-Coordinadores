<?php

function conectarDB() : mysqli {
    $db = mysqli_connect('localhost', 'root', 'root1', 'portalCoordinadores');

    if(!$db){
        echo "No se pudo conectar";
        exit;
    }

    return $db;
}