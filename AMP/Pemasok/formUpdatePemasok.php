<?php
    session_start();
    include "../../Connection/connection.php";

    if (!isset($_SESSION['user']) || $_SESSION['level'] == "user" || $_SESSION['level'] == "manager") {
        header("Location: ../../User/dashboardPelanggan.php");
        exit();
    }
    
    if($_SESSION['user'] == ""){
        header("location: ../../Login/FormLogin.php");
    }
    $kodePemasok = $_GET['kode_pemasok'];
    $sql = "SELECT * FROM tabel_pemasok WHERE kode_pemasok = '$kodePemasok'";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($result);


    if(isset($_POST['kode_pemasok'])){
        $kodePemasok = $_POST['kode_pemasok'];
        $namaPemasok = $_POST['nama_pemasok'];
        $alamatPemasok = $_POST['alamat_pemasok'];
        $noTelp = $_POST['no_telp'];
        $email = $_POST['email'];
    
        $updateSql = "UPDATE tabel_pemasok SET nama_pemasok = '$namaPemasok', alamat = '$alamatPemasok', 
                            no_telp = '$noTelp', email = '$email' WHERE kode_pemasok = $kodePemasok";
        $insertQuery = mysqli_query($conn, $updateSql);
    
        if($insertQuery) {
            header("Location: ../Dashboard/tabelPemasok.php");
        } else {
            "gagal";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Form Update Mapel</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        body {
            font-family: Poppins;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        h2 {
            text-align: center;
        }
        h4 {
            text-align: center;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 50px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Form Update Pelanggan</h2>
        <h4>Kode Pelanggan : <?php echo "$data[kode_pemasok]"?></h4>
        <form action="" method="post">
            <div class="row mb-2">
                <div class="col">
                    <input class="form-control" type="hidden" id="kode_pemasok" name="kode_pemasok" value="<?php echo "$data[kode_pemasok]"?>">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="nama_pemasok" class="col-form-label">Nama Pelanggan</label>
                </div>
                <div class="col">
                    <input class="form-control" type="text" id="nama_pemasok" name="nama_pemasok" value="<?php echo "$data[nama_pemasok]"?>" required>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="alamat_pemasok" class="col-form-label">Alamat Pemasok</label>
                </div>
                <div class="col">
                    <input class="form-control" type="text" id="alamat_pemasok" name="alamat_pemasok" value="<?php echo "$data[alamat]"?>" required>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="no_telp" class="col-form-label">No Telpon Pemasok</label>
                </div>
                <div class="col">
                    <input class="form-control" type="text" id="no_telp" name="no_telp" value="<?php echo "$data[no_telp]"?>" required>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-4">
                    <label for="email" class="col-form-label">Email Pemasok</label>
                </div>
                <div class="col">
                    <input class="form-control" type="text" id="email" name="email" value="<?php echo "$data[email]"?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-auto mx-auto mt-3">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
