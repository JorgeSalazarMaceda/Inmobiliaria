<?php

require_once __DIR__."/conexion.php";

class modeloVivienda{

    private $conexion; // Atributo para almacenar en una instancia la conexión

    function __construct()
    {
        $this->conexion=new Conexion();
    }

        // **************************** PAGINACIÓN Y MOSTRAR CON FOTOS *************************
    
        function mostrarViviendasPAG($pagina){ // METODO EN USO --> CON FOTOS (GROUP_CONCAT) Y PAGINADO
            // Unimos las dos tablas con LEFT JOIN (viviendas y fotos haciendo que coincidan por id)
            // Agrupamos por el id de las viviendas
            // GROUP_CONCAT para concatenar los valores de la columna fotos.foto en una sola cadena, separados por comas. 
            //La función se utiliza dentro de la cláusula GROUP BY para agrupar todas las fotos correspondientes a una vivienda en particular.

            $limit = 10;
            $offset = ($pagina - 1) * $limit;
            $sql = "
                SELECT viviendas.id, tipo, zona, direccion, ndormitorios, precio, tamano, GROUP_CONCAT(fotos.foto) as fotos
                FROM viviendas
                LEFT JOIN fotos on fotos.id_vivienda = viviendas.id 
                GROUP BY viviendas.id
                ORDER BY fecha_anuncio ASC limit $offset, $limit
            ";
            $stmt = $this->conexion->conn->prepare($sql); // conn contiene el PDO
            $stmt->execute();
            $registros = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $registros;
        }
        function getTotalViviendas() { // OBTENEMOS EL TOTAL DE LAS VIVIENDAS
            $sql= "SELECT count(*) as total FROM viviendas";
            $stmt = $this->conexion->conn->prepare($sql);
            $stmt->execute();
            $totalViviendas = $stmt->fetch(PDO::FETCH_OBJ);
            return $totalViviendas->total;
        }

        function mostrarViviendas(){ //(SIN PAGINAR / SIN FOTOS) No en uso
            $sql = "SELECT id, tipo, zona, direccion, ndormitorios, precio, tamano 
            FROM viviendas
            ORDER BY fecha_anuncio DESC";
            $stmt = $this->conexion->conn->prepare($sql);
            $stmt->execute();
            $registros = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $registros;
        }
    
        function mostrarViviendasconFotos(){ // No en uso
            $sql = "SELECT id, tipo, zona, direccion, ndormitorios, precio, 
            tamano from viviendas
            INNER JOIN fotos
            ON viviendas.id = fotos.id_vivienda
            GROUP BY viviendas.id";
            $stmt = $this->conexion->conn->prepare($sql);
            $stmt->execute();
            $registros = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $registros;
        }
    
    //************************* CRUD ****************************
   // *************************** INSERTAR (LA FOTO SE INSERTA EN OTRA FUNCION) *************************
    public function insertarVivienda($tipo, $zona, $direccion,$ndormitorios, $precio, $tamano, $extras,$observaciones) {
        $sql = "INSERT INTO viviendas (tipo, zona, direccion, ndormitorios, precio, tamano, extras, observaciones) 
        VALUES (:tipo, :zona, :direccion, :ndormitorios, :precio, :tamano, :extras, :observaciones)";
        $stmt = $this->conexion->conn->prepare($sql);
        $stmt->bindParam("tipo", $tipo);
        $stmt->bindParam("zona", $zona);
        $stmt->bindParam("direccion", $direccion);
        $stmt->bindParam("ndormitorios", $ndormitorios);
        $stmt->bindParam("precio", $precio);
        $stmt->bindParam("tamano", $tamano);
        // $stmt->bindValue("extras", implode(",",$extras));
        $extras ="";
        if(isset($_POST["extras"])){
            for($i =0; $i < count($_POST["extras"]);$i++){
                $extras=$extras.$_POST["extras"][$i].',';
            }
        }
        // echo ($extras);
        $stmt->bindParam("extras",$extras);
        $stmt->bindParam("observaciones", $observaciones);
        $stmt->execute();
        return $this->conexion->conn->lastInsertId(); // Metodo PHP para recuperar el ultimo id
    }

    // ***************************** INSERTAR FOTO *************************
    // Como necesitamos insertar la vivienda primero, cogeremos el ultimo id insertado y a ese id le asignaremos en el controlador la funcion de insertar la foto
    public function insertarFoto($lastId, $nombreFichero) {
        $sql = "INSERT INTO fotos (id_vivienda, foto) VALUES (:id_vivienda, :foto)";
        $stmt = $this->conexion->conn->prepare($sql);
        $stmt->bindParam("id_vivienda", $lastId);
        $stmt->bindParam("foto", $nombreFichero);
        $stmt->execute();
    }

    // ***************************** BUSCAR VIVIENDA (FILTRADO VIVIENDAS) *************************
    function buscarVivienda(){ // FILTRADO
        /*Usamos LIKE para COMPARAR*/
        $sql = "SELECT * FROM viviendas 
            WHERE tipo LIKE :tipo 
            AND zona LIKE :zona 
            AND ndormitorios LIKE :dormitorios 
            AND extras LIKE :extras 
            AND precio BETWEEN :precio1 AND :precio2 ";
        $stmt = $this->conexion->conn->prepare($sql);

        if(isset($_POST['tipo'])){
            $stmt->bindParam(":tipo",$_POST['tipo']);
        }else{
            // bindValue lo utilizo para que coja exactamente "%" de valor en la SQL
            // Es un comodín utilizado en las cláusulas "LIKE" de SQL para hacer coincidir cualquier secuencia de caracteres. 
            // Por lo tanto, este código permite que la consulta SQL seleccione todas las viviendas cuyo tipo coincida con cualquier valor.
            $stmt->bindValue(':tipo', "%");
        }

        if(isset($_POST['zona'])){
            $stmt->bindParam(":zona",$_POST['zona']);
        }else{
            //bindValue lo utilizo para que coja exactamente "%" de valor en la SQL
            $stmt->bindValue(':zona', "%");
        }

        if(isset($_POST['ndormitorios'])){
            $stmt->bindParam(":dormitorios",$_POST['ndormitorios']);
        }else{
            $stmt->bindValue(':dormitorios', "%");
        }

        if (isset($_POST['extras'])) {
            $array= implode(",",$_POST['extras']);
            $stmt->bindValue(':extras', $array);
        } else {
            $stmt->bindValue(':extras', "%");
        }
    
        if(isset($_POST['precio'])){
            if($_POST['precio']==1){
                $stmt->bindValue(":precio1",0);
                $stmt->bindValue(":precio2",100000);
            }else if($_POST['precio']==2){
                $stmt->bindValue(":precio1",100000);
                $stmt->bindValue(":precio2",200000);
            }else if($_POST['precio']==3){
                $stmt->bindValue(":precio1",200000);
                $stmt->bindValue(":precio2",300000);
            }else if($_POST['precio']==4){
                $stmt->bindValue(":precio1",300000);
                $stmt->bindValue(":precio2",400000);
            }
            else{
                $stmt->bindValue(":precio1",400000);
                $stmt->bindValue(":precio2",9999999999);
            } 
        }else{
            $stmt->bindValue(":precio1", 0);
            $stmt->bindValue(":precio2", 9999999999);
        }
        $stmt->execute();
        $registros = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $registros;
    }

    // ***************************** BUSCAR VIVIENDA POR ID PARA PONER LOS VALUES E INDICAR QUE VIVIENDA ESTÁS MODIFICANDO *************************
    function buscarViviendaPorId($id) {
        $sql = "SELECT * FROM viviendas WHERE id = :id";
        $stmt = $this->conexion->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $registro = $stmt->fetch(PDO::FETCH_OBJ);
        return $registro; // Solo quiero un registro para identificar los valores concretos de la vivienda seleccionada
    }

    // ************************* MODIFICAR VIVIENDA *************************
    function modificarVivienda($id,$tipo, $zona, $direccion,$ndormitorios, $precio, $tamano, $extras, $observaciones){
        $sql= "UPDATE viviendas SET 
        tipo=:tipo,
        zona=:zona,
        direccion=:direccion,
        ndormitorios=:ndormitorios,
        precio=:precio,
        tamano=:tamano,
        extras=:extras,
        observaciones=:observaciones WHERE id=:id";
        $stmt=$this->conexion->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':zona', $zona);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':ndormitorios', $ndormitorios);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':tamano', $tamano);
        // $stmt->bindParam(':extras', $extras);
        $extras ="";
        if(isset($_POST["extras"])){
            for($i =0; $i < count($_POST["extras"]);$i++){
                $extras=$extras.$_POST["extras"][$i].',';
            }
        }
        // echo ($extras);
        $stmt->bindParam("extras",$extras);
        $stmt->bindParam(':observaciones', $observaciones);
        return $stmt->execute();
        }

   // ***************************** BORRAR VIVIENDA *************************
    function borrarVivienda($id){
        $sql = "DELETE FROM viviendas WHERE id=:id";
        $stmt = $this->conexion->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}

//PRUEBAS
// $viviendas = new modeloVivienda();
// echo "hola jorge";
// print_r($viviendas->buscarVivienda());
// echo "hola jorge";
// print_r($registros);

?>