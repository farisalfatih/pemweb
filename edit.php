<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Include file koneksi database
require 'functions.php';

// Periksa peran pengguna (role)
if (isset($_SESSION['role'])) {
    // Jika peran pengguna adalah 'reader', arahkan ke reader_dashboard.php
    if ($_SESSION['role'] === 'reader') {
        $dashboard_url = 'reader_dashboard.php';
    }
    // Jika peran pengguna adalah 'admin', arahkan ke admin_dashboard.php
    elseif ($_SESSION['role'] === 'admin') {
        $dashboard_url = 'admin_dashboard.php';
    }
}

// Ambil ID berita dari parameter URL
$id = $_GET['id'];

// Query untuk mengambil data berita berdasarkan ID
$query = "SELECT * FROM beritas WHERE id='$id'";
$result = $conn->query($query);
$row = $result->fetch_assoc();

// Inisialisasi nilai-nilai yang akan ditampilkan dalam form
$title = $row['title'];
$excerpt = $row['excerpt'];
$body = $row['body'];
$gambar = $row['gambar'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Berita</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo $dashboard_url; ?>">Info Gresik</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo $dashboard_url; ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                </ul>
                <div class="ml-2">
                    <a class="btn btn-danger" href="logout.php">Log Out</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="mt-4 text-center">Edit Berita</h2>
        <form action="proses_edit.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Judul</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $title; ?>" required>
            </div>
            <div class="form-group">
                <label for="excerpt">Excerpt</label>
                <textarea class="form-control" id="excerpt" name="excerpt" rows="3" required><?php echo $excerpt; ?></textarea>
            </div>
            <div class="form-group">
                <label for="body">Isi Berita</label>
                <textarea class="form-control" id="body" name="body" rows="5" required><?php echo $body; ?></textarea>
            </div>
            <div class="form-group">
                <label for="gambar">Gambar</label>
                <input type="file" class="form-control-file" id="gambar" name="gambar">
                <small id="gambarHelp" class="form-text text-muted">Ukuran file maksimal: 2MB. Format yang diperbolehkan: JPG, JPEG, PNG.</small>
                <input type="hidden" name="gambarLama" value="<?php echo $gambar; ?>">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>

    <footer class="footer">
      <div class="container">
          <span>Info Gresik &copy; 2024</span>
      </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
