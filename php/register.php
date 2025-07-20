<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $nohp     = trim($_POST['nohp'] ?? '');

    if ($username === '' || $password === '' || $nohp === '') {
        echo "<script>alert('Harap lengkapi semua data yang wajib diisi!'); window.location.href = '../register.html';</script>";
        exit();
    }

    $username = str_replace('|', '', $username);
    $password = str_replace('|', '', $password);
    $email    = str_replace('|', '', $email);
    $nohp     = str_replace('|', '', $nohp);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $line = "$username|$hashedPassword|$email|$nohp\n";

    file_put_contents('../data/users.txt', $line, FILE_APPEND);

    echo "<script>alert('Pendaftaran Berhasil.'); window.location.href = '../login.html';</script>";
    exit();
} else {
    echo "<script>alert('Akses Tidak Valid !!'); window.location.href = '../register.html';</script>";
    exit();
}
?>