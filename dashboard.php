<?php
session_start();

// --- DATA BARANG ---
$data_barang = [
    "BRG001" => ["nama" => "Sabun Mandi", "harga" => 15000],
    "BRG002" => ["nama" => "Sikat Gigi", "harga" => 20000],
    "BRG003" => ["nama" => "Pasta Gigi", "harga" => 10000],
    "BRG004" => ["nama" => "Shampo", "harga" => 20000],
    "BRG005" => ["nama" => "Handuk", "harga" => 25000],
];

// Jika belum ada keranjang
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// --- Ambil kode barang yang dipilih ---
$pilih_kode = $_POST['kode'] ?? "";
$nama_otomatis = $pilih_kode && isset($data_barang[$pilih_kode]) ? $data_barang[$pilih_kode]['nama'] : "";
$harga_otomatis = $pilih_kode && isset($data_barang[$pilih_kode]) ? $data_barang[$pilih_kode]['harga'] : "";

// Proses tambah barang
if (isset($_POST['tambah'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $jumlah = $_POST['jumlah'];

    $total = $harga * $jumlah;

    $_SESSION['keranjang'][] = [
        'kode' => $kode,
        'nama' => $nama,
        'harga' => $harga,
        'jumlah' => $jumlah,
        'total' => $total
    ];
}

// Proses kosongkan keranjang
if (isset($_GET['clear'])) {
    $_SESSION['keranjang'] = [];
    header("Location: dashboard.php");
    exit;
}

$username = $_SESSION['username'] ?? 'admin';
$role = $_SESSION['role'] ?? 'Dosen';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>POLGAN MART - Dashboard</title>

    <style>
        body { font-family: Arial; background:#f4f6f9; margin:0; padding:0; }
        .header { background:#fff; padding:20px; display:flex; justify-content:space-between; border-bottom:1px solid #ddd; }
        .logo-box { display:flex; align-items:center; gap:15px; }
        .logo { width:50px; height:50px; background:#2F80ED; color:white; font-size:22px; font-weight:bold; border-radius:10px; display:flex; align-items:center; justify-content:center; }
        .title { font-size:22px; font-weight:bold; }
        .container { width:87%; margin:20px auto; background:#fff; padding:25px; border-radius:12px; }
        input, select { width:100%; padding:10px; margin-bottom:12px; border-radius:6px; border:1px solid #ccc; }
        .btn-primary { padding:10px 18px; background:#2F80ED; color:white; border-radius:8px; border:none; cursor:pointer; }
        .btn-secondary { padding:10px 18px; background:#e0e0e0; border-radius:8px; border:none; cursor:pointer; }
        .btn-danger { margin-top:20px; padding:10px 20px; background:#c53030; color:white; border-radius:8px; text-decoration:none; display:inline-block; }
        table { width:100%; margin-top:25px; border-collapse:collapse; }
        th, td { border-bottom:1px solid #eee; padding:10px; }
        .text-right { text-align:right; }
        .total-row td { font-weight:bold; }
    </style>
</head>
<body>

<div class="header">
    <div class="logo-box">
        <div class="logo">PM</div>
        <div>
            <div class="title">--POLGAN MART--</div>
            <div class="subtitle">Sistem Penjualan Sederhana</div>
        </div>
    </div>

    <div class="user-box" style="text-align:right;">
        Selamat datang, <b><?= $username ?></b>! <br>
        Role: <?= $role ?> <br><br>
        <a href="logout.php" class="logout-btn" style="color:white; background:red; padding:7px 14px; border-radius:6px; text-decoration:none;">Logout</a>
    </div>
</div>

<div class="container">

    <h3>Form Input Barang</h3>

    <!-- FORM INPUT BARANG -->
    <form method="POST">

        <!-- PILIH KODE + NAMA BARANG -->
        <select name="kode" onchange="this.form.submit()">
            <option value="">-- Pilih Barang --</option>

            <?php foreach ($data_barang as $kode => $brg): ?>
                <option value="<?= $kode ?>" <?= $pilih_kode == $kode ? "selected" : "" ?>>
                    <?= $kode . " - " . $brg['nama'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- NAMA OTOMATIS -->
        <input type="text" name="nama" placeholder="Nama Barang" value="<?= $nama_otomatis ?>" readonly>

        <!-- HARGA OTOMATIS -->
        <input type="number" name="harga" placeholder="Harga Barang" value="<?= $harga_otomatis ?>" readonly>

        <!-- JUMLAH -->
        <input type="number" name="jumlah" placeholder="Masukkan Jumlah" required>

        <button class="btn-primary" type="submit" name="tambah">Tambahkan</button>
        <button class="btn-secondary" type="reset">Batal</button>

    </form>

    <div class="center-title" style="text-align:center; font-weight:bold; margin-top:30px; font-size:19px;">
        Daftar Pembelian
    </div>

    <!-- TABEL KERANJANG -->
    <table>
        <tr>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
        </tr>

        <?php 
        if (count($_SESSION['keranjang']) == 0): ?>
            <tr><td colspan="5" style="text-align:center;">Keranjang masih kosong</td></tr>

        <?php else: 
            $total_belanja = 0;
            foreach ($_SESSION['keranjang'] as $item):
                $total_belanja += $item['total'];
        ?>
            <tr>
                <td><?= $item['kode'] ?></td>
                <td><?= $item['nama'] ?></td>
                <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                <td><?= $item['jumlah'] ?></td>
                <td>Rp <?= number_format($item['total'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>

        <?php
            $diskon = $total_belanja * 0.05;
            $total_bayar = $total_belanja - $diskon;
        ?>

            <tr class="total-row">
                <td colspan="4" class="text-right">Total Belanja</td>
                <td>Rp <?= number_format($total_belanja, 0, ',', '.') ?></td>
            </tr>

            <tr class="total-row">
                <td colspan="4" class="text-right">Diskon (5%)</td>
                <td>Rp <?= number_format($diskon, 0, ',', '.') ?></td>
            </tr>

            <tr class="total-row">
                <td colspan="4" class="text-right">Total Bayar</td>
                <td>Rp <?= number_format($total_bayar, 0, ',', '.') ?></td>
            </tr>

        <?php endif; ?>
    </table>

    <a href="dashboard.php?clear=1" class="btn-danger">Kosongkan Keranjang</a>
</div>

</body>
</html>
