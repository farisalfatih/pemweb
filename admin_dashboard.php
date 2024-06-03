<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Periksa peran pengguna (role)
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'reader') {
        $dashboard_url = 'index.php';
    } elseif ($_SESSION['role'] === 'admin') {
        $dashboard_url = 'admin_dashboard.php';
    }
}

// Memanggil file functions.php yang berisi fungsi-fungsi terkait database
require 'functions.php';

// Mengambil data berita dari database
$berita = query('SELECT * FROM beritas');

// Mengambil nama pengguna dari database berdasarkan ID pengguna yang masuk saat ini
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user = getUserById($user_id);
    $user_name = $user['nama'];
}

// Memproses pencarian berita jika tombol cari ditekan
if (isset($_POST["cari"])) {
    $keyword = $_POST["keyword"];
    $berita = cari($keyword);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Gresik | Admin</title>
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
        .card {
            background-color: #3a3a3a;
            border: 1px solid #555;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
            text-decoration: none;
        }
        .card:hover {
            transform: translateY(-10px);
        }
        .card-title, .card-text {
            color: #efefef;
        }
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .btn-group .btn {
            margin-right: 5px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #343a40;
            color: #ffffff;
            text-align: center;
            padding: 10px 0;
            z-index: 100;
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
                <form class="form-inline my-2 my-lg-0 mr-3" method="post">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="keyword">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" name="cari">Search</button>
                </form>
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
                <a class="btn btn-success mr-2" href="tambah_berita.php">Tambah Berita</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <?php
                usort($berita, function($a, $b) {
                    return strtotime($b['created_at']) - strtotime($a['created_at']);
                });

                $i = 1;
                foreach ($berita as $row) :
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card text-end h-100">
                        <img src="<?php echo $row['gambar']; ?>" class="card-img-top" alt="Contoh Gambar">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo $row['title']; ?></h5>
                            <p class="card-text"><?php echo $row['excerpt']; ?><a href="berita.php?slug=<?php echo $row['slug']; ?>"> Baca selengkapnya..</a></p>
                            <p class="card-text mt-auto">
                                <small class="text-body-secondary"><?php echo $row['created_at']; ?></small>
                            </p>
                            <div class="btn-group mt-3" role="group" aria-label="Basic example">
                                <a href="edit_berita.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a>
                                <a href="hapus_berita.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick='return confirmDelete()'>Hapus</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php $i++; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <span>Info Gresik &copy; 2024</span>
        </div>
    </footer>

    <script>
        function confirmDelete() {
            return confirm('Apakah Anda yakin ingin menghapus berita ini?');
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

