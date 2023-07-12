<?php require_once __DIR__ ."/../Controladores/controladorVivienda.php";
require_once __DIR__ ."/../Modelos/modeloVivienda.php";
require_once '../Vistas/plantillas/header.php';
$registros = new controladorVivienda();
// $registros->mostrarViviendas();
// Control sesión
if(empty($_SESSION["id_usuario"])){
    header("location: ../login.php");
}

//Recogo el id desde el hidden de listadoViviendas
if(isset($_POST["id"])){
    $id=$_POST['id'];
    if (isset($_POST["botonModificar"])){
        $mensaje = $registros->modificarVivienda($id); // Mensaje es el mensaje return de exito del controlador
        // print_r($_POST);
    }
}
$vivienda = $registros->buscarViviendaPorId(); //Llamamos a metodo de id concreto de la vivienda seleccionada para hacer los echo y te muestre los datos
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="../assets/css/styleInteriores.css">
    <title>Modificar</title>
</head>
<body>
    <center>
    <div id="contenedor">
        <div> 	
            <form action="" method="post">
            <h3>Realiza tus modificaciones para la vivienda <?php echo $vivienda->id ?></h3><br>
                <label for="tipo">Tipo</label>
                <select name="tipo" id="tipo">
                    <option value="Piso" <?php echo $vivienda->tipo == "Piso" ? "selected" : "" ?>>Piso</option>
                    <option value="Adosado" <?php echo $vivienda->tipo == "Adosado" ? "selected" : "" ?>>Adosado</option>
                    <option value="Chalet" <?php echo $vivienda->tipo == "Chalet" ? "selected" : "" ?>>Chalet</option>
                    <option value="Casa" <?php echo $vivienda->tipo == "Casa" ? "selected" : "" ?>>Casa</option>
                </select><br>
                <label for="zona">Zona</label>
                <select name="zona" id="zona">
                    <option value="Centro" <?php echo $vivienda->zona == "Centro" ? "selected" : "" ?>>Centro</option>
                    <option value="Sur" <?php echo $vivienda->zona == "Sur" ? "selected" : "" ?>>Sur</option>
                    <option value="Este" <?php echo $vivienda->zona == "Este" ? "selected" : "" ?>>Este</option>
                    <option value="Oeste" <?php echo $vivienda->zona == "Oeste" ? "selected" : "" ?>>Oeste</option>
                </select><br>
                <label for="direccion">Dirección</label><br>
                <input type="text" name="direccion" placeholder="direccion" value="<?php echo $vivienda->direccion ?>"><br>
                <label for="ndormitorios">Número de Dormitorios</label><br>
                    <input type="radio" name="ndormitorios" value="1" <?php echo $vivienda->ndormitorios == "1" ? "checked" : "" ?>> 1
                    <input type="radio" name="ndormitorios" <?php echo $vivienda->ndormitorios == "2" ? "checked" : "" ?> value="2"> 2
                    <input type="radio" name="ndormitorios" <?php echo $vivienda->ndormitorios == "3" ? "checked" : "" ?> value="3"> 3
                    <input type="radio" name="ndormitorios" <?php echo $vivienda->ndormitorios == "4" ? "checked" : "" ?> value="4"> 4
                    <input type="radio" name="ndormitorios" <?php echo $vivienda->ndormitorios == "5 o más" ? "checked" : "" ?> value="5 o más"> 5 o más
                </label><br>
                <label for="precio">Precio</label>
                <input type="text" name="precio" placeholder="precio" value="<?php echo $vivienda->precio ?>"><br>
                <label for="tamano">Tamaño</label>
                <input type="text" name="tamano" placeholder="tamano" value="<?php echo $vivienda->tamano ?>"><br>
                <label for="extras">Extras: </label>
                <input type="checkbox" id="checkbox" name="extras[]" value="Piscina" <?php echo in_array("Piscina", explode(",",$vivienda->extras)) ? "checked" : "" ?>>Piscina 
                <input type="checkbox" id="checkbox" name="extras[]" value="Jardín" <?php echo in_array("Jardín", explode(",",$vivienda->extras)) ? "checked" : "" ?>>Jardin
                <input type="checkbox" id="checkbox" name="extras[]" value="Garage" <?php echo in_array("Garage", explode(",",$vivienda->extras)) ? "checked" : "" ?>>Garage<br>
                <label for="observaciones">Observaciones</label>
                <input type="text" name="observaciones" placeholder="observaciones" value="<?php echo $vivienda->observaciones ?>"><br>
                
                <input type="hidden" name="id" value="<?= $id ?>" /> <!--Enviamos con un hidden el id-->
                <p><?=$mensaje?? ""?></p>
                <input type="submit" value="Modificar" name="botonModificar">
            </form>
        </div>
    </div>
    <br>
    <p><a href="listadoViviendas.php">Volver al listado</a></p>
    </center>
    <?php require_once '../Vistas/plantillas/footer.php'; ?>
</body>
</html>