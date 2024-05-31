<?php
require 'functions.php';

// Mendefinisikan folder tempat menyimpan gambar
$target_dir = "img/";

// Mendapatkan nilai-nilai yang dikirimkan dari formulir
$title = $_POST['title'];
$excerpt = $_POST['excerpt'];
$body = $_POST['body'];
$gambar = $_FILES['gambar']['name']; // Nama file gambar yang diupload
$gambar_tmp = $_FILES['gambar']['tmp_name']; // Lokasi sementara file gambar yang diupload

// Membuat slug dari judul berita
$slug = strtolower(str_replace(' ', '-', $title));

// Mengambil ekstensi file gambar yang diupload
$imageFileType = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));

// Inisialisasi variabel $uploadOk
$uploadOk = 1;

// Memeriksa apakah file yang diupload adalah gambar
if(isset($_POST["submit"])) {
    $check = getimagesize($gambar_tmp);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Memeriksa apakah file gambar sudah ada atau tidak
if (file_exists($target_dir . $gambar)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Memeriksa ukuran file gambar
if ($_FILES["gambar"]["size"] > 2000000) { // 2MB
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Memeriksa tipe file gambar
if($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
    echo "Sorry, only JPG, JPEG, PNG files are allowed.";
    $uploadOk = 0;
}

// Jika semua kondisi terpenuhi, maka upload gambar
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    if (move_uploaded_file($gambar_tmp, $target_dir . $slug . '.' . $imageFileType)) {
        $gambar_path = $target_dir . $slug . '.' . $imageFileType;
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Memasukkan data berita ke dalam database
$sql = "INSERT INTO beritas (title, slug, excerpt, body, gambar) VALUES ('$title', '$slug', '$excerpt', '$body', '$gambar_path')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
        alert('Berita berhasil ditambahkan.');
        window.location.href = 'admin_dashboard.php?success=true';
    </script>";
    exit(); // Pastikan kode selanjutnya tidak dijalankan setelah header
} else {
    echo "Gagal menambahkan berita: " . $conn->error;
}

// Menutup koneksi database
$conn->close();
?>
