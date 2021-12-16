<?php
    $carrera = $_GET['carrera'];

    $seccion = $_GET['seccion'];

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
    $query = "SELECT * FROM secciones WHERE carrera = '${carrera}' AND seccion = '${seccion}';";

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

    $imagen = $datos['imagen'];
    $texto = $datos['texto'];


    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        /* echo "<pre>";
        var_dump($_POST);
        echo "</pre>"; */

        /* echo "<pre>";
        var_dump($_FILES);
        echo "</pre>";  */

        $texto = str_replace("\n", "<br>", mysqli_real_escape_string( $db, $_POST['texto'] ));
        $archivo[] = $_FILES['archivo'];

        if(!$texto){
            $errores[] = "Texto Obigatorio";
        }

        /* echo "<pre>";
        var_dump($errores);
        echo "</pre>"; */

        if(empty($errores)){

            $carpetaArchivos ='../archivos/';
            if(!is_dir($carpetaArchivos)){
                mkdir($carpetaArchivos);
            }
            //Para acceder a este arreglo el primer nivel es ['0']
            $nombreArchivo[] = '';
            /* echo "<pre>";
            var_dump($archivo['0']['tmp_name']['0']);
            echo "</pre>";
            exit; */

            if($archivo['0']['tmp_name']['0'] > 1){

                foreach($_FILES['archivo']['tmp_name'] as $nam => $tmp_name){

                    if($_FILES['archivo']['name'][$nam]){

                        $nombreArchivo = $carrera . '_' .$_FILES['archivo']['name'][$nam];
                        $nombretemporal = $_FILES['archivo']['tmp_name'][$nam];

                        $directorio = "../archivos/";

                        if(!file_exists($directorio)){
                            mkdir($directorio, 0777);
                        }
                        $dir = opendir($directorio);
                        $ruta = $directorio.'/'.$nombreArchivo;

                        //Subir Archivos
                        move_uploaded_file($nombretemporal, $ruta);

                        
                    }
                    $nombreArchivoAcumulado = $nombreArchivoAcumulado . $nombreArchivo . ',';
                }
                $nombreArchivoAcumulado = $nombreArchivoAcumulado . '--';
                $limite = strpos($nombreArchivoAcumulado, ',--');
                $nombreArchivoAcumulado = substr($nombreArchivoAcumulado,0,$limite);

            }else{
                $nombreArchivoAcumulado = $datos['archivo'];
            }

            //Insertar en la base de datos
            $query = " UPDATE secciones SET archivo = '{$nombreArchivoAcumulado}', imagen = '${nombreImagen}', texto = '${texto}' WHERE seccion = '${seccion}' AND carrera = '${carrera}';";

            $resultado = mysqli_query($db,$query);

            if($resultado){
                header('Location: /portalcoordinadoresUPG/admin/?resultado=6&carrera=' . $carrera);
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
        <h1>Actualizar <?php echo ucfirst($seccion);?></h1>

        <a href="../index.php?carrera=<?php echo $_SESSION['carrera'];?>" class="btn">Volver</a>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error ?>
            </div>
        <?php endforeach; ?>

        <form class="formulario contenedor" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>Información</legend>

                <label for="texto">Texto</label>
                <textarea name="texto" id="texto" cols="30" rows="10"><?php echo $datos['texto']; ?></textarea>

                <label for="archivo">Archivos</label>
                <input multiple="" type="file" id="archivo[]" name="archivo[]">

            </fieldset>
            <div class="btn-final">
                <input type="submit" value="Actualizar Sección" class="btn btn-verde">
            </div>
            
        </form>
    </main>

    <script src="../build/js/bundle.min.js"></script>
</body>
</html>