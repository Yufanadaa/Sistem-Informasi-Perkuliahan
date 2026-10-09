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

$id = mysqli_real_escape_string($conn, $_GET['id']);

$query = "SELECT * FROM mata_kuliah
          WHERE id_mk = '$id'";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit;
}

$mata_kuliah = mysqli_fetch_assoc($result);

$query_prodi = "SELECT * FROM prodi
                ORDER BY nama_prodi ASC";

$result_prodi = mysqli_query($conn, $query_prodi);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $kode_mk = mysqli_real_escape_string(
        $conn,
        $_POST['kode_mk']
    );

    $nama_mk = mysqli_real_escape_string(
        $conn,
        $_POST['nama_mk']
    );

    $sks = mysqli_real_escape_string(
        $conn,
        $_POST['sks']
    );

    $id_prodi = mysqli_real_escape_string(
        $conn,
        $_POST['id_prodi']
    );

    $query_update = "UPDATE mata_kuliah SET
                     kode_mk = '$kode_mk',
                     nama_mk = '$nama_mk',
                     sks = '$sks',
                     id_prodi = '$id_prodi'
                     WHERE id_mk = '$id'";

    if (mysqli_query($conn, $query_update)) {

        header("Location: index.php");
        exit;

    } else {

        $error = "Gagal mengubah data mata kuliah: "
               . mysqli_error($conn);

    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Mata Kuliah - Sistem Informasi Perkuliahan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>

        body {
            background-color: #93c5fd;
        }

        .form-card {
            border: 2px solid #93c5fd;
            border-radius: 15px;
            overflow: hidden;
        }

        .form-header {
            background-color: #1e3a8a;
            color: white;
            padding: 20px 25px;
        }

        .form-body {
            padding: 30px;
        }

        .btn-simpan {
            background-color: #1e3a8a;
            border-color: #1e3a8a;
            color: white;
            font-weight: 600;
        }

        .btn-simpan:hover {
            background-color: #2563eb;
            border-color: #2563eb;
            color: white;
        }

    </style>

</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark"
         style="background-color: #1e3a8a;">

        <div class="container">

            <a class="navbar-brand fw-bold"
               href="../dashboard.php">

                Sistem Informasi Perkuliahan

            </a>

            <a href="../logout.php"
               class="btn btn-light btn-sm">

                Logout

            </a>

        </div>

    </nav>


    <!-- Konten -->
    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-md-8 col-lg-7">

                <div class="card form-card shadow-sm">

                    <!-- Header -->
                    <div class="form-header">

                        <h4 class="mb-1 fw-bold">
                            Edit Data Mata Kuliah
                        </h4>

                        <p class="mb-0">
                            Silakan ubah data mata kuliah yang diperlukan.
                        </p>

                    </div>


                    <!-- Body -->
                    <div class="form-body">

                        <?php if (isset($error)) { ?>

                            <div class="alert alert-danger">
                                <?= htmlspecialchars($error); ?>
                            </div>

                        <?php } ?>


                        <form method="POST">

                            <!-- ID Mata Kuliah -->
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    ID Mata Kuliah
                                </label>

                                <input type="text"
                                       class="form-control"
                                       value="<?= htmlspecialchars($mata_kuliah['id_mk']); ?>"
                                       disabled>

                                <div class="form-text">
                                    ID mata kuliah tidak dapat diubah.
                                </div>

                            </div>


                            <!-- Kode Mata Kuliah -->
                            <div class="mb-3">

                                <label for="kode_mk"
                                       class="form-label fw-semibold">

                                    Kode Mata Kuliah

                                </label>

                                <input type="text"
                                       class="form-control"
                                       id="kode_mk"
                                       name="kode_mk"
                                       value="<?= htmlspecialchars($mata_kuliah['kode_mk']); ?>"
                                       required>

                            </div>


                            <!-- Nama Mata Kuliah -->
                            <div class="mb-3">

                                <label for="nama_mk"
                                       class="form-label fw-semibold">

                                    Nama Mata Kuliah

                                </label>

                                <input type="text"
                                       class="form-control"
                                       id="nama_mk"
                                       name="nama_mk"
                                       value="<?= htmlspecialchars($mata_kuliah['nama_mk']); ?>"
                                       required>

                            </div>


                            <!-- SKS -->
                            <div class="mb-3">

                                <label for="sks"
                                       class="form-label fw-semibold">

                                    SKS

                                </label>

                                <input type="number"
                                       class="form-control"
                                       id="sks"
                                       name="sks"
                                       min="1"
                                       max="6"
                                       value="<?= htmlspecialchars($mata_kuliah['sks']); ?>"
                                       required>

                            </div>


                            <!-- Program Studi -->
                            <div class="mb-4">

                                <label for="id_prodi"
                                       class="form-label fw-semibold">

                                    Program Studi

                                </label>

                                <select class="form-select"
                                        id="id_prodi"
                                        name="id_prodi"
                                        required>

                                    <?php while ($prodi = mysqli_fetch_assoc($result_prodi)) { ?>

                                        <option value="<?= $prodi['id_prodi']; ?>"
                                            <?= $mata_kuliah['id_prodi'] == $prodi['id_prodi'] ? 'selected' : ''; ?>>

                                            <?= htmlspecialchars($prodi['nama_prodi']); ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>


                            <!-- Tombol -->
                            <div class="d-flex justify-content-between">

                                <a href="index.php"
                                   class="btn btn-outline-secondary">

                                    Kembali

                                </a>

                                <button type="submit"
                                        class="btn btn-simpan px-4">

                                    Simpan Perubahan

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>