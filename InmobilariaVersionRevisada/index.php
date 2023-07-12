<?php 
 // Comprobamos que existe la sesión. Si no existe, derivamos a login.php
    if(empty($_SESSION["id_usuario"])){
        header("location: login.php");
    }
?>