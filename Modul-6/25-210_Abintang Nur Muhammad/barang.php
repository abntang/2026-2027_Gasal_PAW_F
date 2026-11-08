<?php
include "koneksi.php";

$pesan = "";
$kelas = "success";

if (isset($_POST['hapus'])) {
    $id = (int)$_POST['id'];

    // cek dulu apakah barang sudah dipakai di transaksi_detail
    $cek = mysqli_query($conn, "SELECT * FROM transaksi_detail WHERE barang_id = $id");

    if (mysqli_num_rows($cek) > 0) {
        $pesan = "Barang tidak bisa dihapus karena masih digunakan pada transaksi";
        $kelas = "error";
    } else {
        mysqli_query($conn, "DELETE FROM barang WHERE id = $id");
        $pesan = "Barang berhasil dihapus";
    }
}

$barang = mysqli_query($conn, "SELECT barang.*, supplier.nama AS supplier FROM barang
    JOIN supplier ON supplier.id = barang.supplier_id
    ORDER BY barang.id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav><a href="index.php">Transaksi</a> <a href="barang.php">Barang</a></nav>
<div class="container">

<?php if ($pesan != "") { ?>
    <div class="alert <?= $kelas ?>"><?= $pesan ?></div>
<?php } ?>

<div class="card">
    <h1>Data Barang</h1>
    <table>
        <tr>
            <th>ID</th><th>Kode</th><th>Nama Barang</th><th>Supplier</th><th>Harga</th><th>Stok</th><th>Aksi</th>
        </tr>
        <?php while ($b = mysqli_fetch_assoc($barang)) { ?>
        <tr>
            <td><?= $b['id'] ?></td>
            <td><?= $b['kode_barang'] ?></td>
            <td><?= htmlspecialchars($b['nama_barang']) ?></td>
            <td><?= htmlspecialchars($b['supplier']) ?></td>
            <td><?= rupiah($b['harga']) ?></td>
            <td><?= $b['stok'] ?></td>
            <td>
                <form method="post" onsubmit="return confirm('Yakin hapus barang ini?')">
                    <input type="hidden" name="id" value="<?= $b['id'] ?>">
                    <button class="btn danger small" type="submit" name="hapus">Hapus</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</div>
</body>
</html>
