<?php
    $carrera = $_GET['carrera'];
    require '../includes/templates/funciones.php';

    $autenticado = estaLogueado();

    if(!$autenticado){
        header('Location: /portalcoordinadoresUPG/');
    }

    $usuario = usuarioValido($carrera);

    if($usuario != $carrera){
        header('Location: /portalcoordinadoresUPG/');
    }

    //Base de datos
    require '../includes/config/database.php';
    $db = conectarDB();

    $query = "SELECT * FROM entradas WHERE carrera = '${carrera}' ORDER BY id DESC";

    $resultadoConsulta = mysqli_query($db, $query);

    $queryFiltro = "SELECT * FROM filtro";
    $resultadoFiltro = mysqli_query($db, $queryFiltro);


    $resultado = $_GET['resultado'] ?? null;

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $id = $_POST['id'];

        $id = filter_var($id, FILTER_VALIDATE_INT);
        
        if($id){

            while($imagenProtegida =mysqli_fetch_assoc($resultadoFiltro)){
                if(!$imagenProtegida['clase']){
                    //Eliminar imagen
                    $queryImg = "SELECT imagen FROM entradas WHERE id = ${id}";
                    $resultadoImg = mysqli_query($db,$queryImg);
                    $entradaImg = mysqli_fetch_assoc($resultadoImg);
                    unlink('../imagenes' . $entradaImg['imagen']);
                }
            }

            //Eliminar entrada
            $query = "DELETE FROM entradas WHERE id = ${id}";

            $resultado = mysqli_query($db,$query);

            if($resultado){
                header('Location: /portalcoordinadoresUPG/admin?resultado=3&carrera=' . $carrera);
            }
        }
    }

    //Así imprimo el valor de la carrera para la seguridad del sitio
    $guardar = $_SESSION['carrera'];

    //var_dump($_SESSION);
    //echo $carrera;

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../build/css/app.css">
</head>
<body>
    <main class="contenedor">
        <div class="header-admin">
            <h1 class="titulo-administrador">Administrador</h1>
            <button class="btn btn-salir">
                <a href="../logout.php">Salir</a>
            </button>
        </div>

        <?php echo alertaError($resultado);?>

        <div class="botones botones-principal">
            <button class="btn btn-admin">
                <a href="entradas/crear.php?carrera=<?php echo $carrera;?>" class="btn">Crear Entrada</a>
            </button>

            <div class="config">
                <button class="btn btn-admin">
                    <a href="restaurar.php?carrera=<?php echo $carrera;?>" class="btn">Actualizar contraseña</a>
                </button>
            
            </div>
            
        </div>

        <div class="botones botones-secciones">
            <button class="btn btn-admin">
                <a href="actualizar-secciones.php/?carrera=<?php echo $carrera;?>&seccion=estancias">Actualizar Estancias</a>
            </button>

            <button class="btn btn-admin">
                <a href="actualizar-secciones.php/?carrera=<?php echo $carrera;?>&seccion=estadias">Actualizar Estadías</a>
            </button>

            <button class="btn btn-admin">
                <a href="actualizar-secciones.php/?carrera=<?php echo $carrera;?>&seccion=titulacion">Actualizar Titulación</a>
            </button>

            <button class="btn btn-admin">
                <a href="actualizar-biografia.php/?carrera=<?php echo $carrera;?>">Actualizar Biografía</a>
            </button>
        </div>
        
        <table class="entradas-admin">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Texto</th>
                    <th>Imagen</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php while($entrada = mysqli_fetch_assoc($resultadoConsulta)) : ?>
                    <tr>
                        <td><?php echo $entrada['titulo'];?> </td>
                        <td><?php echo substr($entrada['texto'],0,300) . '...';?></td>
                        <td>
                            <?php if($entrada['imagen']) : ?>
                                <img class="imagen-tabla" src="<?php echo "../imagenes/" . $entrada['imagen']; ?>"  alt="Imagen Entrada">
                            <?php endif ?>
                            <?php if(!$entrada['imagen']) : ?>
                                <p>Entrada sin imagen</p>
                            <?php endif ?>
                        </td>
                        <td class="fecha-actualizarEntradas"><?php echo $entrada['fecha'];?></td>
                        <td class="botones-act-eli">
                            <a href="entradas/editar.php?id=<?php echo $entrada['id'] . '&carrera=' .$carrera;?>" class="btn btn-amarillo">Actualizar</a>
                            <form method='POST' class="eliminar">

                                <input type="hidden" name="id" value="<?php echo $entrada['id']; ?>">
                                <input type="submit" class="btn btn-rojo" value="eliminar">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        

    </main>
    <?php 
        //Cerrar la conexión
        mysqli_close($db);
    ?>

    <script src="../build/js/bundle.min.js"></script>
</body>
</html>
