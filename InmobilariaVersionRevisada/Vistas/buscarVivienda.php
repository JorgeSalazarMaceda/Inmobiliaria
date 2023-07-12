<?php 
require_once '../Vistas/plantillas/header.php'; 
require_once __DIR__ ."/../Controladores/controladorVivienda.php";
require_once __DIR__ ."/../Modelos/modeloVivienda.php";
$buscar = new controladorVivienda();
if(empty($_SESSION["id_usuario"])){
    header("location: ../login.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="../assets/css/styleInteriores.css">
    <title>Buscar Vivienda</title>
</head>
<?php 
    if(isset($_POST['botonBuscarVivienda'])){
    $viviendas =  $buscar->buscarVivienda();
    if (count($viviendas) > 0) {
        
    // print_r($viviendas);
    echo'
    <table style="border: 1px solid black; overflow-y: scroll;max-height: 300px;">
    <thead>
      <tr>';
    foreach ($viviendas as $indices => $valores) {
        foreach ($valores as $encabezados => $datos) { 
            echo '<th>' . ucfirst($encabezados) . '</th>'; //El nombre de cada columna en mayuscula
        }
        echo '
      </thead>';
        break;
    }
    echo
    '</tr>';
    echo '<tr>';
    foreach ($viviendas  as $indices => $valores) {
        foreach ($valores as $encabezados => $datos) { 
            if ($encabezados == "fotos") {
                echo "<td>";
                if ($datos) {
                    $fotos = explode(",", $datos); // La cadena de caracteres la convertimos en un array separando sus elementos por comas ej explode(delimitador, cadena1,cadena2);
                    foreach($fotos as $index => $foto) {
                        echo '<a href="'.$pathAssets.'fotos/' . $foto . '" target="_blank">Imagen '.($index +1).'</a><br>'; //Iteramos añadiendo numeros
                    }
                } else {
                    echo "No hay fotos";
                }
                echo "</td>";
            } else {
                echo '<td>' . $datos . '</td>';
            }
        }
        echo '</tr>';
    }
    echo '</table>';
    } else {
        echo "<center><h3>No se encontraron viviendas</h3></center>";
    }
} else {
    echo "<center><h3>Busca la vivienda de tus sueños</h3></center>";
}
?>
<body>
    <center style="padding-bottom: 100px;">
<div id="contenedor">
        <div> 	
            <form action="" method="post">
                <label for="tipo">Tipo</label>
                <select name="tipo" id="tipo">
                    <option value="%">---------</option>
                    <option value="Piso" <?php $buscar->getSelectedValue("tipo", "Piso") ?>>Piso</option>
                    <option value="Adosado" <?php $buscar->getSelectedValue("tipo", "Adosado") ?>>Adosado</option>
                    <option value="Chalet" <?php $buscar->getSelectedValue("tipo", "Chalet") ?>>Chalet</option>
                    <option value="Casa" <?php $buscar->getSelectedValue("tipo", "Casa") ?>>Casa</option>
                </select><br><br>
                <label for="zona">Zona</label>
                <select name="zona" id="zona">
                    <option value="%">---------</option>
                    <option value="Centro" <?php $buscar->getSelectedValue("zona", "Centro") ?>>Centro</option>
                    <option value="Sur" <?php $buscar->getSelectedValue("zona", "Sur") ?>>Sur</option>
                    <option value="Este" <?php $buscar->getSelectedValue("zona", "Este") ?>>Este</option>
                    <option value="Oeste" <?php $buscar->getSelectedValue("zona", "Oeste") ?>>Oeste</option>
                </select><br><br>
                <label for="ndormitorios">Número de Dormitorios</label><br>
                    <input type="radio" name="ndormitorios" value=""> Ninguno
                    <input type="radio" name="ndormitorios" value="1" <?php $buscar->getCheckedValue("ndormitorios", "1"); ?>> 1
                    <input type="radio" name="ndormitorios" value="2" <?php $buscar->getCheckedValue("ndormitorios", "2"); ?>> 2
                    <input type="radio" name="ndormitorios" value="3" <?php $buscar->getCheckedValue("ndormitorios", "3"); ?>> 3
                    <input type="radio" name="ndormitorios" value="4" <?php $buscar->getCheckedValue("ndormitorios", "4"); ?>> 4
                    <input type="radio" name="ndormitorios" value="5 o más" <?php $buscar->getCheckedValue("ndormitorios", "5 o más"); ?>> 5 o más
                </label><br><br>
                <label for="precio">Precio:
                    <input type="radio" name="precio" value=""> Ninguno
                    <br><input type="radio" name="precio" value="1" <?php $buscar->getCheckedValue("precio", "1"); ?> > Menos de 100.000 €
                    <br><input type="radio" name="precio" value="2" <?php $buscar->getCheckedValue("precio", "2"); ?>> Entre 100.000 € y 200.000 €
                    <br><input type="radio" name="precio" value="3" <?php $buscar->getCheckedValue("precio", "3"); ?>> Entre 200.000 € y 300.000 €
                    <br><input type="radio" name="precio" value="4" <?php $buscar->getCheckedValue("precio", "4"); ?>> Entre 300.000 € y 400.000 €
                    <br><input type="radio" name="precio" value="5" <?php $buscar->getCheckedValue("precio", "5"); ?>> Mas de 500.000 €, puro lujo
                </label><br><br>
                <label for="extras">Extras: </label>
                <input type="checkbox" id="checkbox" name="extras[]" value="Piscina" <?php $buscar->getCheckboxValue("extras", "Piscina"); ?>>Piscina 
                <input type="checkbox" id="checkbox" name="extras[]" value="Jardín" <?php $buscar->getCheckboxValue("extras", "Jardín"); ?>>Jardín
                <input type="checkbox" id="checkbox" name="extras[]" value="Garage" <?php $buscar->getCheckboxValue("extras", "Garage"); ?>>Garage
                <br><br><input type="submit" value="BuscarVivienda" name="botonBuscarVivienda">
            </form>
        </div>
    </div>
    <br>
</form>
<p><a href="./listadoViviendas.php">Volver al listado</a></p>
</center>
</body>
<?php require_once '../Vistas/plantillas/footer.php'; ?>
</html>