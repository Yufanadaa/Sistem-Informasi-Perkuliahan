<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['nim'])) {
    header("Location: index.php");
    exit;
}

$nim = mysqli_real_escape_string(
    $conn,
    $_GET['nim']
);

$query = "
    DELETE FROM mahasiswa
    WHERE nim = '$nim'
";

mysqli_query($conn, $query);

header("Location: index.php");
exit;

?>