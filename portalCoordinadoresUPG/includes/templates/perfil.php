<?php 

    $carrera = $_GET['carrera'];

    // consultar
    $query = "SELECT * FROM perfiles WHERE carrera = '${carrera}';";

    // obtener resultado
    $resultado = mysqli_query($db, $query);

    $perfil = mysqli_fetch_assoc($resultado);

    /* echo "<pre>";
    var_dump($pagina);
    echo "</pre>"; */
?>


<div class="contenedor">
    <h2 class="perfil titulo-perfil">Conoce a tu coordinador</h2>
    <div class="contenido-perfil">
        <div>
            <img class="perfil imagen-perfil" src="<?php echo "imagenes/" . $perfil['imagen']; ?>"  alt="Imagen Entrada">
            <p class="perfil nombre-perfil"><?php echo $perfil['nombre']; ?></p>
            <p class="perfil formacion-perfil"><?php echo $perfil['formacion']; ?></p>
            <p class="perfil contacto-perfil"><span>Correo:</span> <?php echo $perfil['correo']; ?></p>
        </div>
            
        <p class="perfil descripcion-perfil"><?php echo str_replace("\n", "<br>", $perfil['descripcion']); ?></p>
    </div>
    
</div>

