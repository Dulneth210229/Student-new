<?php

    class Database{
        private $server = 'localhost';
        private $user = 'root';
        private $password = '';
        private $name = 'student';
        private $port = 3308;
        private $conn;

        public function __construct()
        {
            try{
                $this->conn = new mysqli($this->server, $this->user, $this->password, $this->name, $this->port);
              
                if($this->conn->connect_error){
                    throw new Exception("Database connection failed: " . $this->conn->connect_error);
                }


            }catch(Exception $e){
                die($e->getMessage());
            }
            
        }
        public function getConnection(){
            return $this->conn;
        }
    

    }

?>