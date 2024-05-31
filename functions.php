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

// Fungsi untuk mengupload gambar
function upload() {
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // Cek apakah tidak ada gambar yang diupload
    if ($error === 4) {
        echo "<script>alert('Pilih gambar terlebih dahulu!');</script>";
        return false;
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

    // Lolos pengecekan, gambar siap diupload
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;

    move_uploaded_file($tmpName, 'img/' . $namaFileBaru);

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

    // Cek apakah user pilih gambar baru atau tidak
    if ($_FILES['gambar']['error'] === 4) {
        $gambar = $gambarLama;
    } else {
        $gambar = upload();
        if (!$gambar) {
            return false;
        }
    }

    $query = "UPDATE beritas SET 
                title = '$title',
                excerpt = '$excerpt',
                body = '$body',
                gambar = '$gambar'
              WHERE id = $id";

    mysqli_query($conn, $query);

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
?>