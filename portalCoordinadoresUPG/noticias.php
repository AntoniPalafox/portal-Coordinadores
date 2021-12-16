<?php
    
    $carrera = $_GET['carrera'];
    
    // Importar la conexión
    require 'includes/config/database.php';
    $db = conectarDB();

    
    $querynombreImagenes = " SELECT * FROM filtro;";

    $resultadoFiltro = mysqli_query($db, $querynombreImagenes);

    /* while($filtro = mysqli_fetch_assoc($resultadoFiltro)){
        echo "<pre>";
        var_dump($filtro['clase']);
        echo "</pre>";
    } */
    

    //paginador
    $queryPaginador = "SELECT COUNT(*) as totalEntradas FROM entradas WHERE carrera = '${carrera}' ORDER BY id DESC ;";
    $resultadoPaginador = mysqli_query($db,$queryPaginador);

    $paginador = mysqli_fetch_assoc($resultadoPaginador);

    $totalEntradas =intval( $paginador['totalEntradas']);
    $mostrarPorPagina = 9;

    if(empty($_GET['pagina'])){
        $pagina = 1;
    }else{
        $pagina = $_GET['pagina'];
    }

    $inicio = ($pagina -1 ) * $mostrarPorPagina;
    $totalPaginas = ceil($totalEntradas / $mostrarPorPagina);

    $query = "SELECT * FROM entradas WHERE carrera = '${carrera}' ORDER BY id DESC LIMIT ${inicio},${mostrarPorPagina};";
    $resultado = mysqli_query($db,$query);



    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Últimas Noticias</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="build/css/app.css">
</head>
<body>
<?php require 'includes/templates/header.php';?>


    <section class="seccion-entradas">
        <div class="contenedor contenido-entradas">
        <?php while($entrada = mysqli_fetch_assoc($resultado)): ?>
        <article class="entrada">
            <h3 class="titulo-entrada"><?php echo $entrada['titulo']; ?></h3>
            <div class="imagen-responsiva">
            <?php if($entrada['imagen']) : ?>
                <a href="entrada.php?id=<?php echo $entrada['id'] . '&carrera=' . $carrera;?>">
                    <img class="imagen-entrada" src="<?php echo "imagenes/" . $entrada['imagen']; ?>"  alt="Imagen Entrada">
                </a>
                
            <?php endif ?>
            </div>
            
            <?php $puntos = strlen($entrada['texto']) > 120 ? '...' : '';?>
            <p class="texto-entrada"><?php echo substr($entrada['texto'],0,135) . $puntos;?></p>

            <button class="btn btn-vermas"><a href="entrada.php?id=<?php echo $entrada['id'] . '&carrera=' . $carrera;?>">Ver Más</a></button>
        </article>
    <?php endwhile; ?>

        </div>

        <div class="paginador">
        <ul class="lista-paginas">
            <li><a href="#" class="pagina"> << </a></li>

            <?php 
                for($i = 1; $i <= $totalPaginas; $i++){
                    if($i == $pagina){
                        echo "<li class='paginaActiva'>" . $i . "</li>";
                    }else{
                        echo "<li><a class='pagina' href='noticias.php?carrera=" . $carrera . "&pagina=" . $i . "'>" . $i . "</a></li>";
                    }
                    
                }
            
            ?>
            <li><a href="#" class="pagina"> >> </a></li>
        </ul>
    </div>
    </section>

    <?php include 'includes/templates/footer.php'; ?>
    <script src="build/js/bundle.min.js"></script>
</body>
</html>