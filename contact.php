<?php
// Memulai sesi
session_start();

// Memanggil file functions.php yang berisi fungsi-fungsi terkait database
require 'functions.php';

// Mengambil data berita dari database
$berita = query('SELECT * FROM beritas');

// Memproses pencarian berita jika tombol cari ditekan
if (isset($_POST["cari"])) {
    $keyword = $_POST["keyword"];
    $berita = cari($keyword);
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
} else {
    $dashboard_url = 'index.php';
}

// Mengambil nama pengguna dari database berdasarkan ID pengguna yang masuk saat ini
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user = getUserById($user_id);
    $user_name = $user['nama']; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Gresik | About</title>
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
        .contact-section {
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .contact-section img {
            border-radius: 10px;
            width: 100%;
        }
        .contact-section h2,
        .contact-section p {
            color: #efefef;
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
                    <li class="nav-item active">
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
            </div>
        </div>
    </nav>

    <div class="container my-4 text-center">
        <div class="row contact-section">
            <div class="col-md-3">
                <img src="https://scontent.fmlg11-1.fna.fbcdn.net/v/t39.30808-6/405713625_1338979676738991_247929838340842341_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=5f2048&_nc_eui2=AeGan2C5AflCl6m0C2TF68hrzyeVkUaMSCPPJ5WRRoxIIwDFgTYqD-DiGWNBVdYs8NTwqblIR-IKgmwpApMy9IKV&_nc_ohc=rFwKqDqqAskQ7kNvgFKABGR&_nc_ht=scontent.fmlg11-1.fna&oh=00_AYCyGtxb5QOSTWrRFSXznmOavjtKSmSKXdCaj5ZFk5VqJQ&oe=665F63EA" alt="Mohammad Faris Al Fatih" class="img-fluid">
            </div>
            <div class="col-md-9 d-flex align-items-center">
                <div>
                    <h2>Mohammad Faris Al Fatih</h2>
                    <p>22081010277</p>
                    <p>"Pemrograman adalah seni mengubah ide menjadi realitas digital. Seperti seorang seniman menciptakan karya seni dari kanvas kosong, seorang programmer menciptakan aplikasi dan sistem yang memperkaya pengalaman manusia di era digital ini."</p>
                    <p>Email: <a href="mailto:22081010277@student.upnjatim.ac.id">22081010277@student.upnjatim.ac.id</a></p>
                </div>
            </div>
        </div>
        <div class="row contact-section">
            <div class="col-md-3">
                <img src="https://scontent.fmlg11-1.fna.fbcdn.net/v/t39.30808-6/405713625_1338979676738991_247929838340842341_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=5f2048&_nc_eui2=AeGan2C5AflCl6m0C2TF68hrzyeVkUaMSCPPJ5WRRoxIIwDFgTYqD-DiGWNBVdYs8NTwqblIR-IKgmwpApMy9IKV&_nc_ohc=rFwKqDqqAskQ7kNvgFKABGR&_nc_ht=scontent.fmlg11-1.fna&oh=00_AYCyGtxb5QOSTWrRFSXznmOavjtKSmSKXdCaj5ZFk5VqJQ&oe=665F63EA" alt="Mohammad Isnan" class="img-fluid">
            </div>
            <div class="col-md-9 d-flex align-items-center">
                <div>
                    <h2>Mohammad Isnan</h2>
                    <p>22081010007</p>
                    <p>"Dalam dunia pemrograman, kesabaran adalah kuncinya. Terkadang, menghadapi bug dan kesalahan kode bisa menjadi tantangan yang membuat frustrasi. Namun, dengan ketekunan dan kesabaran, setiap masalah dapat dipecahkan dan setiap program dapat dihasilkan."</p>
                    <p>Email: <a href="mailto:22081010007@student.upnjatim.ac.id">22081010007@student.upnjatim.ac.id</a></p>
                </div>
            </div>
        </div>
        <div class="row contact-section border-bottom">
            <div class="col-md-3">
                <img src="https://scontent.fmlg11-1.fna.fbcdn.net/v/t39.30808-6/405713625_1338979676738991_247929838340842341_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=5f2048&_nc_eui2=AeGan2C5AflCl6m0C2TF68hrzyeVkUaMSCPPJ5WRRoxIIwDFgTYqD-DiGWNBVdYs8NTwqblIR-IKgmwpApMy9IKV&_nc_ohc=rFwKqDqqAskQ7kNvgFKABGR&_nc_ht=scontent.fmlg11-1.fna&oh=00_AYCyGtxb5QOSTWrRFSXznmOavjtKSmSKXdCaj5ZFk5VqJQ&oe=665F63EA" alt="Yogi Prasetyo" class="img-fluid">
            </div>
            <div class="col-md-9 d-flex align-items-center">
                <div>
                    <h2>Yogi Prasetyo</h2>
                    <p>22081010297</p>
                    <p>"Pemrograman bukanlah hanya tentang menulis kode, tetapi juga tentang memecahkan masalah. Kemampuan untuk berpikir secara kreatif dan logis adalah kunci utama dalam menghadapi tantangan pemrograman. Setiap baris kode adalah langkah menuju solusi yang lebih baik."</p>
                    <p>Email: <a href="mailto:22081010297@student.upnjatim.ac.id">22081010297@student.upnjatim.ac.id</a></p>
                </div>
            </div>
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
