<?php
    session_start();
    // if($_SESSION['level'] == "user") return header("Location:../ReadMhs/tableMahasiswa.php");

    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    include "../../../Connection/connection.php";
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    if (!isset($_SESSION['user']) || $_SESSION['level'] == "user" || $_SESSION['level'] == "manager") {
        header("Location: ../../../User/dashboardPelanggan.php");
        exit();
    }
    
    if($_SESSION['user'] == ""){
        header("location: ../../../Login/FormLogin.php");
    }

    if(isset($_POST['simpan'])){
        if(isset($_POST['kodeBarang'])){
            $kodeBarang = $_POST['kodeBarang'];
            $namaBarang = $_POST['namaBarang'];
            $jenisBarang = $_POST['jenisBarang'];
            $satuanBarang = $_POST['satuanBarang'];
            $hargaBeli = $_POST['hargaBeli'];
            $hargaJual = $_POST['hargaJual'];
        
            $insertBarangSql = "INSERT INTO tabel_barang 
                (kode_barang, nama_barang, jenis_barang, satuan, harga_beli, harga_jual, jumlah)
                VALUES ('$kodeBarang', '$namaBarang', '$jenisBarang', '$satuanBarang', '$hargaBeli', '$hargaJual', '0')
                ON DUPLICATE KEY UPDATE 
                nama_barang = '$namaBarang',
                jenis_barang = '$jenisBarang',
                satuan = '$satuanBarang',
                harga_beli = '$hargaBeli',
                harga_jual = '$hargaJual';";
        
            if (mysqli_query($conn, $insertBarangSql)) {
                header("Location: ../tabelBarang.php");
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    } else if (isset($_POST['delete'])) {
        // Logika untuk Delete
        $kodeBarang = $_POST['kodeBarang'];
    
        $deleteBarangSql = "DELETE FROM tabel_barang WHERE kode_barang = '$kodeBarang'";
    
        if (mysqli_query($conn, $deleteBarangSql)) {
            // Redirect ke halaman tabelBarang jika berhasil
            header("Location: ../tabelBarang.php");
        } else {
            // Tampilkan error jika query gagal
            echo "Error: " . mysqli_error($conn);
        }
    }
?>