<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

$query = "
    DELETE FROM mata_kuliah
    WHERE id_mk = $id
";

if (!mysqli_query($conn, $query)) {
    die("Gagal menghapus data mata kuliah: " . mysqli_error($conn));
}

header("Location: index.php");
exit;

?>