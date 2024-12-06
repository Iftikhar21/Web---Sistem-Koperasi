<?php
    session_start();

    if(isset($_SESSION['user'])){
        header("location:../Dashboard/dashboard.php");
        exit();
    } 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: gray;
        height: 100vh;
        font-family: Poppins;
    }
    .login-container {
        width: 400px;
        padding: 50px;
        background-color: #fff;
        border-radius: 40px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    .login-container h2 {
        text-transform: uppercase;
        letter-spacing: 10px;
        text-align: center;
        font-weight: bold;
        margin-bottom: 30px;
    }
    .login-container .form-control {
      border-radius: 3px;
    }
    .login-container .btn-primary {
      width: 100%;
    }
    .show-pass input[type="checkbox"] {
      margin-right: 10px;
    }
    .register {
        text-align: center;
    }
    .register a {
        text-decoration: none;
    }
    .register a:hover {
        text-decoration: underline;
    }
  </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="LoginProcess.php" method="post">
        <div class="mb-3">
            <input type="name" class="form-control" name="username" aria-describedby="emailHelp" placeholder="Enter Username">
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" id="password" name="pass" placeholder="Enter password">
        </div>
        <div class="show-pass mb-3">
            <input class="form-check-input" type="checkbox" onclick="showPasswd()">Show Password<br>
        </div>
        <div class="register mb-3">
            <a href="FormRegister.php">Doesn't have any account? Register Now</a>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" name="submit" class="btn btn-primary">Log in</button>
        </div>
        </form>
    </div>
    <script>
            function showPasswd(){
        var x = document.getElementById("password")
        if(x.type === "password"){
            x.type ="text";
        }
        else{
            x.type ="password";
        }
        }
        </script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>