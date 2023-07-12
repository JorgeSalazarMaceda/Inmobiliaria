<?php
require_once __DIR__ . "/../Controladores/controladorUsuario.php";
$controlador = new controladorUsuario();
$controlador->comprobar_admin(); // Comprobamos que el admin puede añadir usuarios
if (isset($_POST["borrarUsuario"])){
    $id=$_POST["borrarUsuario"];
    $controlador->borrarUsuario($id);
}
$usuarios = $controlador->mostrarUsuarios();
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
    <link type="text/css" rel="stylesheet" href="../assets/css/style.css">
    <title>Listado Usuarios</title>
</head>
<body>
<?php require_once '../Vistas/plantillas/header.php'; ?>
<p><a href="anadirUsuario.php">Añadir usuario</a></p>

<?php
echo '
<table>
<thead>
  <tr>
    <th>Usuarios</th>
    <th>Borrar</th>
  </thead>';

echo
'</tr>';
echo '<tr>';
foreach ($usuarios  as $usuario => $valores) {
            echo '<td>' . $valores->id_usuario . '</td>';
    echo "<td><form method='POST' action=''><button type='submit' id='papelera' name='borrarUsuario' value='".$valores->id_usuario."'><img src='../assets/fotos/iconoPapelera.png'/></button</form></td>";
    echo '</tr>';
}
echo '</table>';
?>
<p><a href="listadoViviendas.php">Volver al listado</a></p>
<?php require_once '../vistas/plantillas/footer.php'; ?>
</body>
</html>