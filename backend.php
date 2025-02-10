<?php
    require_once 'student.php';

    $student = new Student();

    if(isset($_GET['action'])){

        switch ($_GET['action']){
            case 'add':
                echo $student->addStudent($_POST['name'], $_POST['birth'], $_POST['gender'], $_FILES['resume']);
                break;
            case 'fetch':
                echo $student->fetchStudents();
                break;
            case 'fetchById':
                echo $student->fetchStudentById($_POST['id']);
                break;
            case 'update': 
                echo $student->updateStudent($_POST['id'], $_POST['name'], $_POST['birth'], $_POST['gender'], $_FILES['resume']);
                break;
            case 'delete':
                echo $student->deleteStudent($_POST['id']);
                break;
            default: 
                echo "Invalid Action";
        }
    }
?>