<?php 
    require_once __DIR__."/../Controladores/controladorUsuario.php";
    require_once '../Vistas/plantillas/header.php';
    if(empty($_SESSION["id_usuario"])){
        header("location: ../login.php");
    }
    $controlador = new controladorUsuario();
    $controlador->comprobar_admin(); //Comprobamos que solo el admin puede añadir usuarios

    if(isset($_POST["guardarUsuario"])) {
        $controlador->guardarUsuario();
        echo "<p>Se ha guardado el usuario!</p>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="../assets/css/styleInteriores.css">
    <title>Añadir Usuario</title>
</head>
<body>
<center>
<div id="contenedor">
    <h3>Inserta un nuevo usuario</h3>
        <div> 	
        <form action="" method="POST">
            <label>Usuario:</label>
            <input type="text" name="usuario">
            <br>
            <button type="submit" name="guardarUsuario">Guardar</button>
            </form>
        </div>
    </div>
    <br>
<p><a href="./listadoViviendas.php">Volver al listado</a></p>
</center>
<?php require_once '../vistas/plantillas/footer.php'; ?>
</body>
</html>
