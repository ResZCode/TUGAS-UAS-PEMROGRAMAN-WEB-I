<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($username === '' || $password === '') {
    echo "<script>alert('Username dan password wajib diisi.'); window.location.href = '../login.html';</script>";
    exit;
  }

  $file = '../data/users.txt';
  if (!file_exists($file)) {
    echo "<script>alert('Belum ada data pengguna.'); window.location.href = '../login.html';</script>";
    exit;
  }

  $users = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($users as $user) {
    list($savedUser, $hashedPass, $email, $nohp) = explode('|', $user);

    if ($username === $savedUser && password_verify($password, $hashedPass)) {
      $_SESSION['username'] = $username;
      echo "
        <script>
          localStorage.setItem('isLoggedIn', 'true');
          localStorage.setItem('username', '$username');
          window.location.href = '../profile.php?u=$username';
        </script>
      ";
      exit;
    }
  }

  echo "<script>alert('Username Atau Password Salah.'); window.location.href = '../login.html';</script>";
  exit;
} else {
  echo "<script>alert('Akses Tidak Valid !!'); window.location.href = '../login.html';</script>";
  exit;
}
?>