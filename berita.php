<?php
    // Mulai sesi jika belum dimulai
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require 'functions.php';

    // Mendapatkan nilai slug dari URL
    $slug = $_GET["slug"];

    // Cek koneksi
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Mengambil nama pengguna dari database berdasarkan ID pengguna yang masuk saat ini
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $user = getUserById($user_id);
        $user_name = $user['nama']; 
    }

    // Siapkan pernyataan SQL
    $stmt = $conn->prepare("SELECT * FROM beritas WHERE slug = ?");
    if (!$stmt) {
        die("Prepare statement failed: " . $conn->error);
    }
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

    // Mengambil komentar terkait artikel
    $comments_result = getComments($berita['id']);

    // Proses penambahan komentar
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_comment'])) {
        // Pastikan pengguna telah login
        if (!isset($_SESSION['email'])) {
            echo "<script>alert('You must be logged in to comment.'); window.location.href='login.php';</script>";
            exit();
        }

        // Mengambil data dari POST request
        $username = $_SESSION['nama']; // Ambil nama pengguna dari sesi
        $comment = htmlspecialchars($_POST['comment']);
        $article_id = $berita['id'];

        if (addComment($article_id, $username, $comment)) {
            echo "<script>
                alert('Komentar berhasil ditambahkan!');
                window.location.href = 'berita.php?slug=$slug';
            </script>";
            exit();
        } else {
            echo "Gagal menambahkan komentar: " . $conn->error;
        }
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($berita['title']); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .news-container {
            background-color: #212529;
            padding: 30px;
            border-radius: 10px;
            color: #ffffff;
            margin-top: 20px;
        }
        .news-title {
            color: #f8f9fa;
        }
        .news-meta {
            font-size: 0.9em;
            color: #ced4da;
        }
        .news-body {
            color: #e9ecef;
        }
        .comment-section {
            background-color: #343a40;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .comment-item {
            background-color: #495057;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .comment-item h6 {
            color: #ffffff;
        }
        .comment-item p {
            color: #adb5bd;
        }
        .comment-form label {
            color: #ffffff;
        }
        .comment-form textarea {
            resize: none;
        }
        .comment-form button {
            margin-top: 10px;
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
        <div class="news-container">
            <h1 class="news-title"><?php echo htmlspecialchars($berita['title']); ?></h1>
            <p class="news-meta">Published on: <?php echo $berita['created_at']; ?></p>
            <img src="<?php echo htmlspecialchars($berita['gambar']); ?>" class="img-fluid mb-3" alt="<?php echo htmlspecialchars($berita['title']); ?>">
            <div class="news-body">
                <?php echo htmlspecialchars_decode($berita['body']); ?>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="comment-section">
            <h4 class="text-white">Komentar</h4>
            <!-- Tempat untuk menampilkan komentar -->
            <?php while($comment = $comments_result->fetch_assoc()): ?>
                <div class="media mb-3 comment-item">
                    <div class="media-body">
                        <h6 class="mt-0"><?php echo htmlspecialchars($comment['username']); ?></h6>
                        <p class="text-muted"><?php echo $comment['created_at']; ?></p>
                        <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>

            <!-- Form untuk menambahkan komentar baru -->
            <?php if (isset($_SESSION['email'])): ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?slug=<?php echo $slug; ?>" method="post" class="comment-form">
                    <div class="form-group">
                        <label for="commentText" class="text-white">Komen Disini</label>
                        <textarea class="form-control" id="commentText" name="comment" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit_comment">Kirim</button>
                </form>
            <?php else: ?>
                <p>Anda harus <a href="login.php">login</a> untuk menambahkan komentar.</p>
            <?php endif; ?>
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