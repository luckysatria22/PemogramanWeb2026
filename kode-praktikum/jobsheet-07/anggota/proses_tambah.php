<?php
session_start();

$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

$errors = [];
if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}
if ($noAnggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

// Inisialisasi session anggota jika belum ada
if (!isset($_SESSION['anggota'])) {
    $_SESSION['anggota'] = [];
}

// Simpan data anggota baru ke session
$_SESSION['anggota'][] = [
    'nama' => $nama,
    'email' => $email,
    'telepon' => $telepon,
    'alamat' => $alamat,
];

// Set flash message sukses dan redirect ke daftar anggota
$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil ditambahkan.'];
header('Location: list.php');
exit;
?>