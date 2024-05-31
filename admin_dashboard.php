<?php
    session_start();

    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: login.php");
        exit();
    }

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

<script>
    // Ambil nilai dari URL parameter success
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');

    // Jika parameter success bernilai true, tampilkan alert
    if (success) {
        alert("Berita berhasil ditambahkan!");
    }
</script>

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
                        <a class="nav-link active" href="<?php echo $dashboard_url; ?>">Home</a>
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
                    <a class="btn btn-success" href="tambah.php">Tambah Berita</a>
                </div>
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
                <div class="card text-end">
                    <img src="<?php echo $row['gambar']; ?>" class="card-img-top card-img" alt="Contoh Gambar">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['title']; ?></h5>
                        <p class="card-text"><?php echo $row['excerpt']; ?></p>
                        <p class="card-text">
                            <small class="text-body-secondary"><?php echo $row['created_at']; ?></small>
                        </p>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="edit_berita.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a>
                            <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Hapus</a>
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>