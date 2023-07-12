<?php

    class Conexion{
        private $servername = "localhost";
        private $username = "root";
        private $password = "";
        private $db = "inmobiliaria";
        public $conn; //Atributo conexión

        function __construct(){
            try {
                $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->db", $this->username, $this->password);
                $this->conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOExeption $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
    }

  