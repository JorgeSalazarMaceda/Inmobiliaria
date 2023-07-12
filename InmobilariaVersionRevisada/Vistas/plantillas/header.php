<?php 
    require_once __DIR__."/../../Controladores/controladorUsuario.php";
    $controladorUsuario = new controladorUsuario();
    $controladorUsuario->comprobar_sesion(); // Comprobamos que existe la sesión. Si no existe, derivamos a login.php

    if (isset($_POST["cerrarSesion"])) {  // Cerramos sesion y derivamos a login.php
        $controladorUsuario->cerrarSesion();
    }
?>
<!DOCTYPE html>
<?php
@session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link type="text/css" rel="stylesheet" href="../assets/css/style.css">
    </head>
    <body>
        <header>
            <h2>INMOBILIARIA</h2>
            <h3>Bienvenid@: <?php echo $_SESSION["id_usuario"]; ?></h3>
            <h3>Ultima conexión: <?php echo $_COOKIE[$_SESSION["id_usuario"]]?? "Es la primera vez que entras"; ?></h3>
            <nav>
                <ul>
                    <!-- Si eres admin, muéstrame el enlace de la gestión de usuarios, si no no -->
                    <!-- (Puede insertar y borrar, los admin nunca pueden ser borrados) -->
                    <?php if($_SESSION["id_usuario"] == "admin"){
                        echo "<li><a href='../Vistas/listadoUsuarios.php'>Gestión de usuarios</a></li>";    
                    }?>
                    <li><a href="../Vistas/buscarVivienda.php">Buscar viviendas</a></li>
                    <!-- <li><a href="?vista=cerrarSesion.php">Cerrar Sesión</a></li> -->
                </ul>
            </nav>

            <!----------------------------------- BOTON CERRAR SESION ------------------------------------>
            <form action="" method="post">
                <input type="submit" name="cerrarSesion" value="Cerrar Sesión">
            </form>
        </header>