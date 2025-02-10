<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Mgt</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<style>
    body{
        font-family: "Poppins", serif;
        font-weight: 500;
        font-style: normal;
        background-color:rgb(242, 242, 242);
    }
    h1{
        text-align: center;
    }
    #studentForm{
       /* display: none; */
        max-width: 500px;
        padding: 1rem;
        border-radius: 1rem;
        margin: 1.5rem auto 0 auto;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        
    }
    #studentForm input{
        width: 450px;
        padding: 0.5rem;
        border-radius: 0.5rem;
        border: 1px solid slategray;
        margin: 0 auto;
    }
    #studentForm select{
       width: 470px;
       padding: 0.5rem;
       border-radius: 0.5rem;
       border: 1px solid slategray;
       margin: 0 auto;
   }
    #studentForm button{
        width : 470px;
        color: white;
        font-weight: 400;
        padding: 0.5rem;
        background-color: lime;
        border: 1px solid lime;
        border-radius:  0.5rem;
    }
    /*Update form*/
    #studentUpdateForm{
       display: none;
       max-width: 500px;
       padding: 1rem;
       border-radius: 1rem;
       margin: 1.5rem auto 0 auto;
       box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
       
   }
   #studentUpdateForm input{
       width: 450px;
       padding: 0.5rem;
       border-radius: 0.5rem;
       border: 1px solid slategray;
       margin: 0 auto;
   }
   #studentUpdateForm select{
       width: 470px;
       padding: 0.5rem;
       border-radius: 0.5rem;
       border: 1px solid slategray;
       margin: 0 auto;
   }
   
   #studentUpdateForm button{
       width : 470px;
       color: white;
       font-weight: 400;
       padding: 0.5rem;
       background-color: lime;
       border: 1px solid lime;
       border-radius:  0.5rem;
   }
</style>
</head>
<body>
    <h1>Student Management System</h1>
    <hr>
    <!--Add student modal-->
    <form id="studentForm" enctype="multipart/form-data">
        <label for="name">Name :</label><br>
        <input type="text" name="name" id="name" required><br><br>

        <label for="birth" >Birth Date</label><br>
        <input type="date" name="birth" id="birth" required><br><br>

        <label for="gender">Gender:</label><br>
        <select id="gender" name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select><br><br>

        <label for="resume">Resume</label><br>
        <input type="file" name="resume" id="resume" accept=".pdf" required><br><br>

        <button type="submit">Add Student</button>

    </form>

    <!--Update student modal-->
    <form id="studentUpdateForm" enctype="multipart/form-data">
    <input type="hidden" id="updateId" name="id">
        <label for="updateName">Name :</label><br>
        <input type="text" name="name" id="updateName" required><br><br>

        <label for="updateBirth" >Birth Date</label><br>
        <input type="date" name="birth" id="updateBirth" required><br><br>

        <label for="updateGender">Gender:</label><br>
        <select id="updateGender" name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select><br><br>

        <label for="resume">Resume</label><br>
        <input type="file" name="resume" id="resume" accept=".pdf" ><br><br>

        <button type="submit">Update Student</button>
        <button id="close" style="background-color: yellow; margin-top: 1rem ;border: none" >Close The Form</button>

    </form>

    <!--Data Table-->
   <table id="studentTable" class="display" style="width: 100%; margin-top: 5rem;">
    <thead>
        <th>Student ID</th>
        <th>Name</th>
        <th>Birth Date</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Resume</th>
        <th>Action</th>
    </thead>
    <tbody>
        <!--Data will be loaded here via AJAX-->
    </tbody>
   </table>
   <script>
    $(document).ready(function(){
        //Initialize the data table
        var dataTable = $('#studentTable').DataTable({
            ajax: {
                url:'backend.php?action=fetch',
                dataSrc: ''
            
            },
            columns: [
                {data : 'id'},
                {data: 'name'},
                {data : 'birth'},
                {data : 'age'},
                {data: 'gender'},
                {data : 'resume',
                    render: function(data, type, row){
                        if(data){
                            return `<a href="${data}" target = "_blank"> View Resume </a>`
                        }else{
                            return "No resume uploaded";
                        }
                    }
                },
                {data : null,
                    render:  function(data, type, row){
                        //create Delete and update 
                        return `<button class="btn-delete" data-id="${data.id}">Remove Student</button> 
                        <button class="btn-update" data-id="${data.id}">Update Student</button> `
                    }
                }
            ]
        });

        //Handle Data Submission
        $('#studentForm').on('submit', function(e){
            e.preventDefault();
            console.log("Student adding function executed");
            

            //create form data object
            let formData = new FormData(this);
            $.ajax ({
                url : 'backend.php?action=add',
                method : 'POST',
                data : formData,
                processData: false,
                contentType: false,
                success : function(response){ 
                    alert(response);
                    dataTable.ajax.reload();
                    $('#studentForm')[0].reset();
                    
                }

            })
        })

        //Update Handle by getting data from the database to the updated form
        $('#studentTable').on('click', '.btn-update', function(e){

            e.preventDefault();

            //Get the student Id from the button
            let studentID = $(this).data('id');

            $.ajax ({
                url : 'backend.php?action=fetchById',
                method : 'GET',
                data : {id : studentID},
                success: function(response){
                    let student = JSON.parse(response);
                    $('#updateId').val(student.id);
                    $('#updateName').val(student.name);
                    $('#updateBirth').val(student.birth);
                    $('#updateGender').val(student.gender);
                    // $('#updateResume').val(response.resume);
                    $('#studentForm').hide();
                    $('#studentUpdateForm').show();
                }
            })
        })
        
        //Update handler to send data to the database 
        $('#studentUpdateForm').on('submit', function(e){

            e.preventDefault();

            //create form data object to get data from the form
            let formData = new FormData(this);
            console.log(formData.id);
            

            $.ajax({
                url : 'backend.php?action=update',
                method: 'POST',
                data : formData,
                processData: false,
                contentType: false,
                success: function(response){
                    alert(response);
                    dataTable.ajax.reload();
                    $('#studentForm').show();
                    $('#studentUpdateForm').hide();

                },
                
                
            })
        })

        //Delete handle
        $('#studentTable').on('click', '.btn-delete', function(){

            //Get the student Id
            let studentId = $(this).data('id');
            console.log(studentId);
            

            if(confirm("Are ypu sure you want to delete thi student")){
                $.ajax({
                url: 'backend.php?action=delete',
                method: 'POST',
                data : {id : studentId},
                success: function(response){
                    alert(response);
                    dataTable.ajax.reload();
                },
            })
            }
        })
    })
    </script>
</body>
</html>