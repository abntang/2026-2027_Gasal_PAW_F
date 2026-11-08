<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    $tanggal = $_POST['tanggal'];
    $pelanggan_id = $_POST['pelanggan_id'];
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    mysqli_query($conn, "INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id)
        VALUES ('$tanggal', '$keterangan', 0, '$pelanggan_id')");
    $id_baru = mysqli_insert_id($conn);
    header("Location: detail.php?id=$id_baru");
    exit;
}

$transaksi = mysqli_query($conn, "SELECT transaksi.*, pelanggan.nama FROM transaksi
    JOIN pelanggan ON pelanggan.id = transaksi.pelanggan_id
    ORDER BY transaksi.id DESC");
$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY nama");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Transaksi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav><a href="index.php">Transaksi</a> <a href="barang.php">Barang</a></nav>
<div class="container">

<div class="card">
    <h1>Data Transaksi</h1>
    <table>
        <tr>
            <th>ID</th><th>Tanggal</th><th>Pelanggan</th><th>Keterangan</th><th>Total</th><th>Aksi</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($transaksi)) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['waktu_transaksi'] ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['keterangan']) ?></td>
            <td><?= rupiah($row['total']) ?></td>
            <td><a class="btn small" href="detail.php?id=<?= $row['id'] ?>">Detail</a></td>
        </tr>
        <?php } ?>
    </table>
</div>

<div class="card">
    <h2>Tambah Transaksi</h2>
    <form method="post">
        <label>Tanggal</label>
        <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required>
        <label>Pelanggan</label>
        <select name="pelanggan_id" required>
            <?php while ($p = mysqli_fetch_assoc($pelanggan)) { ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nama']) ?></option>
            <?php } ?>
        </select>
        <label>Keterangan</label>
        <input type="text" name="keterangan">
        <p><button class="btn" type="submit" name="simpan">Simpan</button></p>
    </form>
</div>

</div>
</body>
</html>
