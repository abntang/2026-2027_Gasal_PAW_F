-- ============================================================
-- Praktikum Pengembangan Aplikasi Web - Modul 6
-- Database : penjualan
-- ============================================================

DROP DATABASE IF EXISTS penjualan;
CREATE DATABASE penjualan CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE penjualan;

-- ------------------------------------------------------------
-- Tabel master
-- ------------------------------------------------------------
CREATE TABLE supplier (
  id     INT AUTO_INCREMENT PRIMARY KEY,
  nama   VARCHAR(100) NOT NULL,
  telp   VARCHAR(12),
  alamat TEXT
) ENGINE=InnoDB;

CREATE TABLE barang (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  kode_barang VARCHAR(10)  NOT NULL UNIQUE,
  nama_barang VARCHAR(100) NOT NULL,
  harga       INT NOT NULL,
  stok        INT NOT NULL DEFAULT 0,
  supplier_id INT NOT NULL,
  CONSTRAINT fk_barang_supplier FOREIGN KEY (supplier_id)
    REFERENCES supplier(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE pelanggan (
  id            VARCHAR(20) PRIMARY KEY,
  nama          VARCHAR(100) NOT NULL,
  jenis_kelamin ENUM('L','P') NOT NULL,
  telp          VARCHAR(12),
  alamat        TEXT
) ENGINE=InnoDB;

CREATE TABLE user (
  id_user  TINYINT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(30) NOT NULL UNIQUE,
  password VARCHAR(35) NOT NULL,
  nama     VARCHAR(50),
  alamat   VARCHAR(150),
  hp       VARCHAR(20),
  level    TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel transaksi (MASTER) dan transaksi_detail (DETAIL)
-- ------------------------------------------------------------
CREATE TABLE transaksi (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  waktu_transaksi  DATE NOT NULL,
  keterangan       TEXT,
  total            INT NOT NULL DEFAULT 0,
  pelanggan_id     VARCHAR(20) NOT NULL,
  CONSTRAINT fk_transaksi_pelanggan FOREIGN KEY (pelanggan_id)
    REFERENCES pelanggan(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;


CREATE TABLE transaksi_detail (
  transaksi_id INT NOT NULL,
  barang_id    INT NOT NULL,
  harga        INT NOT NULL,
  qty          INT NOT NULL,
  PRIMARY KEY (transaksi_id, barang_id),
  CONSTRAINT fk_detail_transaksi FOREIGN KEY (transaksi_id)
    REFERENCES transaksi(id) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_detail_barang FOREIGN KEY (barang_id)
    REFERENCES barang(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE pembayaran (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  waktu_bayar  DATETIME NOT NULL,
  total        INT NOT NULL,
  metode       ENUM('TUNAI','TRANSFER','EDC') NOT NULL,
  transaksi_id INT NOT NULL,
  CONSTRAINT fk_pembayaran_transaksi FOREIGN KEY (transaksi_id)
    REFERENCES transaksi(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- DATA AWAL
-- ============================================================
INSERT INTO supplier (nama, telp, alamat) VALUES
('PT Sumber Makmur',   '031555101', 'Jl. Rungkut Industri No. 12, Surabaya'),
('CV Maju Jaya',       '031555102', 'Jl. Ahmad Yani No. 45, Surabaya'),
('UD Berkah Sentosa',  '031555103', 'Jl. Raya Darmo No. 8, Surabaya'),
('PT Global Elektronik','031555104','Jl. Tunjungan No. 21, Surabaya'),
('CV Mitra Abadi',     '031555105', 'Jl. Kenjeran No. 77, Surabaya');

INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES
('BRG001', 'Beras Premium 5 Kg',     68000,  50, 1),
('BRG002', 'Minyak Goreng 2 Liter',  38000,  80, 1),
('BRG003', 'Gula Pasir 1 Kg',        17500, 100, 2),
('BRG004', 'Kopi Bubuk 200 g',       22000,  60, 2),
('BRG005', 'Mie Instan (dus)',      115000,  40, 3),
('BRG006', 'Sabun Mandi Cair',       24500,  70, 3),
('BRG007', 'Lampu LED 12 Watt',      32000,  90, 4),
('BRG008', 'Kabel USB Type-C',       45000,  55, 4),
('BRG009', 'Buku Tulis (pak 10)',    42000, 120, 5),
('BRG010', 'Baterai AA (pak 4)',     18000,  75, 4);

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('PLG001', 'Budi Santoso',   'L', '081234500001', 'Jl. Mawar No. 1, Surabaya'),
('PLG002', 'Siti Rahayu',    'P', '081234500002', 'Jl. Melati No. 2, Sidoarjo'),
('PLG003', 'Agus Wijaya',    'L', '081234500003', 'Jl. Kenanga No. 3, Gresik'),
('PLG004', 'Dewi Lestari',   'P', '081234500004', 'Jl. Anggrek No. 4, Surabaya'),
('PLG005', 'Rizky Pratama',  'L', '081234500005', 'Jl. Dahlia No. 5, Malang'),
('PLG006', 'Nur Aini',       'P', '081234500006', 'Jl. Teratai No. 6, Surabaya');

INSERT INTO user (username, password, nama, alamat, hp, level) VALUES
('admin',  MD5('admin123'),  'Administrator', 'Jl. Pemuda No. 1, Surabaya', '081200000001', 1),
('kasir1', MD5('kasir123'),  'Kasir Satu',    'Jl. Basuki Rahmat No. 2',     '081200000002', 2),
('kasir2', MD5('kasir123'),  'Kasir Dua',     'Jl. Diponegoro No. 3',        '081200000003', 2),
('gudang', MD5('gudang123'), 'Staf Gudang',   'Jl. Gubeng No. 4',            '081200000004', 3),
('owner',  MD5('owner123'),  'Pemilik Toko',  'Jl. Darmo No. 5',             '081200000005', 1);

INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES
('2026-10-01', 'Belanja bulanan',        0, 'PLG001'),
('2026-10-03', 'Pembelian kebutuhan dapur', 0, 'PLG002'),
('2026-10-05', 'Pembelian perlengkapan kantor', 0, 'PLG003'),
('2026-10-08', 'Belanja mingguan',       0, 'PLG004'),
('2026-10-10', 'Pembelian elektronik',   0, 'PLG005'),
('2026-10-12', 'Belanja bulanan',        0, 'PLG006'),
('2026-10-15', 'Pesanan toko kelontong', 0, 'PLG001'),
('2026-10-18', 'Belanja harian',         0, 'PLG002'),
('2026-10-21', 'Pembelian grosir',       0, 'PLG003'),
('2026-10-25', 'Belanja akhir bulan',    0, 'PLG004');

INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1, 1, 68000, 2), (1, 2, 38000, 3), (1, 3, 17500, 4),
(2, 2, 38000, 2), (2, 4, 22000, 3),
(3, 9, 42000, 5), (3, 8, 45000, 2),
(4, 3, 17500, 6), (4, 6, 24500, 2), (4, 4, 22000, 1),
(5, 7, 32000, 4), (5, 8, 45000, 3),
(6, 1, 68000, 1), (6, 5, 115000, 1),
(7, 5, 115000, 3), (7, 2, 38000, 6),
(8, 6, 24500, 3), (8, 3, 17500, 2),
(9, 9, 42000, 10), (9, 7, 32000, 5), (9, 1, 68000, 2),
(10, 4, 22000, 5), (10, 2, 38000, 2);


UPDATE transaksi t
SET t.total = (SELECT COALESCE(SUM(d.harga * d.qty), 0)
               FROM transaksi_detail d WHERE d.transaksi_id = t.id);

INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id)
SELECT CONCAT(waktu_transaksi, ' 10:00:00'), total,
       ELT(1 + (id % 3), 'TUNAI', 'TRANSFER', 'EDC'), id
FROM transaksi;
