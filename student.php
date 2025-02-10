<?php
    require_once 'config.php';
    // header('Content-Type: application/json');

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
                $sql = "INSERT INTO student (name, birth, age, gender, resume) VALUES (?, ?, ?, ?, ?)";
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
            $sql = "SELECT * FROM student WHERE id = ?";
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
        public function updateStudent($id, $name, $birth, $gender, $resume){

            $birthDate = new Datetime($birth);
            $today = new DateTime();
            $age = $today->diff($birthDate)->y;

            $filePath = null;
            if($resume['error'] === UPLOAD_ERR_OK){
                $uploadDir = 'uploads/';
                if(!is_dir($uploadDir)){
                    mkdir($uploadDir, 0777, true); //making the directory if not exist and give read and write permission
                }

                $fileName = uniqid() . '_' . basename($resume['name']);
                $filePath = $uploadDir . $fileName;

                if(!move_uploaded_file($resume['tmp_name'], $filePath)){
                    return "Error Uploading Resume";
                }
            }
            if($filePath){
                $sql = "UPDATE student SET name = ?, birth = ?, age =?, gender = ?, resume = ? WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("ssissi", $name, $birth, $age, $gender, $filePath, $id);
            }else{
                $sql = "UPDATE student SET name = ?, birth = ?, age = ?, gender = ? WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("ssisi", $name, $birth, $age, $gender, $id);   
            }
            if ($stmt->execute()){
                return "Student Updated Successfully";
            }else{
                return "Error updating student" . $stmt->error;
            }

        }

        //Function for delete student
        public function deleteStudent($id){
            $sql = "DELETE FROM student WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $id);

            if($stmt->execute()){
                return "Student deleted successfully";
            }else{
                return "Error deleting student : " . $stmt->error;
            }
        }
    }
?>