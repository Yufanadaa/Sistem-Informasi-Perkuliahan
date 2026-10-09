<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";


/*
|--------------------------------------------------------------------------
| Cek ID nilai
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = mysqli_real_escape_string(
    $conn,
    $_GET['id']
);


/*
|--------------------------------------------------------------------------
| Ambil data nilai
|--------------------------------------------------------------------------
*/

$query = "SELECT nilai.*,
                 krs.nim,
                 mahasiswa.nama_mahasiswa,
                 mata_kuliah.kode_mk,
                 mata_kuliah.nama_mk,
                 kelas.semester,
                 kelas.tahun_ajaran
          FROM nilai
          INNER JOIN krs
              ON nilai.id_krs = krs.id_krs
          INNER JOIN mahasiswa
              ON krs.nim = mahasiswa.nim
          INNER JOIN kelas
              ON krs.id_kelas = kelas.id_kelas
          INNER JOIN mata_kuliah
              ON kelas.id_mk = mata_kuliah.id_mk
          WHERE nilai.id_nilai = '$id'";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit;
}

$nilai = mysqli_fetch_assoc($result);


/*
|--------------------------------------------------------------------------
| Proses update nilai
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nilai_angka = mysqli_real_escape_string(
        $conn,
        $_POST['nilai_angka']
    );


    /*
    |--------------------------------------------------------------------------
    | Validasi nilai
    |--------------------------------------------------------------------------
    */

    if ($nilai_angka < 0 || $nilai_angka > 100) {

        $error = "Nilai harus berada di antara 0 sampai 100.";

    } else {


        /*
        |--------------------------------------------------------------------------
        | Tentukan nilai huruf
        |--------------------------------------------------------------------------
        */

        if ($nilai_angka >= 85) {

            $nilai_huruf = "A";

        } elseif ($nilai_angka >= 80) {

            $nilai_huruf = "A-";

        } elseif ($nilai_angka >= 75) {

            $nilai_huruf = "B+";

        } elseif ($nilai_angka >= 70) {

            $nilai_huruf = "B";

        } elseif ($nilai_angka >= 65) {

            $nilai_huruf = "B-";

        } elseif ($nilai_angka >= 60) {

            $nilai_huruf = "C+";

        } elseif ($nilai_angka >= 55) {

            $nilai_huruf = "C";

        } elseif ($nilai_angka >= 40) {

            $nilai_huruf = "D";

        } else {

            $nilai_huruf = "E";

        }


        /*
        |--------------------------------------------------------------------------
        | Update nilai
        |--------------------------------------------------------------------------
        */

        $query_update = "UPDATE nilai
                         SET nilai_angka = '$nilai_angka',
                             nilai_huruf = '$nilai_huruf'
                         WHERE id_nilai = '$id'";


        if (mysqli_query($conn, $query_update)) {

            header("Location: index.php");
            exit;

        } else {

            $error = "Gagal mengubah nilai: "
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

    <title>Edit Nilai - Sistem Informasi Perkuliahan</title>

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
                            Edit Nilai
                        </h4>

                        <p class="mb-0">
                            Perbarui nilai mahasiswa.
                        </p>

                    </div>


                    <!-- Body -->
                    <div class="form-body">

                        <?php if (isset($error)) { ?>

                            <div class="alert alert-danger">

                                <?= htmlspecialchars($error); ?>

                            </div>

                        <?php } ?>


                        <!-- Informasi Mahasiswa -->
                        <div class="alert alert-primary">

                            <strong>
                                <?= htmlspecialchars($nilai['nim']); ?>
                                -
                                <?= htmlspecialchars($nilai['nama_mahasiswa']); ?>
                            </strong>

                            <br>

                            <?= htmlspecialchars($nilai['kode_mk']); ?>
                            -
                            <?= htmlspecialchars($nilai['nama_mk']); ?>

                            <br>

                            Semester <?= htmlspecialchars($nilai['semester']); ?>
                            |
                            Tahun Ajaran <?= htmlspecialchars($nilai['tahun_ajaran']); ?>

                        </div>


                        <form method="POST">

                            <!-- Nilai Angka -->
                            <div class="mb-4">

                                <label for="nilai_angka"
                                       class="form-label fw-semibold">

                                    Nilai Angka

                                </label>

                                <input type="number"
                                       class="form-control"
                                       id="nilai_angka"
                                       name="nilai_angka"
                                       value="<?= htmlspecialchars($nilai['nilai_angka']); ?>"
                                       min="0"
                                       max="100"
                                       step="0.01"
                                       required>

                                <div class="form-text">
                                    Nilai huruf akan diperbarui secara otomatis.
                                </div>

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