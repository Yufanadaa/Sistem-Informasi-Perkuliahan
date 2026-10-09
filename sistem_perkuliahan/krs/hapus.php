<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$id = mysqli_real_escape_string($conn, $id);

$query = "DELETE FROM krs
          WHERE id_krs = '$id'";

if (mysqli_query($conn, $query)) {

    header("Location: index.php");
    exit;

} else {

    echo "Gagal menghapus data KRS.<br>";
    echo "Error: " . mysqli_error($conn);

}

?>