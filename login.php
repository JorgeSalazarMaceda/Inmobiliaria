<?php 
    require_once __DIR__."/Controladores/controladorUsuario.php";
    $controladorUsuario = new controladorUsuario();
    if (isset($_POST["botonEnviar"])) {
        $controladorUsuario->comprobar_login();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="./assets/css/styleLogin.css">
    <title>Login INMOBILIARIA</title>
</head>
<body>
    <div id="contenedor">
         <div id="contenedor_derecha">
             <form action="" method="post">
                 <h1>Bienvenido a Jorgealista</h1>
                 <h3>Necesitamos que nos digas quién éres</h3><br>
                 <label for="id_usuario">id_usuario</label>
                 <input type="text" name="id_usuario" placeholder="id_usuario" required><br>
                 <label for="contrasena">Contraseña</label>
                 <input type="password" name="contrasena" placeholder="Escribe tu contraseña" required><br><br>
                 <p id="mensajes"><?= $_GET['mensaje'] ?? "" ?></p> <!--Mensaje en caso de error-->
                 <p id="mensajes"><?= $_GET['mensajeContrasena'] ?? "" ?></p> <!--Mensaje para darte la contraseña si creas un NUEVO USUARIO-->
                 <input type="submit" value="LOGIN" name="botonEnviar"> <!--Cuando pinches aquí, llamamos a ver si existe ese login-->
             </form>
         </div>
    </div>
</body>
</html>