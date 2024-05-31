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