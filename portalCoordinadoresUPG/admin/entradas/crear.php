<?php

require '../../includes/templates/funciones.php';

$autenticado = estaLogueado();

if(!$autenticado){
    header('Location: /portalcoordinadoresUPG/');
}

$usuario = usuarioValido($_SESSION['carrera']);
    
if($usuario != $_SESSION['carrera']){
    header('Location: /portalcoordinadoresUPG/');
}
    //Base de datos
    require '../../includes/config/database.php';

    $db = conectarDB();

    //Base de datos filtros
    $consulta = "SELECT * FROM filtro";
    $resultado = mysqli_query($db, $consulta);

    $querynombreImagenes = " SELECT * FROM filtro;";

    $resultadoFiltro = mysqli_query($db, $querynombreImagenes);

    /* while($filtro = mysqli_fetch_assoc($resultadoFiltro)){
        echo "<pre>";
        var_dump($filtro['clase']);
        echo "</pre>";
    } */

    $errores = [];

    $titulo = '';
    $imagen = '';
    $texto = '';
    $filtro = '';
    $fecha = '';
    $carrera = '';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        /* echo "<pre>";
        var_dump($_POST);
        echo "</pre>";

        echo "<pre>";
        var_dump($_FILES);
        echo "</pre>"; */

        $titulo = mysqli_real_escape_string( $db, $_POST['titulo'] );
        $texto = str_replace("\n", "<br>", mysqli_real_escape_string( $db, $_POST['texto'] ));
        $filtro = mysqli_real_escape_string( $db, $_POST['filtro'] );
        $usuarioCarrera = mysqli_real_escape_string( $db, $_SESSION['carrera'] );
        $fecha = date('y-m-d');
        $imagen = $_FILES['imagen'];


        if(!$titulo){
            $errores[] = "Título Obigatorio";
        }

        if(!$texto){
            $errores[] = "Texto Obigatorio";
        }

        if(!$filtro){
            $errores[] = "Selecciona el filtro";
        }
        

        $medida = 1000 * 5000;
        if($imagen['size'] > $medida){
            $errores[] = "La imagen es muy pesada, máximo 5Mb";
        }


        /* echo "<pre>";
        var_dump($errores);
        echo "</pre>"; */

        
        if(empty($errores)){

            //Crear carpeta
            $carpetaImagenes ='../../imagenes/';
            if(!is_dir($carpetaImagenes)){
                mkdir($carpetaImagenes);
            }

            if($imagen['size'] > 1){
                //Generar nombre único
                $nombreImagen = md5( uniqid( rand(), true))  . ".jpg";

                //Subir Imagen
                move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);
            }else{
                while($nombreFiltro = mysqli_fetch_assoc($resultado)){
                    /* echo "<pre>";
                    var_dump($nombreFiltro['clase']);
                    echo "</pre>"; */
                    if($filtro == $nombreFiltro['filtroId']){
                        $nombreImagen = $nombreFiltro['clase'] . ".jpg";
                    }
                    
                }
                
            }
        
            //Insertar en la base de datos
            $query = " INSERT INTO entradas (carrera, titulo, imagen, texto, filtro, fecha) VALUES ( '$usuarioCarrera', '$titulo', '$nombreImagen', '$texto', '$filtro', '$fecha' )";

            $resultado = mysqli_query($db,$query);

            if($resultado){
                header('Location: /portalcoordinadoresUPG/admin/?resultado=1&carrera=' . $_SESSION['carrera']);
            }
        }
  
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Entrada</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../build/css/app.css">
</head>
<body>
    <main class="contenedor">
        <h1>Crear Entrada</h1>

        <a href="../index.php?carrera=<?php echo $_SESSION['carrera'];?>" class="btn">Volver</a>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error ?>
            </div>
        <?php endforeach; ?>

        <form class="formulario contenedor" method="POST" action="crear.php" enctype="multipart/form-data">
            <fieldset>
                <legend>Información</legend>

                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" placeholder="Titulo de la entrada" value="<?php echo $titulo; ?>">

                <label for="imagen">Imagen</label>
                <input type="file" id="imagen" name="imagen" accept="image/jpeg, image/png">

                <label for="texto">Texto</label>
                <textarea name="texto" id="texto" cols="30" rows="10"><?php echo $texto; ?></textarea>

                <label for="carrera">Carrera</label>
                <input type="text" disabled="disabled" id="carrera" name="carrera" value="<?php echo $_SESSION['carrera'];?>">

            </fieldset>

            <fieldset>
                <legend>Configuración de la entrada</legend>

                <label for="filtro">Filtro</label>
                <select name="filtro" id="filtro">
                    <option value="">Seleccione</option>
                    <?php while($filtro = mysqli_fetch_assoc($resultado) ) : ?>
                        <option value="<?php echo $filtro['filtroId']; ?>"> <?php echo $filtro['clase'];?> </option>
                    <?php endwhile; ?>
                </select>

            </fieldset>
            <div class="btn-final">
                <input type="submit" value="Crear Entrada" class="btn btn-verde">
            </div>
            
        </form>
    </main>

    <script src="../../build/js/bundle.min.js"></script>
</body>
</html>