<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "penjualan";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

function rupiah($angka) {
    return "Rp " . number_format($angka, 0, ",", ".");
}

// hitung ulang total transaksi dari semua detailnya
function hitung_total($conn, $transaksi_id) {
    $sql = "UPDATE transaksi SET total = (SELECT IFNULL(SUM(harga * qty), 0)
            FROM transaksi_detail WHERE transaksi_id = $transaksi_id)
            WHERE id = $transaksi_id";
    mysqli_query($conn, $sql);
}
?>
