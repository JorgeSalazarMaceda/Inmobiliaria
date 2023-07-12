<?php
@session_start();
require_once __DIR__."/../Modelos/modeloUsuario.php";

class controladorUsuario{

    private $modelo; //Atributo para instanciar en constructor

    function __construct(){
        $this->modelo = new modeloUsuario();
    }


    /************************* FUNCION PARA COMPROBAR LOGIN (SESION Y COOKIE) (NO USO) ****************************/
    // function comprobar_login(){ // No está en uso
    //     $registros = $this->modelo->comprobar_login($_POST["id_usuario"]); // Estraemos registros del modelo

    //     // Si la contraseña y el usuario son correctos, podrías hacer un header para redirigir a otra página
    //     if(($_POST["id_usuario"] == $registros[0]["id_usuario"]) && (password_verify($_POST["contrasena"],$registros[0]["password"]))){
    //     // if(count($registros) > 0){
    //         $_SESSION["id_usuario"] = $registros[0]["id_usuario"];

    //         // Si ya hay una ultima conexion navegador, se almacena en $ultima_sesion para mostrarla luego. (Si no, se mostraria la actual)
    //         if(isset($_COOKIE["ultima_conexion"])) {
    //             $ultima_conexion = $_COOKIE["ultima_conexion"];
    //         } else {
    //             $ultima_conexion = "Nunca se ha conectado antes";
    //         }
    //         // Creamos la cookie--> NOMBRE, VALOR, EXPIRACION (30 días desde ya), RUTA de acceso (URL, si pones / es para todo el sitio web)
    //         setcookie("ultima_conexion", $registros[0]["last_conection"], time() + (86400 * 30), "/");
            
            
    //         // Si todo OK, ACCEDES y se envia por GET la cookie de ultima_sesion para que sea accesible en la vista header con $_GET["ultima_conexion"]
    //         header("location: Vistas/acceso.php?ultima_conexion=$ultima_conexion");
    //     }
    //     else{
    //         header("location: login.php?mensaje=Login incorrecto");
    //     }
    // }

    function comprobar_login(){ // EN USO
        // Si el id_usuario es admin, llamamos a admin (para asegurar que siempre admin (que hace un update encriptado) tendrá la contraseña admin en vez de 1234)
        if ($_POST["id_usuario"] == "admin") {
            $this->modelo->admin();
        }
        $registros = $this->modelo->comprobar_login($_POST["id_usuario"]); // Extraemos registros del modelo
        
        if(($_POST["id_usuario"] == $registros[0]["id_usuario"]) && (password_verify($_POST["contrasena"],$registros[0]["password"]))){
            $_SESSION["id_usuario"] = $registros[0]["id_usuario"];
            // Si todo OK, ACCEDES
            header("location: Vistas/acceso.php");
        }
        else{
            header("location: login.php?mensaje=Login incorrecto");
        }
    }

    /************************* FUNCION PARA COMPROBAR SESION ****************************/ /* PROBLEMA REDIRECCIONAMIENTO ESTATICO (PARA HEADER),RESTO SE CONTROLA EN TODAS LAS VISTAS*/
    function comprobar_sesion() {
        // SI NO HAY SESION, DIRECTAMENTE TE LLEVO A LOGIN --> En login llamamos a comprueba login con password_Verify y creamos la sesión del código y del camarero. 
        // La enviamos aquí
        if(empty($_SESSION["id_usuario"])){
            // echo "No está logueado";
            header("location: ./login.php");
        }
    }

    // **************************** COMPROBAR SI ES EL ADMIN PARA MOSTRAR LA GESTION DE USUARIOS, SI NO ES, AL ACCESO (LISTADO NORMAL) *******************************
    function comprobar_admin() {
        if($_SESSION["id_usuario"] !== "admin") {
            header("location: ./acceso.php");
        }
    }
    //************************* FUNCION PARA CERRAR SESION --> CREAMOS COOKIE AL CERRAR ****************************
    function cerrarSesion(){
        // Si input en cerrar sesion, sesion destroy y derivamos a login.
        // $this->modelo->guardarUltimaConexion($_SESSION["id_usuario"]); ESTO ERA CUANDO GUARDABAMOS LA COOKIE CON EL UPDATE EN LA BD
        if (isset($_POST["cerrarSesion"])) {
            // Al cerrar la sesión CREAMOS LA COOKIE--> NOMBRE, VALOR, EXPIRACION (30 días desde ya), RUTA de acceso (URL, si pones / es para todo el sitio web)
            setcookie($_SESSION["id_usuario"], date("Y-m-d H:i:s"), time() + (86400 * 30), "/");
            session_destroy();
            header('Location: ../login.php');
        }
    }

    //************************* MOSTRAR USUARIOS ****************************
    function mostrarUsuarios() {
        return $this->modelo->mostrarUsuarios();
    }

    //************************* GUARDAR USUARIOS ****************************
    function guardarUsuario() {
        // $usuario = $_POST["usuario"];
        // $password = $_POST["password"];
        // $password = password_hash($password, PASSWORD_DEFAULT);
        // $this->modelo->insertarUsuario($usuario, $password);
        
        $contrasenaAleatoria = mb_substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, random_int(4,10)); //Desde el caracter 0 genera contraseña de 4 a 10 caracteres
        
        $passwordCifrado = password_hash($contrasenaAleatoria, PASSWORD_DEFAULT);

        $usuario = $_POST["usuario"];
        $this->modelo->insertarUsuario($usuario, $passwordCifrado);
            header("location:../login.php?mensajeContrasena=Su contraseña es:".$contrasenaAleatoria); 
    }
    
    //************************* BORRAR USUARIOS --> SOLO SI ERES ADMIN ****************************
    function borrarUsuario($id){
        if(isset($_POST["borrarUsuario"])){
            if($_SESSION["id_usuario"] == "admin") {
                $this->modelo->borrarUsuario($id);
            }
        }
    }
}

// Pruebas
// if (isset($_POST["borrarUsuario"])){
//     $id=$_POST["borrarUsuario"];
//     $modelo->borrarUsuario($id);
// }

// $modelo = new controladorUsuario();
// echo "hola perro";
// print_r($modelo->mostrarUsuarios());
// echo "hola perro";
// print_r($_SESSION);
?>