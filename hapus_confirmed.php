<?php
// Include file koneksi database
require 'functions.php';

// Ambil ID berita dari parameter URL
$id = $_GET['id'];

// Query untuk menghapus berita berdasarkan ID
$query = "DELETE FROM beritas WHERE id='$id'";

// Eksekusi query hapus
if ($conn->query($query) === TRUE) {
    // Redirect ke halaman admin_dashboard.php setelah berhasil menghapus
    header("Location: admin_dashboard.php");
    exit();
} else {
    echo "Error: " . $conn->error;
}

// Menutup koneksi database
$conn->close();
?>
