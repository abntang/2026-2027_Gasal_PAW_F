<?php
include "koneksi.php";

$id = (int)$_GET['id'];
$pesan = "";
$kelas = "success";

// simpan detail baru
if (isset($_POST['simpan'])) {
    $barang_id = (int)$_POST['barang_id'];
    $qty = (int)$_POST['qty'];

    $cek = mysqli_query($conn, "SELECT * FROM transaksi_detail
        WHERE transaksi_id = $id AND barang_id = $barang_id");
    $barang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT harga FROM barang WHERE id = $barang_id"));

    if (!$barang || $qty < 1) {
        $pesan = "Pilih barang dan isi qty minimal 1";
        $kelas = "error";
    } elseif (mysqli_num_rows($cek) > 0) {
        $pesan = "Barang ini sudah ada di transaksi";
        $kelas = "error";
    } else {
        $harga = $barang['harga'];
        mysqli_query($conn, "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty)
            VALUES ($id, $barang_id, $harga, $qty)");
        hitung_total($conn, $id);
        $pesan = "Detail berhasil disimpan, total transaksi diperbarui";
    }
}

// hapus detail
if (isset($_POST['hapus'])) {
    $barang_id = (int)$_POST['barang_id'];
    mysqli_query($conn, "DELETE FROM transaksi_detail WHERE transaksi_id = $id AND barang_id = $barang_id");
    hitung_total($conn, $id);
    $pesan = "Detail berhasil dihapus, total transaksi diperbarui";
}

// data master
$master = mysqli_fetch_assoc(mysqli_query($conn, "SELECT transaksi.*, pelanggan.nama FROM transaksi
    JOIN pelanggan ON pelanggan.id = transaksi.pelanggan_id
    WHERE transaksi.id = $id"));
if (!$master) {
    die("Transaksi tidak ditemukan");
}

// data detail
$detail = mysqli_query($conn, "SELECT transaksi_detail.*, barang.kode_barang, barang.nama_barang
    FROM transaksi_detail
    JOIN barang ON barang.id = transaksi_detail.barang_id
    WHERE transaksi_detail.transaksi_id = $id");

// dropdown: hanya barang yang belum ada di detail transaksi ini
$pilihan = mysqli_query($conn, "SELECT * FROM barang
    WHERE id NOT IN (SELECT barang_id FROM transaksi_detail WHERE transaksi_id = $id)
    ORDER BY nama_barang");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Transaksi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav><a href="index.php">Transaksi</a> <a href="barang.php">Barang</a></nav>
<div class="container">

<?php if ($pesan != "") { ?>
    <div class="alert <?= $kelas ?>"><?= $pesan ?></div>
<?php } ?>

<div class="card">
    <h1>Transaksi #<?= $master['id'] ?></h1>
    <p>Tanggal: <?= $master['waktu_transaksi'] ?></p>
    <p>Pelanggan: <?= htmlspecialchars($master['nama']) ?></p>
    <p>Keterangan: <?= htmlspecialchars($master['keterangan']) ?></p>
    <p>Total: <b><?= rupiah($master['total']) ?></b></p>
</div>

<div class="card">
    <h2>Detail Barang</h2>
    <table>
        <tr>
            <th>Kode</th><th>Nama Barang</th><th>Harga</th><th>Qty</th><th>Subtotal</th><th>Aksi</th>
        </tr>
        <?php while ($d = mysqli_fetch_assoc($detail)) { ?>
        <tr>
            <td><?= $d['kode_barang'] ?></td>
            <td><?= htmlspecialchars($d['nama_barang']) ?></td>
            <td><?= rupiah($d['harga']) ?></td>
            <td><?= $d['qty'] ?></td>
            <td><?= rupiah($d['harga'] * $d['qty']) ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="barang_id" value="<?= $d['barang_id'] ?>">
                    <button class="btn danger small" type="submit" name="hapus">Hapus</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

<div class="card">
    <h2>Tambah Barang</h2>
    <form method="post">
        <label>Barang</label>
        <select name="barang_id" required>
            <option value="">-- Pilih barang --</option>
            <?php while ($b = mysqli_fetch_assoc($pilihan)) { ?>
            <option value="<?= $b['id'] ?>"><?= $b['kode_barang'] ?> - <?= htmlspecialchars($b['nama_barang']) ?> (<?= rupiah($b['harga']) ?>)</option>
            <?php } ?>
        </select>
        <label>Qty</label>
        <input type="number" name="qty" value="1" min="1" required>
        <p><button class="btn" type="submit" name="simpan">Simpan</button>
        <a class="btn gray" href="index.php">Kembali</a></p>
    </form>
</div>

</div>
</body>
</html>
