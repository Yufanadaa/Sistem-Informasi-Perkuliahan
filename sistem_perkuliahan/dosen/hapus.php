<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['nidn'])) {
    header("Location: index.php");
    exit;
}

$nidn = mysqli_real_escape_string(
    $conn,
    $_GET['nidn']
);

$query = "
    DELETE FROM dosen
    WHERE nidn = '$nidn'
";

mysqli_query($conn, $query);

header("Location: index.php");
exit;

?>