<?php
session_start(); 
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $game     = $_POST['game'] ?? '';
  $userid   = $_POST['userid'] ?? '';
  $server   = $_POST['server'] ?? '-';
  $nominal  = $_POST['nominal'] ?? '';
  $jumlah   = $_POST['jumlah'] ?? '';
  $metode   = $_POST['metode'] ?? '';
  $whatsapp = $_POST['whatsapp'] ?? '';

  if (!isset($_SESSION['username'])) {
    header("Location: ../login.html");
    exit();
  }
  $username = $_SESSION['username']; 

  if (!$game || !$userid || !$nominal || !$jumlah || !$metode || !$whatsapp) {
    header("Location: ../topup_{$game}.html?error=1");
    exit();
  }

  $data = [
    date('Y-m-d H:i:s'),
    $game,
    $userid,
    $server,
    $nominal,
    $jumlah,
    $metode,
    $whatsapp,
    '2',          
    $username     
  ];

  $line = implode('|', $data) . PHP_EOL;
  file_put_contents('../data/transaksi.txt', $line, FILE_APPEND);

  header("Location: ../pesanan.php");
  exit();
}
?>