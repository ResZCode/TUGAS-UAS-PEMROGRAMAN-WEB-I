<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.html");
  exit;
}

$usersFile = 'data/users.txt';
$currentUser = $_SESSION['username'];

$profile = [
  'name' => $currentUser,
  'email' => '',
  'wa' => '',
  'avatar' => strtoupper($currentUser[0])
];

if (file_exists($usersFile)) {
  $lines = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    list($username, $pass, $email, $wa) = explode('|', trim($line));
    if (strtolower($username) === strtolower($currentUser)) {
      $profile['email'] = $email;
      $profile['wa'] = $wa;
      $profile['avatar'] = strtoupper($username[0]);
      $profile['name'] = $username;
      break;
    }
  }
}

$transaksiFile = 'data/transaksi.txt';
$totalTransaksi = 0;
$dalamProses = 0;
$sukses = 0;
$gagal = 0;

if (file_exists($transaksiFile)) {
  $lines = file($transaksiFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

  foreach ($lines as $line) {
    $data = explode('|', trim($line));
    if (count($data) < 9) continue;
    $status = $data[8];
    $totalTransaksi++;
    switch ($status) {
      case '0': $dalamProses++; break;
      case '2': $sukses++; break;
      case '3': $gagal++; break;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard | Resz.Store</title>
  <link rel="stylesheet" href="css/profile.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <div class="dashboard-container">
    <aside class="sidebar">
      <div class="brand">Resz.Store</div>
      <ul class="menu">
        <li><a href="index.html"><i class="fas fa-home"></i>Home</a></li>
        <li><a href="topup_ml.html"><i class="fas fa-gem"></i>Top Up</a></li>
        <li><a href="history.html"><i class="fas fa-clock"></i>History</a></li>
        <li><a href="contact.html"><i class="fas fa-envelope"></i>Contact Us</a></li>
        <li><a href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
      </ul>
    </aside>

    <main class="main-content">
      <div class="topbar">
        <div class="topbar-left">
          <div class="avatar"><?php echo $profile['avatar']; ?></div>
          <div class="topbar-name">
            <strong><?php echo htmlspecialchars($profile['name']); ?></strong>
            <div class="badge">Member</div>
          </div>
        </div>
      <div class="user-meta-inline">
        <span class="meta-item">
          <i class="fas fa-envelope"></i>
          <?php echo htmlspecialchars($profile['email']); ?>
        </span>
        <span class="meta-item">
            <i class="fas fa-phone"></i>
          <?php echo htmlspecialchars($profile['wa']); ?>
        </span>
      </div>
      </div>
      <div class="coin-card">
        <div class="coin-label">Top Up</div>
        <div class="coin-actions">
          <a href="topup_ml.html" class="btn-topup">Top Up Sekarang</a>
        </div>
      </div>

      <h3 class="section-title">Transaksi Hari Ini</h3>
      <div class="stat-box gray stat-full">
        <p>Total Transaksi</p>
        <h2><?php echo $totalTransaksi; ?></h2>
      </div>
      <div class="stat-row">
        <div class="stat-box blue">
          <p>Dalam Proses</p>
          <h2><?php echo $dalamProses; ?></h2>
        </div>
        <div class="stat-box green">
          <p>Sukses</p>
          <h2><?php echo $sukses; ?></h2>
        </div>
        <div class="stat-box orange">
          <p>Gagal</p>
          <h2><?php echo $gagal; ?></h2>
        </div>
      </div>

  <script>
    function logout() {
      localStorage.removeItem('isLoggedIn');
      localStorage.removeItem('username');
      window.location.href = 'login.html';
    }
  </script>
</body>
</html>