<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";

$query_prodi = "SELECT * FROM prodi ORDER BY nama_prodi ASC";
$result_prodi = mysqli_query($conn, $query_prodi);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $nama_mahasiswa = mysqli_real_escape_string($conn, $_POST['nama_mahasiswa']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $id_prodi = mysqli_real_escape_string($conn, $_POST['id_prodi']);

    $query = "INSERT INTO mahasiswa
              (nim, nama_mahasiswa, jenis_kelamin, tanggal_lahir, id_prodi)
              VALUES
              ('$nim', '$nama_mahasiswa', '$jenis_kelamin', '$tanggal_lahir', '$id_prodi')";

    if (mysqli_query($conn, $query)) {

        header("Location: index.php");
        exit;

    } else {

        $error = "Gagal menambahkan data mahasiswa: " . mysqli_error($conn);

    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Tambah Mahasiswa - Sistem Informasi Perkuliahan</title>

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
                            Tambah Data Mahasiswa
                        </h4>

                        <p class="mb-0">
                            Silakan isi data mahasiswa dengan lengkap.
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

                            <!-- NIM -->
                            <div class="mb-3">

                                <label for="nim"
                                       class="form-label fw-semibold">

                                    NIM

                                </label>

                                <input type="text"
                                       class="form-control"
                                       id="nim"
                                       name="nim"
                                       placeholder="Masukkan NIM"
                                       required>

                            </div>


                            <!-- Nama -->
                            <div class="mb-3">

                                <label for="nama_mahasiswa"
                                       class="form-label fw-semibold">

                                    Nama Mahasiswa

                                </label>

                                <input type="text"
                                       class="form-control"
                                       id="nama_mahasiswa"
                                       name="nama_mahasiswa"
                                       placeholder="Masukkan nama mahasiswa"
                                       required>

                            </div>


                            <!-- Jenis Kelamin -->
                            <div class="mb-3">

                                <label for="jenis_kelamin"
                                       class="form-label fw-semibold">

                                    Jenis Kelamin

                                </label>

                                <select class="form-select"
                                        id="jenis_kelamin"
                                        name="jenis_kelamin"
                                        required>

                                    <option value="">
                                        -- Pilih Jenis Kelamin --
                                    </option>

                                    <option value="L">
                                        Laki-laki
                                    </option>

                                    <option value="P">
                                        Perempuan
                                    </option>

                                </select>

                            </div>


                            <!-- Tanggal Lahir -->
                            <div class="mb-3">

                                <label for="tanggal_lahir"
                                       class="form-label fw-semibold">

                                    Tanggal Lahir

                                </label>

                                <input type="date"
                                       class="form-control"
                                       id="tanggal_lahir"
                                       name="tanggal_lahir">

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

                                    <option value="">
                                        -- Pilih Program Studi --
                                    </option>

                                    <?php while ($prodi = mysqli_fetch_assoc($result_prodi)) { ?>

                                        <option value="<?= $prodi['id_prodi']; ?>">

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

                                    Simpan

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