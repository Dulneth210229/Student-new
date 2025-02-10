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
            $result = $this->db->query($sql);

            if($result){
                $student = $result->fetch_all(MYSQLI_ASSOC);
                return json_encode($student);
            }else{
                return json_encode(["Error" => "Error Fetching Students"]);
            }


        }

         //Function for fetch a single student by ID
        public function fetchStudentById($id){
            $sql = "SELECT * FROM students WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                return json_encode($result->fetch_assoc());
            }else{
                return json_encode(["error" => "Student not found"]);
            }
        }

        //Function for update student

        //Function for delete student
    }
?>