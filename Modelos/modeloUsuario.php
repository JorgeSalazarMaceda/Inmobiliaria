<?php

require_once __DIR__."/conexion.php";

class modeloUsuario{

    private $conexion;

    function __construct()
    {
        $this->conexion=new Conexion();
    }

    // **************************** UPDATE ADMIN PARA EVITAR PROBLEMAS (1234) *******************************
    function admin(){
        $password = "admin";
        $contrasenaAdmin = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET password = :password WHERE id_usuario = 'admin'";
        $stmt = $this->conexion->conn->prepare($sql);
        $stmt->bindParam(":password", $contrasenaAdmin);
        $stmt->execute();
    }

    // **************************** MOSTRAR USUARIO *******************************
    function mostrarUsuarios(){
        $sql = "SELECT * FROM usuarios WHERE id_usuario NOT IN ('admin')"; // Controlamos así que no se puede borrar el admin a si mismo
        $stmt = $this->conexion->conn->prepare($sql);
        $stmt->execute();
        $registros = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $registros;
    }

    // **************************** COMPROBAR USUARIO *******************************
    function comprobar_login($usuario){
        $sql = "SELECT id_usuario, password FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->conexion->conn->prepare($sql);
        $stmt->bindParam(1, $usuario);
        $stmt->execute();
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $registros;
    }
    // **************************** COMPROBAR LOGIN USANDO LA BD (NO ESTA EN USO, PERO ES OTRA OPCION PARA GUARDAR ULTIMA CONEXION) *******************************
    // function comprobar_login($usuario){
    //     $sql = "SELECT id_usuario, password, last_conection FROM usuarios WHERE id_usuario = ?";
    //     $stmt = $this->conexion->conn->prepare($sql);
    //     $stmt->bindParam(1, $usuario);
    //     $stmt->execute();
    //     $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     return $registros;
    // }
    
    // **************************** GUARDAR CONEXION EN BD (NO ESTA EN USO, PERO ES OTRA OPCION PARA GUARDAR ULTIMA CONEXION) *******************************
    // function guardarUltimaConexion($usuario) { //Guardamos la ultima conexion en la tabla usuarios campo last_conection y lo actualizamos
    //     $sql = "UPDATE usuarios SET last_conection = :fecha where id_usuario = :usuario";
    //     $stmt = $this->conexion->conn->prepare($sql);
    //     $stmt->bindValue(":fecha", date('Y-m-d H:i:s'));
    //     $stmt->bindValue(":usuario", $usuario);
    //     $stmt->execute();
    // }

    // **************************** INSERTAR USUARIO *******************************
    function insertarUsuario($usuario, $password) {
        $sql = "INSERT INTO usuarios (id_usuario, password) VALUES (?,?)";
        $stmt = $this->conexion->conn->prepare($sql);
        $stmt->bindParam(1, $usuario);
        $stmt->bindParam(2, $password);
        $stmt->execute();
    }

    // **************************** BORRAR USUARIO *******************************
    function borrarUsuario($id){
        $sql = "DELETE FROM usuarios WHERE id_usuario=:id_usuario";
        $stmt = $this->conexion->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $id);
        $stmt->execute();
      }
}

// Pruebas
// $usuarios = new modeloUsuario();
// print_r($usuarios->comprobarAdmin());

?>