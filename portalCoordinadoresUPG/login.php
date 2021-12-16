<?php
    $carrera = $_GET['carrera'];

// Importar la conexión
require 'includes/config/database.php';
$db = conectarDB();

$errores = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    /* echo "<pre>";
    var_dump($_POST);
    echo "</pre>"; */

    $email = mysqli_real_escape_string( $db, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) );
    $password = mysqli_real_escape_string( $db, $_POST['password']);
    $coordinacion =mysqli_real_escape_string($db, $_POST['carrera']);

    if(!$email){
        $errores[] = "El email no es valido";
    }

    if(!$password){
        $errores[] = "Escribe una contraseña";
    }

    if(empty($errores)){
        //Comprobar si existe
        $query = "SELECT * FROM usuarios WHERE email = '${email}' AND carrera = '${carrera}';";
        $resultado = mysqli_query($db, $query);
    

        if($resultado -> num_rows){
            //Comprobar password
            $usuario = mysqli_fetch_assoc($resultado);

            $autenticado = password_verify($password, $usuario['password']);

            if($autenticado){
                session_start();

                //Comprobar que usuario está autenticado
                $_SESSION['usuario'] = $usuario['email'];
                $_SESSION['login'] = true;
                $_SESSION['carrera'] = $usuario['carrera'];

                

                header('Location: /portalcoordinadoresUPG/admin?carrera=' . $carrera);

                
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="build/css/app.css">
</head>
<body>
    <main class="contenedor contenido-login">
        <h1>Iniciar Sesión</h1>

        <?php foreach($errores as $error) : ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach;  ?>

        <form method="POST" class="formulario" novalidate>
            <fieldset>
                <legend>Correo y Contraseña</legend>

                <label for="email">Correo</label>
                <input type="email" id="email" name="email" placeholder="Escribe tu correo">

                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Escribe tu contraseña">

                <input type="hidden" id="carrera" name="carrera" placeholder="Escribe tu contraseña" value="<?php echo $resultado['carrera'];?>" hidden>

            </fieldset>
            <div class="btn-final">
                <input type="submit" value="Iniciar Sesión" class="btn btn-verde">
            </div>
            
        </form>


    </main>

    <script src="build/js/bundle.min.js"></script>
</body>
</html>