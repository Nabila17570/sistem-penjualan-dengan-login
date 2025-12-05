<?php
 session_start();
 
// Cek apakah user sudah login
 if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
 }
 
// Inisialisasi cart di session jika belum ada
 if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
 }
 
// Proses form submit
 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil dan sanitasi input
    $kode   = trim($_POST['kode']   ?? '');
    $nama   = trim($_POST['nama']   ?? '');
    $harga  = $_POST['harga'] ?? '';
    $jumlah = $_POST['jumlah'] ?? '';
 
    // Validasi sederhana
    $errors = [];
    if ($kode === '')  $errors[] = "Kode barang harus diisi.";
    if ($nama === '')  $errors[] = "Nama barang harus diisi.";
    if ($harga === '' || !is_numeric($harga) || $harga < 0) $errors[] = "Harga tidak valid.";
    if ($jumlah === ''|| !is_numeric($jumlah) || $jumlah <= 0) $errors[] = "Jumlah tidak 
valid.";
 
    if (empty($errors)) {
        // Normalisasi tipe
        $harga  = (int) $harga;
        $jumlah = (int) $jumlah;
        $lineTotal = $harga * $jumlah;
 
        // Tambah item ke cart (session)
        $_SESSION['cart'][] = [
            'kode' => $kode,
            'nama' => $nama,
            'harga' => $harga,
            'jumlah' => $jumlah,
            'lineTotal' => $lineTotal,
        ];
 
        // Redirect untuk menghindari resubmit (PRG pattern)
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
 }
 
// Hitung total dari cart
 $cart = $_SESSION['cart'];
 $grandtotal = 0;
 foreach ($cart as $item) {
    $grandtotal += $item['lineTotal'];
 }
 
// Hitung diskon berdasarkan grandtotal
 if ($grandtotal == 0) {
    $diskon = 0;
    $d = "0%";
 } else if ($grandtotal < 50000) {
    $d = "5%";
    $diskon = 0.05 * $grandtotal;    // 5%
 } else if ($grandtotal <= 100000) {
    $d = "10%";
    $diskon = 0.10 * $grandtotal;    // 10%
 } else {
    $d = "15%";
    $diskon = 0.15 * $grandtotal;    // 15%
 }
 $totalbayar = $grandtotal - $diskon;
 ?>
 <!DOCTYPE html>
 <html lang="id">
 <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard - POLGAN MART</title>
    <style>
        :root{
            --bg:#f5f7fb;
            --card:#ffffff;
            --accent:#1f6feb;
            --muted:#6b7280;
            --border:#e6e9ef;
            --success:#10b981;
        }
        *{box-sizing:border-box;font-family:Inter, "Segoe UI", Roboto, Arial, sans-serif}
        body{
            margin:0;
            background:var(--bg);
            color:#111827;
            padding:24px;
        }
 
        .container{
            max-width:900px;
            margin:0 auto;
        }
 
        header.app-header{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:16px;
            margin-bottom:20px;
        }
        .brand{
            display:flex;
            gap:14px;
            align-items:center;
        }
        .logo{
            width:56px;
            height:56px;
            border-radius:8px;
            background:linear-gradient(135deg,var(--accent),#5aa0ff);
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
            font-size:18px;
            box-shadow:0 6px 18px rgba(31,111,235,0.12);
        }
        .brand h1{
            margin:0;
            font-size:20px;
            letter-spacing:1px;
        }
        .brand p{
            margin:0;
            color:var(--muted);
            font-size:13px;
        }
 
        .user-box{
            text-align:right;
        }
        .user-box .name{
            font-weight:600;
        }
        .role{
            color:var(--muted);
            font-size:13px;
        }
        .logout-btn{
            display:inline-block;
            margin-top:8px;
            padding:8px 12px;
            background:transparent;
            border:1px solid var(--border);
            border-radius:8px;
            text-decoration:none;
            color:#111827;
            font-size:14px;
        }
        .logout-btn:hover{
            background:#fff;
            box-shadow:0 6px 18px rgba(0,0,0,0.06);
        }
 
        .card{
            background:var(--card);
            border:1px solid var(--border);
            border-radius:12px;
            padding:18px;
            box-shadow:0 6px 18px rgba(9,30,66,0.04);
        }
 
        .mart-header{
            text-align:center;
            margin-bottom:18px;
        }
        .mart-header h2{
            margin:0;
            font-size:18px;
        }
        .mart-header p{
            margin:6px 0 0 0;
            color:var(--muted);
            font-size:13px;
        }
 
        table{
            width:100%;
            border-collapse:collapse;
            margin-top:12px;
            font-size:14px;
        }
        th, td{
            padding:10px 12px;
            border-bottom:1px solid var(--border);
            text-align:left;
        }
        th{
            background:transparent;
            color:var(--muted);
            font-weight:600;
            font-size:13px;
        }
        tr:nth-child(even) td{
            background:#fbfdff;
        }
 
        .total-row td{
            font-weight:700;
            font-size:16px;
            border-top:2px solid var(--border);
        }
 
        .right{
            text-align:right;
        }
 
 .small-muted{
     color:var(--muted);
     font-size:13px;
 }
 .form-group{
     margin-bottom:16px;
 }
 label{
     display:block;
     font-weight:600;
     margin-bottom:6px;
     color:#374151;
     font-size:14px;
 }
 input[type="text"], input[type="number"],select{
     width:100%;
     padding:8px 10px;
     border:1px solid var(--border);
     border-radius:8px;
     font-size:14px;
 }
 .btn, .btn-reset {
     padding:10px 14px;
     border-radius:8px;
     border:0;
     cursor:pointer;
     margin-right:8px;
 }
 .btn { background:var(--accent); color:#fff; }
 .btn-reset { background:#fff; border:1px solid var(--border); }
 @media (max-width:600px){
     .brand h1{font-size:16px}
     th,td{padding:8px}
     .logo{width:46px;height:46px;font-size:16px}
 }
</style>
</head>
<body>
<div class="container">
 <header class="app-header">
     <div class="brand">
         <div class="logo">PM</div>
         <div>
             <h1>--POLGAN MART--</h1>
             <p class="small-muted">Sistem Penjualan Sederhana</p>
         </div>
     </div>
     <div class="user-box">
    <div class="name">
        Selamat datang, <?php echo htmlspecialchars($_SESSION['username'] ?? 'admin'); ?>!
    </div>
    <div class="role">
        Role: <?php echo htmlspecialchars($_SESSION['role'] ?? '-'); ?>
    </div>
    <a class="logout-btn" href="logout.php">Logout</a>
</div>

        </header>
        <main class="card">
            <?php if (!empty($errors)): ?>
                <div style="margin-bottom:12px;color:#b91c1c">
                    <?php foreach ($errors as $err) {
                        echo htmlspecialchars($err) . "<br>";
                    } ?>
                </div>
            <?php endif; ?>
 
            <form method="post" novalidate>
                <div class="form-group">
                    <label for="kode">Kode Barang</label>
                    <!-- <input type="text" id="kode" name="kode" placeholder="Masukkan Kode 
Barang" required> -->
                    <select id="kode" name="kode" required>
                    <option value="" disabled selected>Pilih Kode Barang</option>
                    <option value="BRG001">BRG001 - Sabun Mandi</option>
                    <option value="BRG002">BRG002 - Sikat Gigi</option>
                    <option value="BRG003">BRG003 - Pasta Gigi</option>
                    <option value="BRG004">BRG004 - Shampoo</option>
                    <option value="BRG005">BRG005 - Handuk</option>
                </select>
                </div>
                <div class="form-group">
                    <label for="nama">Nama Barang</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan Nama 
Barang" required>
                </div>
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" id="harga" name="harga" placeholder="Masukkan Harga" 
min="0" required>
                </div>
                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" id="jumlah" name="jumlah" placeholder="Masukkan 
Jumlah" min="1" required>
                </div>
                <button type="submit" class="btn">Tambahkan</button>
                <button type="reset" class="btn-reset">Batal</button>
            </form>
            <div class="mart-header">
                <h2>Daftar Pembelian</h2>
            </div>
            <?php if (count($cart) === 0): ?>
                <p>Tidak ada pembelian.</p>
            <?php else: ?>
                <table aria-label="Daftar Pembelian">
                    <thead>
                        <tr>
                        <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th class="right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['kode']); ?></td>
                                <td><?php echo htmlspecialchars($item['nama']); ?></td>
                                <td>Rp <?php echo number_format($item['harga'],0,',','.'); ?>
 <td><?php echo htmlspecialchars($item['jumlah']); ?>
</td>
<td class="right">
    Rp <?php echo number_format($item['lineTotal'], 0, ',', '.'); ?>
</td>


                            </tr>
                        <?php endforeach; ?>
                        <tr class="total-row">
                            <td colspan="3"></td>
                            <td class="right">Total Belanja</td>
                            <td class="right">Rp <?php echo number_format($grandtotal,0,',','
 .'); ?></td>
                        </tr>
                        <tr class="total-row">
                            <td colspan="3"></td>
                            <td class="right">Diskon</td>
                            <td class="right">Rp <?php echo number_format($diskon,0,',','.'); 
?> (<?php echo $d; ?>)</td>
                        </tr>
                        <tr class="total-row">
                            <td colspan="3"></td>
                            <td class="right">Total Bayar</td>
                            <td class="right">Rp <?php echo number_format($totalbayar,0,',','
 .'); ?></td>
                        </tr>
                    </tbody>
                </table>
                <form method="post" style="margin-top:12px;">
                    <button type="submit" name="clear_cart" class="btn-reset">Kosongkan 
Keranjang</button>
                </form>
            <?php endif; ?>
        </main>
    </div>
 </body>
 </html>
 <?php
 // Tombol 'Kosongkan Keranjang' akan dikirim juga lewat POST — tangani di akhir file
 if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
<script>
   //munculkan nama baranng, harga barang
   const kodeSelect = document.getElementById('kode');
   const namaInput = document.getElementById('nama');
   const hargaInput = document.getElementById('harga');
   const barangData = {
       "BRG001": { nama: "Sabun Mandi", harga: 15000 },
       "BRG002": { nama: "Sikat Gigi", harga: 12000 },
       "BRG003": { nama: "Pasta Gigi", harga: 20000 },
       "BRG004": { nama: "Shampoo", harga: 25000 },
       "BRG005": { nama: "Handuk", harga: 30000 }
   };
   kodeSelect.addEventListener('change', function() {
       const selectedKode = this.value;
       if (barangData[selectedKode]) {
           namaInput.value = barangData[selectedKode].nama;
           hargaInput.value = barangData[selectedKode].harga;
       } else {
           namaInput.value = '';
           hargaInput.value = '';
       }
   });
</script>