<?php
$conn = mysqli_connect("localhost", "root", "", "pemweb");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $rows = [];

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $rows[] = $row;
    }

    return $rows;
}

function cari($keyword)
{
    $query = "SELECT * FROM beritas
                    WHERE
                    title LIKE '%$keyword%' OR
                    created_at LIKE '%$keyword%'
    ";
    return query($query);
}

function hapus_berita($id) {
    global $conn;

    // Query untuk mendapatkan nama file gambar yang terkait dengan berita
    $querySelect = "SELECT gambar FROM beritas WHERE id = ?";
    if ($stmtSelect = $conn->prepare($querySelect)) {
        $stmtSelect->bind_param("i", $id);
        $stmtSelect->execute();
        $stmtSelect->bind_result($gambar);
        $stmtSelect->fetch();
        $stmtSelect->close();
    } else {
        return false; // Terjadi kesalahan saat menyiapkan statement select
    }

    // Query untuk menghapus berita berdasarkan ID
    $queryDelete = "DELETE FROM beritas WHERE id = ?";
    if ($stmtDelete = $conn->prepare($queryDelete)) {
        $stmtDelete->bind_param("i", $id);

        // Eksekusi statement
        if ($stmtDelete->execute()) {
            $stmtDelete->close();

            // Hapus gambar dari sistem file jika ada
            if ($gambar && file_exists($gambar)) {
                unlink($gambar);
            }

            return true; // Penghapusan berhasil
        } else {
            $stmtDelete->close();
            return false; // Terjadi kesalahan saat menghapus
        }
    } else {
        return false; // Terjadi kesalahan saat menyiapkan statement delete
    }
}

// Fungsi untuk mengupload gambar
function upload($existingFileName = null) {
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // Cek apakah tidak ada gambar yang diupload
    if ($error === 4) {
        return $existingFileName;
    }

    // Cek apakah yang diupload adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>alert('Yang anda upload bukan gambar!');</script>";
        return false;
    }

    // Cek jika ukurannya terlalu besar
    if ($ukuranFile > 2000000) {
        echo "<script>alert('Ukuran gambar terlalu besar!');</script>";
        return false;
    }

    // Jika ada nama file yang sudah ada (gambar lama), gunakan nama tersebut
    $namaFileBaru = $existingFileName ? basename($existingFileName) : uniqid() . '.' . $ekstensiGambar;

    // Pastikan folder img ada
    if (!is_dir('img')) {
        mkdir('img', 0777, true);
    }

    // Tentukan path tujuan file
    $destination = 'img/' . $namaFileBaru;

    // Debug: cek path tujuan
    error_log("Destination path: " . $destination);

    // Pindahkan file gambar ke folder img
    if (!move_uploaded_file($tmpName, $destination)) {
        error_log("Failed to move uploaded file: " . $tmpName . " to " . $destination);
        echo "<script>alert('Gagal mengupload gambar!');</script>";
        return false;
    }

    return $namaFileBaru;
}

// Fungsi untuk mengubah data berita
function updateBerita($data) {
    global $conn;
    $id = $data["id"];
    $title = htmlspecialchars($data["title"]);
    $excerpt = htmlspecialchars($data["excerpt"]);
    $body = htmlspecialchars($data["body"]);
    $gambarLama = htmlspecialchars($data["gambarLama"]);

    // Cek apakah user memilih gambar baru atau tidak
    if ($_FILES['gambar']['error'] === 4) {
        $gambar = $gambarLama;
    } else {
        // Hapus gambar lama jika ada gambar baru dan gambar lama ada
        if ($gambarLama && file_exists($gambarLama)) {
            unlink($gambarLama);
        }

        // Unggah gambar baru dengan nama file yang sama atau baru jika tidak ada gambar lama
        $gambar = 'img/' . upload($gambarLama ? basename($gambarLama) : null);
        if (!$gambar) {
            return false;
        }
    }

    // Update data berita
    $query = "UPDATE beritas SET 
                title = '$title',
                excerpt = '$excerpt',
                body = '$body',
                gambar = '$gambar'
            WHERE id = $id";

    if (!mysqli_query($conn, $query)) {
        echo "<script>alert('Gagal memperbarui berita!');</script>";
        return false;
    }

    return mysqli_affected_rows($conn);
}



function addComment($article_id, $username, $comment) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO comments (article_id, username, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $article_id, $username, $comment);
    return $stmt->execute();
}

function getComments($article_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM comments WHERE article_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    return $stmt->get_result();
}

function getUserById($user_id) {
    global $conn;

    $sql = "SELECT * FROM users WHERE id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        return $user;
    } else {
        return null;
    }

    $conn->close();
}

function updateProfile($user_id, $nama, $email, $password) {
    global $conn;

    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    $nama = mysqli_real_escape_string($conn, $nama);
    $email = mysqli_real_escape_string($conn, $email);
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "UPDATE users SET nama='$nama', email='$email', password='$hashed_password' WHERE id=$user_id";

    if (mysqli_query($conn, $sql)) {
        mysqli_close($conn);
        return true;
    } else {
        mysqli_close($conn);
        return false;
    }
}

function validateProfileUpdate($nama, $email, $password) {
    $errors = array();

    if (empty($nama)) {
        $errors[] = "Nama tidak boleh kosong";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid";
    }

    if (empty($password)) {
        $errors[] = "Password tidak boleh kosong";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password harus terdiri dari minimal 6 karakter";
    }

    return $errors;
}

function signup($nama, $email, $password, $role) {
    global $conn;
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "<script>alert('Akun berhasil ditambahkan');</script>";
        header("Refresh: 1; URL=login.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

function login($email, $password) {
    global $conn;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Email tidak valid.'); window.location.href = 'login.php';</script>";
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            echo "<script>alert('Log In Gagal.'); window.location.href = 'login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Log In Gagal.'); window.location.href = 'login.php';</script>";
        exit();
    }

    $stmt->close();
}
?>
