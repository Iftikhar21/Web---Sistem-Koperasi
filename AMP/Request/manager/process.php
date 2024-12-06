<?php
session_start();
include "../../../Connection/connection.php";

if (!isset($_SESSION['user']) || $_SESSION['level'] == "user" || $_SESSION['level'] == "admin" || $_SESSION['level'] == "pemasok") {
    header("Location: ../../../User/dashboardPelanggan.php");
    exit();
}

if($_SESSION['user'] == ""){
    header("location: ../../../Login/FormLogin.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form dengan key 'jumlah_po' yang akan menjadi array
    $jumlah_po_array = $_POST['jumlah_po']; 

    // Cek stok barang di tabel_barang yang jumlahnya < 5
    $queryBarangMenipis = "SELECT * FROM tabel_barang WHERE jumlah < 5";
    $resultBarangMenipis = mysqli_query($conn, $queryBarangMenipis);

    while ($row = mysqli_fetch_assoc($resultBarangMenipis)) {
        $kode_barang = $row['kode_barang'];
        $nama_barang = $row['nama_barang'];
        $request_by = $_SESSION['user'];

        // Cek apakah ada input untuk kode barang ini
        if (isset($jumlah_po_array[$kode_barang])) {
            $jumlah_po = intval($jumlah_po_array[$kode_barang]);

            // Cek apakah permintaan sudah ada
            $queryCheck = "SELECT * FROM transaksi_koperasi WHERE kode_barang = '$kode_barang' AND status = 'Pending'";
            $resultCheck = mysqli_query($conn, $queryCheck);

            if (mysqli_num_rows($resultCheck) == 0) {
                // Tambahkan data ke tabel transaksi_koperasi
                $queryInsert = "INSERT INTO transaksi_koperasi
                                VALUES ('', '$kode_barang', '$nama_barang', '$jumlah_po', '$request_by', 'Pending', '')";
                mysqli_query($conn, $queryInsert);

                // // Kurangi stok di tabel_barang
                // $queryUpdateStok = "UPDATE tabel_barang SET jumlah = jumlah - $jumlah_po WHERE kode_barang = '$kode_barang'";
                // mysqli_query($conn, $queryUpdateStok);
            }
        }
    }

    // Redirect kembali ke dashboard dengan pesan
    header("Location: ../../Dashboard/dashboard.php?pesan=Permintaan berhasil dikirim");
}
?>
