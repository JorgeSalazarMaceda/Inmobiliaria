<?php
// session_start();
// require_once __DIR__."/../Modelos/modeloVivienda.php";
// require_once __DIR__."/../Modelos/modeloUsuario.php";
class controladorVistas{

    //Asignamos una vista por defecto, en caso de que no se especifique ninguna
    private $vistaPorDefecto = "login";

    function cargaMVC(){

        //Comprobamos si se ha recibido alguna vista por GET (URL)
        if (isset($_GET["vista"])){
            
            // Comprobamos si existe el controlador por el nombre recibido
            if(is_file(__DIR__."/".$_GET["vista"].".php")){

                $controlador = $this->loadController($_GET["vista"]);

                // Incluimos la vista con el mismo nombre
                include __DIR__."/../Vistas/".$_GET["vista"].".php";
            } else{
                echo "404: Esta vista no existe";
            }
           
        } else{
            $controlador = $this->loadController($this->vistaPorDefecto);
            include __DIR__."/../Vistas/$this->vistaPorDefecto.php";
        } 
    }


    private function loadController($vista){
        //Incluimos el controlador
        include __DIR__."/../Controladores/$vista.php";

        // Pongo la primera letra en mayúscula por convención de nombre de las clases (MiClase)
        $vista = ucfirst($vista);

        // Instanciamos la clase de ese controlador (Se tiene que llamar igual la clase que el fichero y la vista)
        // Esta forma de instanciar un objeto es una forma dinámica, ya que se está usando una variabel en lugar del nombre de la clase
        // Instancia normal: new Home();
        // Instancia dinámica:
        //      $nombreClase = "Home";
        //      new $nombreClase();

        return new $vista();
    }

    /************************* FUNCION PARA CERRAR SESION ****************************/
    function cerrarSesion(){
        //Si input en cerrar sesion, sesion destroy y derivamos a login.
        if (isset($_POST["cerrarSesion"])) {
            session_destroy();
            header('Location: ../login.php');
        }
    }
}





?>