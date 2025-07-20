<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$filename = "data/transaksi.txt";
if (!file_exists($filename)) {
  echo "Tidak ada transaksi ditemukan.";
  exit;
}

$lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if (!$lines) {
  echo "Data tidak tersedia.";
  exit;
}

$index = isset($_GET['i']) ? intval($_GET['i']) : count($lines) - 1;
if (!isset($lines[$index])) {
  echo "Transaksi tidak ditemukan.";
  exit;
}

$data = explode('|', $lines[$index]);
if (count($data) !== 10) {
  echo "Format data transaksi salah: jumlah kolom tidak sesuai.";
  exit;
}

list($tanggalRaw, $game, $userid, $server, $nominal, $jumlah, $metode, $wa, $status, $usernameOwner) = $data;
$tanggal = date("Y-m-d H:i:s", strtotime($tanggalRaw));
$orderId = '#ORD' . (1000 + $index);

$gameNama = [
  'ml'   => 'Mobile Legends',
  'ff'   => 'Free Fire',
  'pubg' => 'PUBG Mobile',
  'hok'  => 'Honor Of Kings'
][$game] ?? 'Game Tidak Diketahui';

$itemList = [
  'ml' => [
    27990 => "Weekly Diamond Pass",
    55979 => "2x Weekly Pass",
    139947 => "5x Weekly Pass",
    195936 => "7x Weekly Pass",
    3600 => "12 Diamonds",
    16400 => "56 Diamonds",
    24500 => "86 Diamonds",
    48900 => "170 Diamonds",
    69900 => "240 Diamonds",
    122000 => "408 Diamonds",
    169000 => "568 Diamonds",
    259000 => "875 Diamonds",
    599000 => "2010 Diamonds",
    1499000 => "5030 Diamonds"
  ],
  'ff' => [
    1958 => "10 Diamonds",
    4895 => "25 Diamonds",
    11586 => "75 Diamonds",
    18438 => "120 Diamonds",
    35738 => "230 Diamonds",
    46346 => "300 Diamonds",
    61846 => "400 Diamonds",
    95466 => "635 Diamonds",
    148503 => "1000 Diamonds",
    301514 => "2000 Diamonds"
  ],
  'pubg' => [
    15146 => "60 UC Indo",
    30292 => "120 UC Indo",
    40738 => "160 UC Indo",
    60584 => "240 UC Indo",
    76448 => "325 UC Indo",
    106564 => "445 UC Indo",
    121710 => "505 UC Indo",
    168221 => "720 UC Indo",
    259639 => "1105 UC Indo",
    459230 => "2125 UC Indo"
  ],
  'hok' => [
    3066 => "16 Tokens",
    15327 => "80 Tokens",
    46338 => "240 Tokens",
    77349 => "400 Tokens",
    108361 => "560 Tokens",
    154878 => "830 Tokens",
    232407 => "1245 Tokens",
    464994 => "2508 Tokens",
    775109 => "4180 Tokens",
    1550397 => "8360 Tokens"
  ]
];

$nominal = (int)$nominal;
$jumlah  = (int)$jumlah;
$item    = $itemList[$game][$nominal] ?? 'Item Tidak Diketahui';

$biayaAdmin = 500;
$diskon = 0;
$total = ($nominal * $jumlah) + $biayaAdmin - $diskon;

function formatRupiah($angka) {
  return 'Rp ' . number_format($angka, 0, ',', '.');
}

$gambarGame = [
  'ml'   => 'ml.png',
  'ff'   => 'ff.png',
  'pubg' => 'pubg.png',
  'hok'  => 'hok.png'
][$game] ?? 'default-icon.png';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pesanan | Resz.Store</title>
  <link rel="stylesheet" href="css/pesanan.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
</head>
<body>
<header>
  <div class="navbar">
    <div class="logo">Resz.Store</div>
    <nav>
      <ul id="navbarLinks"></ul>
    </nav>
  </div>
</header>

<main class="wrapper">
  <div class="box detail-box">
    <h3>Detail Pesanan</h3>
    <hr>
    <div class="info">
      <p><strong>ID Order :</strong> <?= $orderId ?></p>
      <p><strong>Tanggal Transaksi :</strong> <?= $tanggal ?></p>
      <p><strong>Status Pembayaran :</strong> <span class="badge paid">Sudah Dibayar</span></p>
      <p><strong>Status Transaksi :</strong>
        <span class="badge <?= $status == '2' ? 'success' : ($status == '3' ? 'failed' : 'pending') ?>">
          <?= $status == '2' ? 'Success' : ($status == '3' ? 'Gagal' : 'Dalam Proses') ?>
        </span>
      </p>
      <p><strong>Metode Pembayaran :</strong> <?= strtoupper($metode) ?></p>
      <hr>
      <p><strong>Jumlah Pembelian :</strong> <?= $jumlah ?>   x</p>
      <p><strong>Harga per Paket :</strong> <?= formatRupiah($nominal) ?></p>
      <p><strong>Biaya Admin :</strong> <?= formatRupiah($biayaAdmin) ?></p>
      <p><strong>Diskon :</strong> <?= formatRupiah($diskon) ?></p>
      <p class="total"><strong>Total :</strong> <?= formatRupiah($total) ?></p>
    </div>
  </div>

  <div class="box item-box">
    <h3>Detail Item</h3>
    <hr>
    <div class="item">
      <img src="img/game/<?= $gambarGame ?>" alt="<?= $gameNama ?>">
      <div>
        <h4><?= $item ?></h4>
        <p><?= $gameNama ?></p>
      </div>
    </div>
    <h3>Detail Player</h3>
    <hr>
    <div class="player">
      <p><strong>ID Akun :</strong> <?= htmlspecialchars($userid) ?></p>
      <?php if ($game === 'ml'): ?>
        <p><strong>Server Akun :</strong> <?= htmlspecialchars($server) ?: '-' ?></p>
      <?php endif; ?>
      <p><strong>Jenis Paket :</strong> <?= strpos($item, 'Pass') !== false ? 'Special Item' : 'Top Up Instan' ?></p>
      <p><strong>Nomor WhatsApp :</strong> <?= htmlspecialchars($wa) ?></p>
    </div>
  </div>
</main>

<footer>
  <p>&copy; 2025 Resz.Store - All Rights Reserved.</p>
</footer>

<script>
  const navbar = document.getElementById('navbarLinks');
  const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
  const username = localStorage.getItem('username');
  navbar.innerHTML = isLoggedIn ? `
    <li><a href="index.html"><i class="fas fa-home"></i><span>Home</span></a></li>
    <li><a href="topup_ml.html"><i class="fas fa-gem"></i><span>Top Up</span></a></li>
    <li><a href="history.html"><i class="fas fa-clock"></i><span>History</span></a></li>
    <li><a href="contact.html"><i class="fas fa-envelope"></i><span>Contact Us</span></a></li>
    <li><a href="profile.php"><i class="fas fa-user"></i><span>${username}</span></a></li>
    <li><a href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
  ` : `
    <li><a href="index.html"><i class="fas fa-home"></i><span>Home</span></a></li>
    <li><a href="topup_ml.html"><i class="fas fa-gem"></i><span>Top Up</span></a></li>
    <li><a href="history.html"><i class="fas fa-clock"></i><span>History</span></a></li>
    <li><a href="contact.html"><i class="fas fa-envelope"></i><span>Contact Us</span></a></li>
    <li><a href="login.html"><i class="fas fa-sign-in-alt"></i><span>Login</span></a></li>
    <li><a href="register.html"><i class="fas fa-user-plus"></i><span>Register</span></a></li>
  `;
  function logout() {
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('username');
    window.location.href = 'index.html';
  }
</script>
</body>
</html>