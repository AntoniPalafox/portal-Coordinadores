<?php
    $carrera = $_GET['carrera'];

    if(!$carrera){
        header('Location: /portalcoordinadoresUPG/');
    } 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coordinación Sistemas</title>
    <link rel="stylesheet" href="build/css/app.css">
</head>
<body>
    <?php require 'includes/templates/header.php';?>

    <section class="seccion-entradas">
        <div class="contenedor contenido-entradas">

            <?php 
                $limite = 6;
                $caracteres = 135;
                include 'includes/templates/entradas.php';
            ?>
        </div>
        <div class="contenedor btn-final">
            <button class="btn btn-noticias"><a href="noticias.php?carrera=<?php echo $carrera;?>">Ver Todas</a></button>
        </div>
        
    </section>

    <section class="seccion-perfil">
        <?php include 'includes/templates/perfil.php'; ?>
    </section>


    <footer class="footer">
        <p class="derechos">TODOS LOS DERECHOS RESERVADOS</p>
        <a class="acceso" href="login.php?carrera=<?php echo $carrera;?>">Administrador</a>
    </footer>
    <script src="build/js/bundle.min.js"></script>
</body>
</html>