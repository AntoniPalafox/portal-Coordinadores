<?php 

    $carrera = $_GET['carrera'];
    
    // Importar la conexión
    require 'includes/config/database.php';
    $db = conectarDB();

    // consultar
    $query = "SELECT * FROM entradas WHERE carrera = '${carrera}' ORDER BY id DESC LIMIT ${limite};";

    // obtener resultado
    $resultado = mysqli_query($db, $query);

    if(!$resultado->num_rows) {
        header('Location: /portalcoordinadoresUPG/');
    } 
    
    $querynombreImagenes = " SELECT * FROM filtro;";

    $resultadoFiltro = mysqli_query($db, $querynombreImagenes);

    /* while($filtro = mysqli_fetch_assoc($resultadoFiltro)){
        echo "<pre>";
        var_dump($filtro['clase']);
        echo "</pre>";
    } */

?>


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
            <p class="texto-entrada"><?php echo substr($entrada['texto'],0,$caracteres) . $puntos;?></p>

            <button class="btn btn-vermas"><a href="entrada.php?id=<?php echo $entrada['id'] . '&carrera=' . $carrera;?>">Ver Más</a></button>
        </article>
    <?php endwhile; ?>


