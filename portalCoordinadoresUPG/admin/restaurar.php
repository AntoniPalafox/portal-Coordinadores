<?php
    $carrera = $_GET['carrera'];

// Importar la conexión
require '../includes/config/database.php';
$db = conectarDB();

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

$errores = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    /* echo "<pre>";
    var_dump($_POST);
    echo "</pre>"; */

    $password = mysqli_real_escape_string( $db, $_POST['password']);
    $passwordNueva = mysqli_real_escape_string( $db, $_POST['passwordNueva']);
    $passwordNuevaReal = mysqli_real_escape_string( $db, $_POST['passwordNuevaReal']);



    if(!$password){
        $errores[] = "Escribe una contraseña";
    }
    if(!$passwordNueva){
        $errores[] = "Escribe una contraseña nueva";
    }
    if(!$passwordNuevaReal){
        $errores[] = "comprueba la nueva contraseña";
    }

    if(empty($errores)){
        //Comprobar si existe
        $query = "SELECT * FROM usuarios WHERE carrera = '${carrera}';";
        $resultado = mysqli_query($db, $query);
    

        if($resultado -> num_rows){
            //Comprobar password
            $usuario = mysqli_fetch_assoc($resultado);

            $password = password_verify($password, $usuario['password']);

            if($password){

                if($passwordNueva == $passwordNuevaReal){

                    $passwordSegura = password_hash($passwordNuevaReal, PASSWORD_BCRYPT);
                    //Insertar en la base de datos
                    $query = " UPDATE usuarios SET password = '${passwordSegura}' WHERE carrera = '${carrera}' ;";

                    $resultado = mysqli_query($db,$query);

                    header('Location: /portalcoordinadoresUPG/admin?resultado=4&carrera=' . $carrera);
                }else{
                    $errores[] = "La contraseña nueva no coincide";
                }    

            }else{
                $errores[] = "Contraseña incorrecta";
            }

        }else{
            $errores[] = "El usuario no existe";
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
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../build/css/app.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
</head>
<body>
    <main class="contenedor contenido-login">
        <h1>Cambia tu contraseña</h1>

        <?php foreach($errores as $error) : ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach;  ?>

        <form method="POST" class="formulario">
            <fieldset>
                <legend>Contraseña actual</legend>

                <label for="password">Contraseña Actual</label>
                <input type="password" id="password" name="password" placeholder="Escribe tu contraseña actual">

                <input type="hidden" id="carrera" name="carrera" placeholder="Escribe tu contraseña" value="<?php echo $resultado['carrera'];?>" hidden>

            </fieldset>

            <fieldset>
                <legend>Nueva Contraseña</legend>

                <label for="password">Escribe tu nueva contraseña:</label>
                <input type="password" id="password" name="passwordNueva" placeholder="Contraseña nueva">

                <label for="password">Confirma tu nueva contraseña:</label>
                <input type="password" id="password" name="passwordNuevaReal" placeholder="Confirma contraseña nueva">

            </fieldset>

            <input type="submit" value="Actualizar contraseña" class="btn btn-verde">
        </form>


    </main>
</body>
</html>