<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Include file koneksi database
require 'functions.php';

// Ambil ID berita dari parameter URL
$id = $_GET['id'];

// Query untuk mengambil data berita berdasarkan ID
$query = "SELECT * FROM beritas WHERE id='$id'";
$result = $conn->query($query);
$row = $result->fetch_assoc();

if (isset($_POST['submit'])) {
    if (updateBerita(array_merge($_POST, ['id' => $id, 'gambarLama' => $row['gambar']]))) {
        echo "<script>
            alert('Berita berhasil diubah!');
            window.location.href = 'admin_dashboard.php?success=true';
        </script>";
        exit();
    } else {
        echo "<script>alert('Berita gagal diubah!');</script>";
    }
}

// Menutup koneksi database
$conn->close();
?>
