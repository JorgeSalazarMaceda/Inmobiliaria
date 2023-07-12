<?php 

require_once __DIR__ ."/../Controladores/controladorVivienda.php";
require_once __DIR__."/../config.php"; // Path para mostrar fotos
// *********************** INSTANCIAMOS EL CONTROLLER **********************
$controlador = new controladorVivienda(); // Esto es una instancia de un objeto

require_once '../Vistas/plantillas/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="../assets/css/style.css">
    <title>Listado Viviendas</title>
</head>
<body>
<?php

if(empty($_SESSION["id_usuario"])){
    header("location: ../login.php");
}
// Miramos si hay vista de anadirAnuncio, es decir, si se ha pulsado anadirAnuncio, si no muestra listado.
if (isset($_GET['Vistas']) && $_GET['Vistas'] === 'anadirAnuncio') {
    include "anadirAnuncio.php";
    exit();
} else {
    
    echo '<p><a href="?Vistas=anadirAnuncio">Añadir Vivienda</a></p><br><br>';

$viviendas=$controlador->mostrarViviendasPAG(); // Esto es meter en un array los objetos = array de objetos
echo '
<table>
<thead>
  <tr>';
foreach ($viviendas as $indices => $valores) {
    foreach ($valores as $encabezados => $datos) { 
        echo '<th>' . ucfirst($encabezados) . '</th>'; //El nombre de cada columna en mayuscula
    }
    echo '
    <th>Borrar</th>
    <th>Modificar</th>
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
                $fotos = explode(",", $datos);
                foreach($fotos as $index => $foto) {
                    echo '<a href="__DIR__/../../assets/fotos/'. $foto . '" target="_blank">Imagen '.($index +1).'</a><br>';
                    // echo '<a href="'.$pathAssets.'fotos/' . $foto . '" target="_blank">Imagen '.($index +1).'</a><br>';
                }
            } else {
                echo "No hay fotos";
            }
            echo "</td>";
        } else {
            echo '<td>' . $datos . '</td>';
        }
    }
    
    // echo "<td><button type='button' class='borrarVivienda' name='borrarVivienda' value='".$valores->id."'>Borrar Vivienda</button></td>"; // Boton con AJAX
    echo "<td><form method='POST' action=''><button type='submit' id='papelera' name='borrarVivienda' value='".$valores->id."'><img src='../assets/fotos/iconoPapelera.png'/></button></form></td>";
    echo "<td><form method='POST' action='./modificarVivienda.php'><input type='hidden' name='id' value='".$valores->id."'/><input type='image'src='../assets/fotos/iconoEditar.png' id='editar'/></form></td>";
    // Envio con un hidden el id a la vista modificar con el action
    echo '</tr>';
}
echo '</table>';
}
    // ***************** MOSTRAMOS LA PAGINACION  ******************
    $total = $controlador->getTotalViviendas(); 
    $numPaginas = ceil($total / 10);
    echo "<div class='paginacion'>";
    echo "<a href='?pagina=1'> Inicio </a>"; // Boton inicio
    for($i = 1; $i <= $numPaginas; $i++) {
        echo "<a href='listadoViviendas.php?pagina=$i'>".$i."</a>";
    }
    echo "<a href='?pagina=" . $numPaginas ."'> Fin </a>"; // Boton final
    echo "</div>";
    echo "<br><br>";
?>
<?php require_once '../Vistas/plantillas/footer.php'; ?>




<!-- 
    ******************** PARTE CON AJAX PARA BORRAR ********************
    Hay que descomentar el botón habilitado en la vista: <button type='button' class='borrarVivienda'... en esta misma vista
    Hay que descomentar la llamada en el controladorVivienda.php para llamar y borrar la vivienda usando AJAX
-->
    <script>
        document.querySelectorAll(".borrarVivienda").forEach(button => { // Elegimos el boton borrarVivienda de Ajax asignando evento onclick
            button.onclick = (event) => {
                event.preventDefault();
                fetch(location.href, {method: "POST", body: JSON.stringify({borrarVivienda: event.target.value})})  // llamada a fetch con la URL actual (location.href) y se envía una solicitud POST con un objeto JSON que contiene una propiedad borrarVivienda
                    .then((res) => {
                        return res.json(); // Devolvemos el body de la respuesta en formato JSON
                    }).then(data => {
                        if (data.mensaje == "borrado") {
                            location.reload();
                        }
                    }).catch(error => {
                        console.log("Error",error);
                    });
            }
        })
    </script>

</body>
</html>