<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";

$query_mk = "SELECT * FROM mata_kuliah
             ORDER BY kode_mk ASC";

$result_mk = mysqli_query($conn, $query_mk);

$query_dosen = "SELECT * FROM dosen
                ORDER BY nama_dosen ASC";

$result_dosen = mysqli_query($conn, $query_dosen);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_mk = mysqli_real_escape_string(
        $conn,
        $_POST['id_mk']
    );

    $nidn = mysqli_real_escape_string(
        $conn,
        $_POST['nidn']
    );

    $semester = mysqli_real_escape_string(
        $conn,
        $_POST['semester']
    );

    $tahun_ajaran = mysqli_real_escape_string(
        $conn,
        $_POST['tahun_ajaran']
    );

    $ruang = mysqli_real_escape_string(
        $conn,
        $_POST['ruang']
    );

    $query = "INSERT INTO kelas
              (id_mk, nidn, semester, tahun_ajaran, ruang)
              VALUES
              ('$id_mk', '$nidn', '$semester',
               '$tahun_ajaran', '$ruang')";

    if (mysqli_query($conn, $query)) {

        header("Location: index.php");
        exit;

    } else {

        $error = "Gagal menambahkan data kelas: "
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

    <title>Tambah Kelas - Sistem Informasi Perkuliahan</title>

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
                            Tambah Data Kelas
                        </h4>

                        <p class="mb-0">
                            Silakan isi data kelas dengan lengkap.
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

                            <!-- Mata Kuliah -->
                            <div class="mb-3">

                                <label for="id_mk"
                                       class="form-label fw-semibold">

                                    Mata Kuliah

                                </label>

                                <select class="form-select"
                                        id="id_mk"
                                        name="id_mk"
                                        required>

                                    <option value="">
                                        -- Pilih Mata Kuliah --
                                    </option>

                                    <?php while ($mk = mysqli_fetch_assoc($result_mk)) { ?>

                                        <option value="<?= $mk['id_mk']; ?>">

                                            <?= htmlspecialchars($mk['kode_mk']); ?>
                                            -
                                            <?= htmlspecialchars($mk['nama_mk']); ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>


                            <!-- Dosen -->
                            <div class="mb-3">

                                <label for="nidn"
                                       class="form-label fw-semibold">

                                    Dosen

                                </label>

                                <select class="form-select"
                                        id="nidn"
                                        name="nidn"
                                        required>

                                    <option value="">
                                        -- Pilih Dosen --
                                    </option>

                                    <?php while ($dosen = mysqli_fetch_assoc($result_dosen)) { ?>

                                        <option value="<?= $dosen['nidn']; ?>">

                                            <?= htmlspecialchars($dosen['nama_dosen']); ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>


                            <!-- Semester -->
                            <div class="mb-3">

                                <label for="semester"
                                       class="form-label fw-semibold">

                                    Semester

                                </label>

                                <select class="form-select"
                                        id="semester"
                                        name="semester"
                                        required>

                                    <option value="">
                                        -- Pilih Semester --
                                    </option>

                                    <?php for ($i = 1; $i <= 8; $i++) { ?>

                                        <option value="<?= $i; ?>">
                                            <?= $i; ?>
                                        </option>

                                    <?php } ?>

                                </select>

                            </div>


                            <!-- Tahun Ajaran -->
                            <div class="mb-3">

                                <label for="tahun_ajaran"
                                       class="form-label fw-semibold">

                                    Tahun Ajaran

                                </label>

                                <input type="text"
                                       class="form-control"
                                       id="tahun_ajaran"
                                       name="tahun_ajaran"
                                       placeholder="Contoh: 2026/2027"
                                       required>

                            </div>


                            <!-- Ruang -->
                            <div class="mb-4">

                                <label for="ruang"
                                       class="form-label fw-semibold">

                                    Ruang

                                </label>

                                <input type="text"
                                       class="form-control"
                                       id="ruang"
                                       name="ruang"
                                       placeholder="Contoh: Lab Komputer 1"
                                       required>

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