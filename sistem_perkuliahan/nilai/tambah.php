<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";


/*
|--------------------------------------------------------------------------
| Ambil KRS yang belum memiliki nilai
|--------------------------------------------------------------------------
*/

$query_krs = "SELECT krs.id_krs,
                     krs.nim,
                     mahasiswa.nama_mahasiswa,
                     mata_kuliah.kode_mk,
                     mata_kuliah.nama_mk,
                     kelas.semester,
                     kelas.tahun_ajaran
              FROM krs
              INNER JOIN mahasiswa
                  ON krs.nim = mahasiswa.nim
              INNER JOIN kelas
                  ON krs.id_kelas = kelas.id_kelas
              INNER JOIN mata_kuliah
                  ON kelas.id_mk = mata_kuliah.id_mk
              LEFT JOIN nilai
                  ON krs.id_krs = nilai.id_krs
              WHERE nilai.id_nilai IS NULL
              ORDER BY krs.nim ASC";

$result_krs = mysqli_query($conn, $query_krs);


/*
|--------------------------------------------------------------------------
| Proses tambah nilai
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_krs = mysqli_real_escape_string(
        $conn,
        $_POST['id_krs']
    );

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
        | Cek apakah KRS sudah memiliki nilai
        |--------------------------------------------------------------------------
        */

        $query_cek = "SELECT *
                      FROM nilai
                      WHERE id_krs = '$id_krs'";

        $result_cek = mysqli_query($conn, $query_cek);


        if (mysqli_num_rows($result_cek) > 0) {

            $error = "KRS tersebut sudah memiliki nilai.";

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
            | Simpan nilai
            |--------------------------------------------------------------------------
            */

            $query = "INSERT INTO nilai
                      (id_krs, nilai_angka, nilai_huruf)
                      VALUES
                      ('$id_krs', '$nilai_angka', '$nilai_huruf')";


            if (mysqli_query($conn, $query)) {

                header("Location: index.php");
                exit;

            } else {

                $error = "Gagal menambahkan nilai: "
                       . mysqli_error($conn);

            }

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

    <title>Tambah Nilai - Sistem Informasi Perkuliahan</title>

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
                            Tambah Nilai
                        </h4>

                        <p class="mb-0">
                            Masukkan nilai mahasiswa berdasarkan KRS yang tersedia.
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

                            <!-- KRS -->
                            <div class="mb-3">

                                <label for="id_krs"
                                       class="form-label fw-semibold">

                                    Mahasiswa / Mata Kuliah

                                </label>

                                <select class="form-select"
                                        id="id_krs"
                                        name="id_krs"
                                        required>

                                    <option value="">
                                        -- Pilih Mahasiswa / Mata Kuliah --
                                    </option>

                                    <?php while ($krs = mysqli_fetch_assoc($result_krs)) { ?>

                                        <option value="<?= $krs['id_krs']; ?>">

                                            <?= htmlspecialchars($krs['nim']); ?>
                                            -
                                            <?= htmlspecialchars($krs['nama_mahasiswa']); ?>
                                            |
                                            <?= htmlspecialchars($krs['kode_mk']); ?>
                                            -
                                            <?= htmlspecialchars($krs['nama_mk']); ?>
                                            |
                                            Semester <?= htmlspecialchars($krs['semester']); ?>
                                            |
                                            <?= htmlspecialchars($krs['tahun_ajaran']); ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>


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
                                       min="0"
                                       max="100"
                                       step="0.01"
                                       placeholder="Masukkan nilai 0 - 100"
                                       required>

                                <div class="form-text">
                                    Nilai huruf akan ditentukan secara otomatis.
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