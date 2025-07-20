<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = htmlspecialchars(trim($_POST['nama']));
  $email = htmlspecialchars(trim($_POST['email']));
  $pesan = htmlspecialchars(trim($_POST['pesan']));

  $timestamp = date('Y-m-d H:i:s');
  $data = "$timestamp | Nama: $nama | Email: $email | Pesan: $pesan" . PHP_EOL;

  $filePath = '../data/contactus.txt';

  if (!is_dir('../data')) {
    mkdir('../data', 0755, true); 
  }

  if (file_put_contents($filePath, $data, FILE_APPEND | LOCK_EX)) {
    echo "<script>alert('Pesan Anda Telah Terkirim. Terima kasih!'); window.location.href = '../contact.html';</script>";
  } else {
    echo "<script>alert('Gagal Menyimpan Pesan.'); window.location.href = '../contact.html';</script>";
  }
} else {
  header('Location: ../contact.html');
  exit();
}
?>