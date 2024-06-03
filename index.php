<?php
    session_start();

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

    // Periksa peran pengguna (role) jika sesi ada
    if (isset($_SESSION['role'])) {
        // Jika peran pengguna adalah 'reader', arahkan ke index.php
        if ($_SESSION['role'] === 'reader') {
            $dashboard_url = 'index.php';
        }
        // Jika peran pengguna adalah 'admin', arahkan ke admin_dashboard.php
        elseif ($_SESSION['role'] === 'admin') {
            $dashboard_url = 'admin_dashboard.php';
        }
    } else {
        // Jika tidak ada sesi, arahkan ke halaman utama (home)
        $dashboard_url = 'index.php';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Gresik</title>
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
        .btn-group .btn {
            margin-right: 5px;
        }
        .container-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .card {
            transition: transform 0.3s ease;
            text-decoration: none;
            color: inherit;
        }
        .card:hover {
            transform: translateY(-10px);
        }
        .card-img {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            max-height: 200px;
            object-fit: cover;
        }
        .card-body {
            background-color: #3a3a3a;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
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

    <div class="container mt-4">
        <div class="container-2">
            <?php
                // Mengurutkan berita berdasarkan tanggal pembuatan secara descending
                usort($berita, function($a, $b) {
                    return strtotime($b['created_at']) - strtotime($a['created_at']);
                });

                $i = 1;
                foreach ($berita as $row) :
            ?>
                <a href="berita.php?slug=<?php echo $row['slug']; ?>" class="card">
                    <img src="<?php echo $row['gambar']; ?>" class="card-img-top card-img" alt="Contoh Gambar">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['title']; ?></h5>
                        <p class="card-text"><?php echo $row['excerpt']; ?></p>
                        <p class="card-text">
                            <small class="text-muted"><?php echo $row['created_at']; ?></small>
                        </p>
                    </div>
                </a>
            <?php $i++; ?>
            <?php endforeach; ?>
        </div>
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

