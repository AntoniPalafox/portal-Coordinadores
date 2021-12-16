<?php
    $carrera = $_GET['carrera'];

    require '../includes/templates/funciones.php';

    // Importar la conexión
    require '../includes/config/database.php';
    $db = conectarDB();

    $autenticado = estaLogueado();

    if(!$autenticado){
        header('Location: /portalcoordinadoresUPG/');
    }

    $usuario = usuarioValido($carrera);
    
    if($usuario != $carrera){
        header('Location: /portalcoordinadoresUPG/');
    }

    // consultar
    $query = "SELECT * FROM perfiles WHERE carrera = '$carrera';";

    // obtener resultado
    $resultado = mysqli_query($db, $query);

    if(!$resultado->num_rows) {
        header('Location: /portalcoordinadoresUPG/');
    } 
    
    $datos = mysqli_fetch_assoc($resultado);
/* 
    echo "<pre>";
    var_dump($datos);
    echo "</pre>"; */


    $errores = [];

    $nombre = $datos['nombre'];
    $formacion = $datos['formacion'];
    $correo = $datos['correo'];
    $descripcion = $datos['descripcion'];
    $imagen = $datos['imagen'];


    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        /* echo "<pre>";
        var_dump($_POST);
        echo "</pre>";

        echo "<pre>";
        var_dump($_FILES);
        echo "</pre>"; */

        $nombre = mysqli_real_escape_string( $db, $_POST['nombre'] );
        $formacion = str_replace("\n", "<br>", mysqli_real_escape_string( $db, $_POST['formacion'] ));
        $correo = mysqli_real_escape_string( $db, $_POST['correo'] );
        $descripcion = str_replace("\n", "<br>", mysqli_real_escape_string( $db, $_POST['descripcion'] ));
        $imagen = $_FILES['imagen'];

        if(!$descripcion){
            $errores[] = "Texto Obigatorio";
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
            $carpetaImagenes ='../imagenes/';
            if(!is_dir($carpetaImagenes)){
                mkdir($carpetaImagenes);
            }

            $nombreImagen = '';

            if($imagen['name']){

                //Eliminar la imagen previa
                unlink($carpetaImagenes . $datos['imagen']);

                if($imagen['size'] > 1){
                    //Generar nombre único
                    $nombreImagen = md5( uniqid( rand(), true))  . ".jpg";
    
                    //Subir Imagen
                    move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);
                }
            }else{
                $nombreImagen = $datos['imagen'];
            }       

            //Insertar en la base de datos
            $query = " UPDATE perfiles SET nombre = '${nombre}', formacion = '${formacion}', correo = '${correo}', imagen = '${nombreImagen}', descripcion = '${descripcion}' WHERE carrera = '${carrera}';";

            $resultado = mysqli_query($db,$query);

            if($resultado){
                header('Location: /portalcoordinadoresUPG/admin/?resultado=5&carrera=' . $carrera);
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
    <title>Actualizar Estancias</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../build/css/app.css">
</head>
<body>
    <main class="contenedor">
        <h1>Actualizar Biografía</h1>

        <a href="../index.php?carrera=<?php echo $_SESSION['carrera'];?>" class="btn">Volver</a>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error ?>
            </div>
        <?php endforeach; ?>

        <form class="formulario contenedor" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>Información</legend>

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" value="<?php echo $datos['nombre'];?>">

                <label for="formacion">Formación</label>
                <input type="text" name="formacion" id="formacion" value="<?php echo $datos['formacion'];?>">

                <label for="correo">Correo de contacto</label>
                <input type="text" name="correo" id="correo" value="<?php echo $datos['correo'];?>">

                <label for="imagen">Imagen</label>
                <input type="file" id="imagen" name="imagen" accept="image/jpeg, image/png">

                <img src="<?php echo "../../imagenes/" . $datos['imagen'];?>" alt="Imagen Biografía" class="imagen-pequeña">

                <label for="descripcion">Texto</label>
                <textarea name="descripcion" id="descripcion" cols="30" rows="10"><?php echo $datos['descripcion']; ?></textarea>

            </fieldset>
            <div class="btn-final">
                <input type="submit" value="Actualizar Biografía" class="btn btn-verde">
            </div>
            
        </form>
    </main>

    <script src="../build/js/bundle.min.js"></script>
</body>
</html>