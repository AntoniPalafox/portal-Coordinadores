<?php

function estaLogueado() : bool {
    session_start();

    $autenticado = $_SESSION['login'];

    if($autenticado){
        return true;
    }

    return false;
}

function usuarioValido($carrera) : bool {
    $usuario = $_SESSION['carrera'];

    if($usuario == $carrera){
        return true;
    }

    return false;
}

function alertaError($resultado){
    switch($resultado){
        case 1:
            return "<p class='alerta exitosa'>Entrada Creada Correctamente</p>";
            break;
        case 2:
            return "<p class='alerta exitosa'>Entrada Actualizada Correctamente</p>";
            break;
        case 3:
            return "<p class='alerta error'>Entrada Eliminada Correctamente</p>";
            break;
        case 4:
            return "<p class='alerta exitosa'>Contraseña Actualizada Correctamente</p>";
            break;
        case 5:
            return "<p class='alerta exitosa'>Biografía Actualizada Correctamente</p>";
            break;
        case 6:
            return "<p class='alerta exitosa'>Sección Actualizada Correctamente</p>";
            break;
    }
}