<?php
// Include file koneksi database
require 'functions.php';

// Ambil ID berita dari parameter URL
$id = $_GET['id'];

// Query untuk mengambil data berita berdasarkan ID
$query = "SELECT * FROM beritas WHERE id='$id'";
$result = $conn->query($query);
$row = $result->fetch_assoc();

// Fungsi konfirmasi di JavaScript
echo "<script>
    var confirmation = confirm('Apakah Anda yakin ingin menghapus berita ini?');
    if (confirmation) {
        window.location.href = 'hapus_confirmed.php?id=$id'; // Jika konfirmasi, arahkan ke hapus_confirmed.php
    } else {
        window.location.href = 'admin_dashboard.php'; // Jika tidak, arahkan kembali ke halaman admin_dashboard.php
    }
</script>";
?>
