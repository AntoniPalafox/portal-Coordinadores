<?php

    $carrera = $_GET['carrera'];

    $seccion = $_GET['seccion'];

    if(!$carrera){
        header('Location: /portalcoordinadoresUPG/');
    } 
    // Importar la conexión
    require 'includes/config/database.php';
    $db = conectarDB();


    // consultar
    $query = "SELECT * FROM secciones WHERE carrera = '${carrera}' AND seccion = '${seccion}';";

    // obtener resultado
    $resultado = mysqli_query($db, $query);

    if(!$resultado->num_rows) {
        header('Location: /portalcoordinadoresUPG/');
    } 
    
    $pagina = mysqli_fetch_assoc($resultado);

    $archivosIndividuales = explode(",", $pagina['archivo']);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo ucfirst($seccion);?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="build/css/app.css">
</head>
<body>
<?php require 'includes/templates/header.php';?>
<img src="imagenes/principal-<?php echo $seccion . '.jpg';?>" alt="Banner Principal">

    <section class="seccion-secciones">
        <div class="contenedor contenido-secciones">
            <div class="textos-seccion">
                <p class="texto-seccion"><?php echo str_replace("\n", "<br>", $pagina['texto']); ?></p>
            </div>
            <div class="archivos">
                <h3 class="titulo-archivos">Archivos Descargables</h3>
                <?php if($pagina['archivo']) :?>
                    <?php foreach ($archivosIndividuales as $arch => $archi): ?>
                        <a class="link-archivos" target="_blank" href="archivos/<?php echo $archi;?>">&#8226; <?php echo $archi;?></a>
                    <?php endforeach;?>
                <?php endif;?>
            
            </div>
        </div>
    </section>

    <?php include 'includes/templates/footer.php'; ?>

<script src="build/js/bundle.min.js"></script>
</body>
</html>