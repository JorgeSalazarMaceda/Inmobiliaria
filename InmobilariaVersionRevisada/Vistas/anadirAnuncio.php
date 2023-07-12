<?php 
require_once __DIR__.'/../Controladores/controladorVivienda.php'; 
if(empty($_SESSION["id_usuario"])){
    header("location: ../login.php");
}
if (isset($_POST["botonEnviar"])){
    $registros = new controladorVivienda();
    $mensaje = $registros->insertarVivienda();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="../assets/css/styleInteriores.css">
    <title>Insertar Anuncio</title>
</head>
<body>
    <center>
<div id="contenedor">
            <div id="contenedor_derecha"> 	
                <form action="" method="post" enctype="multipart/form-data">
                <h3>Añade tu anuncio</h3><br>
                    <label for="tipo">Tipo</label>
                    <select name="tipo" id="tipo" required>
                        <option value="Piso">Piso</option>
                        <option value="Adosado">Adosado</option>
                        <option value="Chalet">Chalet</option>
                        <option value="Casa">Casa</option>
                    </select><br>

                    <label for="zona">Zona</label>
                    <select name="zona" id="zona" required>
                        <option value="Centro">Centro</option>
                        <option value="Sur">Sur</option>
                        <option value="Este">Este</option>
                        <option value="Oeste">Oeste</option>
                    </select><br>

                    <label for="direccion">Dirección</label><br>
                    <input type="text" name="direccion" placeholder="direccion" required><br>

                    <label for="ndormitorios" required>Número de Dormitorios</label><br>
                        <input type="radio" name="ndormitorios" value="1"> 1
                        <input type="radio" name="ndormitorios" value="2"> 2
                        <input type="radio" name="ndormitorios" value="3"> 3
                        <input type="radio" name="ndormitorios" value="4"> 4
                        <input type="radio" name="ndormitorios" value="5 o más"> 5 o más
                    </label><br>

                    <label for="precio">Precio</label>
                    <input type="number" name="precio" placeholder="precio" required><br>

                    <label for="tamano">Tamaño</label>
                    <input type="number" name="tamano" placeholder="tamano" required><br>

                    <label for="extras">Extras: </label>
                    <input type="checkbox" id="checkbox" name="extras[]" value="Piscina">Piscina 
                    <input type="checkbox" id="checkbox" name="extras[]" value="Jardín">Jardín
                    <input type="checkbox" id="checkbox" name="extras[]" value="Garage">Garage<br>

                    <label for="observaciones">Observaciones</label>
                    <input type="text" name="observaciones" placeholder="observaciones" required><br>

                    <!-- <label for="foto">Subir Foto</label>
                    <input type="file" name="foto_subida[]" multiple> -->
                    
                    <!-- <form action="" method="POST" enctype="multipart/form-data"/> -->
                        Añadir imagen: <input name="archivo[]" id="archivo" accept="image/png , image/jpeg, image/jpg" type="file" multiple />
                        <!-- </form> -->

                        <p><?=$mensaje?? ""?></p>
                    <input type="submit" value="Insertar Anuncio" name="botonEnviar">
                    
                </form>
        </div>
    </div> 
    
    <br>
   <p><a href="listadoViviendas.php">Volver al listado</a></p> 
   </center>

</body>
</html>
<?php require_once '../Vistas/plantillas/footer.php'; ?>