<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";

/*
|--------------------------------------------------------------------------
| Ambil data mahasiswa
|--------------------------------------------------------------------------
*/

$query_mahasiswa = "SELECT *
                    FROM mahasiswa
                    ORDER BY nim ASC";

$result_mahasiswa = mysqli_query($conn, $query_mahasiswa);


/*
|--------------------------------------------------------------------------
| Ambil data kelas
|--------------------------------------------------------------------------
*/

$query_kelas = "SELECT kelas.*,
                       mata_kuliah.kode_mk,
                       mata_kuliah.nama_mk,
                       dosen.nama_dosen
                FROM kelas
                INNER JOIN mata_kuliah
                    ON kelas.id_mk = mata_kuliah.id_mk
                INNER JOIN dosen
                    ON kelas.nidn = dosen.nidn
                ORDER BY kelas.tahun_ajaran DESC,
                         kelas.semester ASC";

$result_kelas = mysqli_query($conn, $query_kelas);


/*
|--------------------------------------------------------------------------
| Proses tambah KRS
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nim = mysqli_real_escape_string(
        $conn,
        $_POST['nim']
    );

    $id_kelas = mysqli_real_escape_string(
        $conn,
        $_POST['id_kelas']
    );


    /*
    |----------------------------------------------------------------------
    | Cek apakah mahasiswa sudah mengambil kelas tersebut
    |----------------------------------------------------------------------
    */

    $query_cek = "SELECT *
                  FROM krs
                  WHERE nim = '$nim'
                  AND id_kelas = '$id_kelas'";

    $result_cek = mysqli_query($conn, $query_cek);


    if (mysqli_num_rows($result_cek) > 0) {

        $error = "Mahasiswa tersebut sudah mengambil kelas ini.";

    } else {

        $query = "INSERT INTO krs
                  (nim, id_kelas)
                  VALUES
                  ('$nim', '$id_kelas')";

        if (mysqli_query($conn, $query)) {

            header("Location: index.php");
            exit;

        } else {

            $error = "Gagal menambahkan KRS: "
                   . mysqli_error($conn);

        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Tambah KRS - Sistem Informasi Perkuliahan</title>

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

            <div class="col-md-9 col-lg-8">

                <div class="card form-card shadow-sm">

                    <!-- Header -->
                    <div class="form-header">

                        <h4 class="mb-1 fw-bold">
                            Tambah KRS
                        </h4>

                        <p class="mb-0">
                            Silakan pilih mahasiswa dan kelas yang akan diambil.
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

                            <!-- Mahasiswa -->
                            <div class="mb-3">

                                <label for="nim"
                                       class="form-label fw-semibold">

                                    Mahasiswa

                                </label>

                                <select class="form-select"
                                        id="nim"
                                        name="nim"
                                        required>

                                    <option value="">
                                        -- Pilih Mahasiswa --
                                    </option>

                                    <?php while ($mahasiswa = mysqli_fetch_assoc($result_mahasiswa)) { ?>

                                        <option value="<?= $mahasiswa['nim']; ?>">

                                            <?= htmlspecialchars($mahasiswa['nim']); ?>
                                            -
                                            <?= htmlspecialchars($mahasiswa['nama_mahasiswa']); ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>


                            <!-- Kelas -->
                            <div class="mb-4">

                                <label for="id_kelas"
                                       class="form-label fw-semibold">

                                    Kelas

                                </label>

                                <select class="form-select"
                                        id="id_kelas"
                                        name="id_kelas"
                                        required>

                                    <option value="">
                                        -- Pilih Kelas --
                                    </option>

                                    <?php while ($kelas = mysqli_fetch_assoc($result_kelas)) { ?>

                                        <option value="<?= $kelas['id_kelas']; ?>">

                                            <?= htmlspecialchars($kelas['kode_mk']); ?>
                                            -
                                            <?= htmlspecialchars($kelas['nama_mk']); ?>
                                            |
                                            Semester <?= htmlspecialchars($kelas['semester']); ?>
                                            |
                                            <?= htmlspecialchars($kelas['tahun_ajaran']); ?>
                                            |
                                            <?= htmlspecialchars($kelas['nama_dosen']); ?>

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