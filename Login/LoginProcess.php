<?php
    session_start();
    include "../Connection/connection.php";

    $user = $_POST['username'];
    $pass = $_POST['pass'];
    $sql = "SELECT * from tabel_admin WHERE username = '$user'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);


    $dbLevel = $row['level'];
    $dbUser = $row['username'];
    $dbPass = $row['password'];


    $sqlView = "SELECT * from view_pelanggan WHERE username = '$user'";
    $resultView = mysqli_query($conn, $sqlView);
    $rowView = mysqli_fetch_assoc($resultView);

    $kodePelanggan = $rowView['kode_pelanggan'];

    
    if($user == $dbUser && $pass == $dbPass){
        $_SESSION['user'] = $dbUser;
        $_SESSION['level'] = $dbLevel;
        $_SESSION['kode_pelanggan'] = $kodePelanggan;

        if($dbLevel == "admin" || $dbLevel == "manager"){
            header("Location: ../AMP/Dashboard/dashboard.php");
        } else if ($dbLevel == "user"){
            header("Location: ../User/dashboardPelanggan.php");
        } else if ($dbLevel == "pemasok"){
            header("Location: ../AMP/Request/pemasok/process.php");
        }
        exit();
    }
    else {
        echo "<script type='text/javascript'>
                alert('Username atau Password anda salah atau Akun anda sudah tidak aktif');
                window.location='FormLogin.php';
            </script>";
    }
?>