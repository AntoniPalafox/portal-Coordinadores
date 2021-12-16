<?php
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    $carrera = $_GET['carrera'];

    if(!$id){
        header('Location: /portalcoordinadoresUPG/');
    }
    // Importar la conexión
    require 'includes/config/database.php';
    $db = conectarDB();


    // consultar
    $query = "SELECT * FROM entradas WHERE id = ${id}";

    // obtener resultado
    $resultado = mysqli_query($db, $query);

    if(!$resultado->num_rows) {
        header('Location: /portalcoordinadoresUPG/');
    } 
    
    $entrada = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coordinación Sistemas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="build/css/app.css">
</head>
<body>
    <?php require 'includes/templates/header.php';?>

    <section class="seccion-entradas">
        <div class="contenedor entrada-completa">
 
            <article class="entrada">
            
                <div class="textos-entrada">
                    <h3 class="titulo-entrada"><?php echo $entrada['titulo']; ?></h3>
                    <p class="texto-seccion"><?php  echo str_replace("\n", "<br>", $entrada['texto']);?></p>
                </div>
            </article>
        </div>
    </section>

    <?php include 'includes/templates/footer.php'; ?>
    <script src="build/js/bundle.min.js"></script>
</body>
</html>