<?php
    session_start();

    require 'functions.php';

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: login.php");
        exit();
    }

    // Mengambil nama pengguna dari database berdasarkan ID pengguna yang masuk saat ini
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $user = getUserById($user_id);
        $user_name = $user['nama']; 
    }

    // Periksa peran pengguna (role)
    if (isset($_SESSION['role'])) {
        // Jika peran pengguna adalah 'reader', arahkan ke index.php
        if ($_SESSION['role'] === 'reader') {
            $dashboard_url = 'index.php';
        }
        // Jika peran pengguna adalah 'admin', arahkan ke admin_dashboard.php
        elseif ($_SESSION['role'] === 'admin') {
            $dashboard_url = 'admin_dashboard.php';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Gresik | Tambah Berita</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #2c2c2c;
            color: #efefef;
            font-family: 'Arial', sans-serif;
            margin-bottom: 60px;
        }
        .navbar {
            background-color: #343a40;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-brand,
        .navbar-nav .nav-link {
            color: #ffffff;
        }
        .navbar-nav .nav-link:hover {
            color: #dee2e6;
        }
        .navbar-toggler-icon {
            border-color: #ffffff;
        }
        .form-control {
            background-color: #555;
            color: #ffffff;
            border-color: #555;
        }
        .btn-outline-success {
            color: #28a745;
            border-color: #28a745;
        }
        .btn-outline-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .footer {
            background-color: #343a40;
            color: #ffffff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand mr-4" href="<?php echo $dashboard_url; ?>">Info Gresik</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $dashboard_url; ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <!-- Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            User Actions
                        </a>
                        <div class="dropdown-menu bg-dark" aria-labelledby="navbarDropdown">
                            <?php if (isset($_SESSION['role'])): ?>
                                <a class="dropdown-item text-white" href="edit_profile.php"><?php echo $user_name; ?></a>
                                <div class="dropdown-divider bg-white"></div>
                                <a class="dropdown-item font-weight-bold text-danger" href="logout.php">Log Out</a>
                            <?php else: ?>
                                <a class="dropdown-item text-success font-weight-bold" href="login.php">Log In</a>
                            <?php endif; ?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="mt-4 text-center">Tambah Berita Baru</h2>
        <form action="proses_tambah_berita.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Judul</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="excerpt">Excerpt</label>
                <textarea class="form-control" id="excerpt" name="excerpt" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="body">Isi Berita</label>
                <textarea class="form-control" id="body" name="body" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="gambar">Gambar</label>
                <input type="file" class="form-control-file" id="gambar" name="gambar" required>
                <small id="gambarHelp" class="form-text text-muted">Ukuran file maksimal: 2MB. Format yang diperbolehkan: JPG, JPEG, PNG.</small>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Berita</button>
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