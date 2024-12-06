<?php
    include "../Connection/connection.php";
    
    if(isset($_POST['username'])){
        $username = $_POST['username'];
        $password = $_POST['pass'];
        $alamat = $_POST['address'];
        $noTelp = $_POST['number'];
    
        $insertAdmin = "INSERT INTO tabel_admin VALUES ('', '$username', '$password', 'user');";
        $insertQueryAdmin = mysqli_query($conn, $insertAdmin);
    
        $insertPelanggan = "INSERT INTO tabel_pelanggan VALUES ('', '$username', '$alamat', '$noTelp');";
        $insertQueryPelanggan = mysqli_query($conn, $insertPelanggan);
    
        if (!$insertQueryAdmin && $insertQueryPelanggan) {
            echo "<script>
                alert('Gagal menyimpan register: " . mysqli_error($conn) . "');
                window.history.back();
            </script>";
            exit;
        } else {
            header("Location: FormLogin.php");
        }
    }
?>