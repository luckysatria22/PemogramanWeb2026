<?php
// Skrip sekali jalan: memindahkan data/buku.json (jobsheet-06) ke tabel buku.
// Jalankan dari terminal: php migrasi_buku.php

require __DIR__ . '/includes/koneksi.php';

$path = __DIR__ . '/data/buku.json'; // sesuaikan lokasi file JSON-mu
if (!file_exists($path)) {
    die("File tidak ditemukan: $path\n");
}

$data = json_decode(file_get_contents($path), true);
if (!is_array($data)) {
    die("Isi JSON tidak valid: " . json_last_error_msg() . "\n");
}

$stmt = $pdo->prepare(
    "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori)
     VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)"
);

$jumlah = 0;

// Transaksi: kalau satu baris gagal, semua dibatalkan (tidak setengah-setengah)
$pdo->beginTransaction();
try {
    foreach ($data as $b) {
        $stmt->execute([
            'judul'     => $b['judul'],
            'pengarang' => $b['pengarang'],
            'tahun'     => (int) $b['tahun'],
            'isbn'      => $b['isbn'] ?? null,
            'stok'      => (int) ($b['stok'] ?? 0),
            'kategori'  => $b['kategori'] ?? null,
        ]);
        $jumlah++;
    }
    $pdo->commit();
    echo "Berhasil memindahkan $jumlah buku ke database.\n";
} catch (PDOException $e) {
    $pdo->rollBack();
    die("Migrasi dibatalkan: " . $e->getMessage() . "\n");
}