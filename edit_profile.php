<?php
    session_start();
    require 'functions.php';

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

    // Inisialisasi variabel $user_id jika sudah login
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Mengambil data pengguna berdasarkan ID
    $user = ($user_id) ? getUserById($user_id) : null;

    // Memanggil fungsi validateProfileUpdate jika ada data yang dikirimkan melalui POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $errors = validateProfileUpdate($_POST['nama'], $_POST['email'], $_POST['password']);

        // Memeriksa apakah ada kesalahan validasi
        if (empty($errors)) {
            // Jika tidak ada kesalahan, lakukan pembaruan profil pengguna
            $nama = $_POST['nama'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            // Panggil fungsi updateProfile untuk melakukan pembaruan
            if (updateProfile($user_id, $nama, $email, $password)) {
                // Jika pembaruan berhasil, tampilkan pemberitahuan menggunakan JavaScript
                echo "<script>alert('Profil berhasil diperbarui'); window.location.href = '$dashboard_url';</script>";
            } else {
                // Jika terjadi kesalahan saat melakukan pembaruan, tampilkan pesan kesalahan
                echo "<script>alert('Gagal memperbarui profil. Silakan coba lagi.'); window.location.href = '$dashboard_url';</script>";
            }
        } else {
            // Jika terdapat kesalahan validasi, tampilkan pesan kesalahan kepada pengguna
            foreach ($errors as $error) {
                echo "<script>alert('$error');</script>";
            }
            // Setelah menampilkan pesan kesalahan, alihkan pengguna ke halaman utama
            echo "<script>window.location.href = '$dashboard_url';</script>";
        }
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Gresik | Edit Profil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
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
                        <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
        <h2 class="mt-4 text-center">Edit Profil</h2>
        <?php if (!empty($notification)) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo $notification; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $user['nama']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru">
            </div>
            <!-- Tambahan informasi profil lainnya sesuai kebutuhan -->
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
