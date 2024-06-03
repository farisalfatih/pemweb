<?php
// Include file koneksi database dan functions
require 'functions.php';

// Ambil ID berita dari parameter URL
$id = $_GET['id'];

// Panggil fungsi hapus_berita dan periksa apakah berhasil
if (hapus_berita($id)) {
    // Redirect ke halaman admin_dashboard.php setelah berhasil menghapus
    header("Location: admin_dashboard.php");
    exit();
} else {
    // Tampilkan pesan kesalahan jika gagal menghapus
    echo "<script>alert('Error: Gagal menghapus berita.'); window.location.href = 'admin_dashboard.php';</script>";
}

// Menutup koneksi database
$conn->close();
?>
