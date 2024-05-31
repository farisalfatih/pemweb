<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'reader') {
    header("Location: login.php");
    exit();
}
?>

<?php
    // Memanggil file functions.php yang berisi fungsi-fungsi terkait database
    require 'functions.php';

    // Mengambil data berita dari database
    $berita = query('SELECT * FROM beritas');

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
    <title>Info Gresik</title>
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
                        <a class="nav-link active" href="reader_dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                </ul>
                <!-- Form pencarian berita -->
                <form class="form-inline my-2 my-lg-0" method="post">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="keyword"> <!-- Tambahkan atribut name untuk mendapatkan nilai di dalam PHP -->
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" name="cari">Search</button> <!-- Tambahkan atribut name untuk tombol submit -->
                </form>
                <div class="ml-2">
                    <a class="btn btn-danger" href="logout.php">Log Out</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="container-2">
            <?php $i = 1; ?>
            <?php foreach ($berita as $row) : ?>
                <a href="berita.php?slug=<?php echo $row['slug']; ?>" class="card text-end">
                    <img src="<?php echo $row['gambar']; ?>" class="card-img-top card-img" alt="Contoh Gambar">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['title']; ?></h5>
                        <p class="card-text"><?php echo $row['excerpt']; ?></p>
                        <p class="card-text">
                            <small class="text-body-secondary"><?php echo $row['created_at']; ?></small>
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