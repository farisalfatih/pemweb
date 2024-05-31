<?php
// Mulai sesi jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'functions.php';

// Mendapatkan nilai slug dari URL
$slug = $_GET["slug"];

// Mengambil data berita berdasarkan slug dari database
$stmt = $conn->prepare("SELECT * FROM beritas WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

// Pengecekan apakah data ditemukan
if ($result->num_rows > 0) {
    $berita = $result->fetch_assoc();
} else {
    echo "Article not found.";
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
} else {
    // Jika tidak ada peran pengguna yang ditetapkan, arahkan ke halaman login
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($berita['title']); ?></title>
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

  <div class="container mt-4 bg-dark p-4 rounded">
    <div class="row">
        <div class="col-md-8">
            <h1><?php echo htmlspecialchars($berita['title']); ?></h1>
            <p class="text-muted">Published on: <?php echo $berita['created_at']; ?></p>
            <img src="<?php echo htmlspecialchars($berita['gambar']); ?>" class="img-fluid mb-3" alt="<?php echo htmlspecialchars($berita['title']); ?>">
            <div>
              <?php echo htmlspecialchars_decode($berita['body']); ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-secondary p-3 rounded">
                <h4>Comments</h4>
                <!-- Tempat untuk menampilkan komentar -->
                <!-- Contoh: -->
                <div class="media mb-3">
                    <div class="media-body">
                        <h5 class="mt-0">John Doe</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                </div>
                <div class="media mb-3">
                    <div class="media-body">
                        <h5 class="mt-0">Jane Doe</h5>
                        <p>Integer eu magna nec elit convallis eleifend.</p>
                    </div>
                </div>
                <!-- Form untuk menambahkan komentar baru -->
                <form>
                    <div class="form-group">
                        <label for="commentText">Your Comment</label>
                        <textarea class="form-control" id="commentText" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
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
