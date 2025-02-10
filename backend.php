<?php
    require_once 'config.php';

    class Student{
        
        private $db;

        public function __construct(){
            $database = new Database();
            $this->db = $database->getConnection();
        }

        //Function for add a new students
        public function addStudent($name, $birth, $gender, $resume){

            $birthDate = new DateTime($birth);
            $today = new DateTime();
            $age = $today->diff($birthDate)->y;

            $uploadDir = 'uploads/';
            if(!is_dir($uploadDir)){
                mkdir($uploadDir, 0777, true);
            }

            $fileName = uniqid() . '_' . basename($resume['name']);
            $filePath = $uploadDir . $fileName;

            if(move_uploaded_file($resume['tmp_name'], $filePath)){
                $sql = "INSERT INTO student (nam, birth, age, gender, resume) VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("ssiss", $name, $birth, $age, $gender, $filePath);

                if($stmt->execute()){
                    return "Student added Successfully";
                }else{
                    return "Error Adding Student" . $stmt->error;
                }
            }else{
                return "Error Uploading resume";
            }
        }

        //Function for Fetch all students
        public function fetchStudents(){

            $sql = "SELECT * FROM student";

            
        }

        //Function for fetch a single student by ID

        //Function for update student

        //Function for delete student
    }
?>