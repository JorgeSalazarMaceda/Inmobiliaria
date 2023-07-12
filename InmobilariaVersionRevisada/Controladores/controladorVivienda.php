<?php
@session_start();
require_once __DIR__."/../Modelos/modeloVivienda.php";

class controladorVivienda{

    private $modelo; //Atributo para instanciar en constructor

    function __construct(){
        $this->modelo = new modeloVivienda();
    }

    //************************* FUNCIONES MOSTRAR ****************************
    function mostrarViviendasPAG(){
        $pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : 1; // ¡¡¡¡EN USO!!!! // Esta linea es para realizar la paginación, si hay pagina...Pagina, si no 1
        return $this->modelo->mostrarViviendasPAG($pagina);
    }

    function getTotalViviendas() {
        return $this->modelo->getTotalViviendas();
    }

    function mostrarViviendas(){ //Mostrar normal (SIN PAGINAR)
        return $registros = $this->modelo->mostrarViviendas();
    }

    function mostrarViviendasconFotos(){
        return $registros = $this->modelo->mostrarViviendasconFotos();
    }

    //************************* CRUD ****************************
    //************************* INSERTAR VIVIENDA ****************************
    function insertarVivienda(){
        // Si hay extras, ponlos, si no, déjalos vacios.
        if(isset($_POST["botonEnviar"])){

            if(isset($_POST["extras"])){
                $lastId = $this->modelo->insertarVivienda($_POST["tipo"], $_POST["zona"], $_POST["direccion"],$_POST["ndormitorios"], $_POST["precio"], $_POST["tamano"], $_POST["extras"], $_POST["observaciones"]); 
            }
            else{
                $lastId = $this->modelo->insertarVivienda($_POST["tipo"], $_POST["zona"], $_POST["direccion"],$_POST["ndormitorios"], $_POST["precio"], $_POST["tamano"], "", $_POST["observaciones"]); 
            }
            
            if (count($_FILES) > 0) { // Comprobammos si hay algun archivo file subido en el form, si hay mas de 0 se recorren
                foreach($_FILES["archivo"]["name"] as $index => $fileName) { // Recorro los nombres y veo los valores. $fileName es una variable temporal que se usa en cada iteración para almacenar el nombre del archivo actual.
                    
                    $nombreFichero = $fileName;
                    $temp = $_FILES["archivo"]['tmp_name'][$index]; // Recupero el nombre temporal (archivo tmp) donde se ha guardado la foto o fotos seleccionadas y se guarda en la carpeta fotos de mi aplicación
                    if (move_uploaded_file($temp, __DIR__.'/../assets/fotos/'.$nombreFichero)) {
                        //Cambiamos los permisos del archivo a 777 para poder modificarlo posteriormente
                        chmod(__DIR__.'/../assets/fotos/'.$nombreFichero, 0777);
                    }
                    else {
                        return "¡Se insertó con EXITO sin fotografía!";
                    }
                    
                    $this->modelo->insertarFoto($lastId, $nombreFichero); // Llamamos a insertar fotos con todas las fotos que has indicado o no.
                }
            }   
            return "¡Vivienda insertada con EXITO!";
        } 
    }

    //************************* FUNCION BUSCAR (PARA FILTRADO Y PARA VALUES) ****************************
    function buscarVivienda(){ // Para filtrarlas
            return $registros = $this->modelo->buscarVivienda(); 
    }

    function buscarViviendaPorId() { // Para poner los values y para indicar en MODIFICARVIVIENDA.PHP la vivienda que estás modificando
        if(isset($_POST["id"])){
            $id=$_POST['id'];
            return $this->modelo->buscarViviendaPorId($id);
        }
    }

    //************************* FUNCION MODIFICAR ****************************
    // Si hay extras, ponlos, si no, déjalos vacios.
    function modificarVivienda($id){
        if(isset($_POST["extras"])){
            $this->modelo->modificarVivienda($id,$_POST["tipo"], $_POST["zona"], $_POST["direccion"],$_POST["ndormitorios"], $_POST["precio"], $_POST["tamano"], $_POST["extras"], $_POST["observaciones"]); 
            // header("location: ../Vistas/modificarVivienda.php?mensajeModificar=Vivienda modificada con EXITO!");
            return "Vivienda modificada con EXITO!";
        }
        else{
            $this->modelo->modificarVivienda($id,$_POST["tipo"], $_POST["zona"], $_POST["direccion"],$_POST["ndormitorios"], $_POST["precio"], $_POST["tamano"], "", $_POST["observaciones"]); 
            // header("location: ../Vistas/modificarVivienda.php?mensajeModificar=Vivienda modificada con EXITO!");
            return "Vivienda modificada con EXITO!";
        } 
    }

    //************************* BORRAR VIVIENDA ****************************
    function borrarVivienda($id){ 
        $borrar = $this->modelo->borrarVivienda($id);
    }
    
    //************************* GETS VALORES VIVIENDA PARA DEJARLOS SELECCIONADOS EN EL FILTRADO buscarVivienda.php ****************************
    function getSelectedValue($nombreCampo, $valor) { // Para mostrar en buscarVivienda.php los valores de los select option
        echo isset($_POST[$nombreCampo]) && $_POST[$nombreCampo] == $valor ? "selected" : "";
    }

    function getCheckedValue($nombreCampo, $valor) { // Para mostrar en buscarVivienda.php los valores de los input radio 
        echo isset($_POST[$nombreCampo]) && $_POST[$nombreCampo] == $valor ? "checked" : "";
    }

    function getCheckboxValue($nombreCampo, $valor) { // Para mostrar en buscarVivienda.php los valores de los checkbox []
        if (isset($_POST[$nombreCampo])) {
            echo in_array($valor, $_POST[$nombreCampo]) ? "checked" : "";
        }
    }
}

// ********************************* INSTANCIAS *********************************
    // EJECUTAR Y LLAMAR A MODIFICAR
    $controlador_vivienda = new controladorVivienda();
    if(isset($_POST["botonModificar"])){
        $controlador_vivienda->modificarVivienda($_POST['id']);
    }

    if (isset($_POST["borrarVivienda"])){
        $id=$_POST["borrarVivienda"];
        $controlador_vivienda->borrarVivienda($id);
    }


    // Si se hace una llamada AJAX los datos POST que se reciben, se deben recoger de esta manera.
    // Asignamos a $post un objeto JSON que se obtiene a partir del contenido enviado en el cuerpo de la solicitud HTTP en listadoViviendas.php

    // $post = json_decode(file_get_contents('php://input'),true); // json_decode para convertir en un objeto de PHP, y true para indicars que se quiere un objeto de PHP en formato de array en lugar de un objeto de clase stdClass.
    // if (isset($post["borrarVivienda"])){ //Si pulsas borrarVivienda
    //     $id=$post["borrarVivienda"]; // guardamos en $id el $_POST
    //     $controlador_vivienda->borrarVivienda($id); // Llamamos al controlador y a la función
    //     echo json_encode(array("mensaje" => "borrado")); // función json_encode para devolver una respuesta JSON al cliente que solicitó el borrado. La respuesta contiene un mensaje "borrado".
    //     exit();
    // }

    
    // json_decode se utiliza para convertir una cadena de texto en formato JSON en una estructura de datos en PHP, como un array o un objeto.
    // json_encode se utiliza para convertir una estructura de datos de PHP, como un array o un objeto, en una cadena de texto en formato JSON.
      
// $registros = new modeloVivienda();
// print_r($registros->buscarVivienda());
?>