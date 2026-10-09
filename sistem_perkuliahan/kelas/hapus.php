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

$query = "DELETE FROM kelas WHERE id_kelas = '$id'";

if (mysqli_query($conn, $query)) {

    header("Location: index.php");
    exit;

} else {

    echo "Data kelas tidak dapat dihapus.<br>";
    echo "Kemungkinan kelas masih digunakan pada KRS.<br><br>";
    echo "Error: " . mysqli_error($conn);

}

?>