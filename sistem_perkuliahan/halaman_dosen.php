<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] !== 'dosen') {
    header("Location: dashboard.php");
    exit;
}

require_once "config/database.php";


/*
|--------------------------------------------------------------------------
| Data Dosen
|--------------------------------------------------------------------------
*/

$nidn = "0012345678";

$query_dosen = mysqli_query(
    $conn,
    "SELECT *
     FROM dosen
     WHERE nidn = '$nidn'
     LIMIT 1"
);

$dosen = mysqli_fetch_assoc($query_dosen);


/*
|--------------------------------------------------------------------------
| Kelas yang Diampu
|--------------------------------------------------------------------------
*/

$query_kelas = mysqli_query(
    $conn,
    "SELECT
        kelas.id_kelas,
        kelas.semester,
        kelas.tahun_ajaran,
        kelas.ruang,
        mata_kuliah.kode_mk,
        mata_kuliah.nama_mk,
        mata_kuliah.sks,

        COUNT(DISTINCT krs.id_krs) AS total_mahasiswa,

        COUNT(DISTINCT nilai.id_nilai) AS total_nilai

     FROM kelas

     INNER JOIN mata_kuliah
        ON kelas.id_mk = mata_kuliah.id_mk

     LEFT JOIN krs
        ON kelas.id_kelas = krs.id_kelas

     LEFT JOIN nilai
        ON krs.id_krs = nilai.id_krs

     WHERE kelas.nidn = '$nidn'

     GROUP BY
        kelas.id_kelas,
        kelas.semester,
        kelas.tahun_ajaran,
        kelas.ruang,
        mata_kuliah.kode_mk,
        mata_kuliah.nama_mk,
        mata_kuliah.sks

     ORDER BY
        kelas.tahun_ajaran DESC,
        kelas.semester ASC"
);


/*
|--------------------------------------------------------------------------
| Statistik
|--------------------------------------------------------------------------
*/

$total_kelas = mysqli_num_rows($query_kelas);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Dashboard Dosen - Sistem Informasi Perkuliahan
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>

        body {
            background-color: #ffffff;
        }

        /* Navbar */
        .navbar-custom {
            background-color: #1e3a8a;
        }

        /* Welcome */
        .welcome-card {
            background: linear-gradient(
                135deg,
                #dbeafe,
                #bfdbfe
            );

            border: none;
            border-radius: 15px;
        }

        /* Statistik */
        .stat-card {
            border: none;
            border-radius: 15px;
            transition: 0.2s;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #1e3a8a;
        }

        .stat-title {
            font-weight: 600;
            color: #1e3a8a;
        }

        .stat-kelas {
            background-color: #a5f3fc;
        }

        .stat-nilai {
            background-color: #bae6fd;
        }

        /* Kelas */
        .kelas-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: 0.2s;
        }

        .kelas-card:hover {
            transform: translateY(-4px);
        }

        .kelas-header {
            background-color: #1e3a8a;
            color: white;
        }

        .footer {
            color: #6c757d;
            font-size: 14px;
        }

    </style>

</head>


<body>


<!-- =========================================================
     NAVBAR
========================================================= -->

<nav class="navbar navbar-dark navbar-custom">

    <div class="container">

        <a class="navbar-brand fw-bold"
           href="halaman_dosen.php">

            Sistem Informasi Perkuliahan

        </a>


        <div class="d-flex align-items-center gap-3">

            <span class="text-white">

                👨‍🏫
                <?= htmlspecialchars($_SESSION['username']); ?>

            </span>


            <a href="logout.php"
               class="btn btn-light btn-sm">

                Logout

            </a>

        </div>

    </div>

</nav>



<!-- =========================================================
     KONTEN
========================================================= -->

<div class="container py-4">


    <!-- =====================================================
         WELCOME
    ====================================================== -->

    <div class="card welcome-card shadow-sm mb-4">

        <div class="card-body p-4">

            <h3 class="fw-bold mb-2">

                Dashboard Dosen

            </h3>

            <p class="mb-1">

                Selamat datang,
                <strong>
                    <?= htmlspecialchars($dosen['nama_dosen']); ?>
                </strong>.

            </p>

            <p class="mb-0 text-muted">

                NIDN:
                <?= htmlspecialchars($dosen['nidn']); ?>

            </p>

        </div>

    </div>



    <!-- =====================================================
         STATISTIK
    ====================================================== -->

    <div class="mb-3">

        <h4 class="fw-bold mb-1">

            Ringkasan

        </h4>

        <p class="text-muted mb-0">

            Ringkasan data perkuliahan yang diampu.

        </p>

    </div>


    <div class="row g-4 mb-5">


        <!-- Total Kelas -->

        <div class="col-md-6 col-lg-4">

            <div class="card stat-card stat-kelas shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="stat-title">

                                Kelas Diampu

                            </div>

                            <div class="stat-number">

                                <?= $total_kelas; ?>

                            </div>

                        </div>

                        <div style="font-size: 32px;">

                            🏫

                        </div>

                    </div>

                </div>

            </div>

        </div>



        <!-- Input Nilai -->

        <div class="col-md-6 col-lg-4">

            <div class="card stat-card stat-nilai shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="stat-title">

                                Pengelolaan Nilai

                            </div>

                            <div class="stat-number">

                                📝

                            </div>

                        </div>

                        <div style="font-size: 32px;">

                            📊

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- =====================================================
         KELAS YANG DIAMPU
    ====================================================== -->

    <div class="mb-3">

        <h4 class="fw-bold">

            Kelas yang Diampu

        </h4>

        <p class="text-muted">

            Daftar kelas yang menjadi tanggung jawab Anda.

        </p>

    </div>


    <div class="row g-4">


        <?php if (mysqli_num_rows($query_kelas) > 0) { ?>


            <?php while ($kelas = mysqli_fetch_assoc($query_kelas)) { ?>


                <div class="col-md-6 col-lg-4">

                    <div class="card kelas-card shadow-sm h-100">


                        <div class="card-header kelas-header">

                            <strong>

                                <?= htmlspecialchars($kelas['kode_mk']); ?>

                            </strong>

                        </div>


                        <div class="card-body">

                            <h5 class="fw-bold">

                                <?= htmlspecialchars($kelas['nama_mk']); ?>

                            </h5>


                            <p class="mb-1">

                                <strong>SKS:</strong>

                                <?= htmlspecialchars($kelas['sks']); ?>

                            </p>


                            <p class="mb-1">

                                <strong>Semester:</strong>

                                <?= htmlspecialchars($kelas['semester']); ?>

                            </p>


                            <p class="mb-1">

                                <strong>Tahun Ajaran:</strong>

                                <?= htmlspecialchars($kelas['tahun_ajaran']); ?>

                            </p>
                            

                            <p class="mb-2">

                                <strong>Ruang:</strong>

                                <?= htmlspecialchars($kelas['ruang']); ?>

                            </p>


                            <p class="mb-2">

                                👨‍🎓

                                <strong>Mahasiswa:</strong>

                                <?= $kelas['total_mahasiswa']; ?>

                                orang

                            </p>


                            <?php

                            $total_mahasiswa_kelas = (int) $kelas['total_mahasiswa'];

                            $total_nilai_kelas = (int) $kelas['total_nilai'];

                            if ($total_mahasiswa_kelas > 0) {

                                $persentase_nilai = round(
                                    ($total_nilai_kelas / $total_mahasiswa_kelas) * 100
                                );

                            } else {

                                $persentase_nilai = 0;

                            }

                            ?>


                            <div class="mb-2">

                                <div class="d-flex justify-content-between">

                                    <small class="fw-semibold">
                                        Progress Nilai
                                    </small>

                                    <small>
                                        <?= $total_nilai_kelas; ?>
                                        /
                                        <?= $total_mahasiswa_kelas; ?>
                                    </small>

                                </div>


                                <div class="progress">

                                    <div class="progress-bar"
                                        role="progressbar"
                                        style="width: <?= $persentase_nilai; ?>%;">

                                        <?= $persentase_nilai; ?>%

                                    </div>

                                </div>

                            </div>


                            <?php if ($total_mahasiswa_kelas > 0 && $total_nilai_kelas == $total_mahasiswa_kelas) { ?>

                                <div class="mb-3">

                                    <span class="badge bg-success">

                                        ✓ Nilai Lengkap

                                    </span>

                                </div>

                            <?php } elseif ($total_nilai_kelas > 0) { ?>

                                <div class="mb-3">

                                    <span class="badge bg-warning text-dark">

                                        ⚠ Nilai Belum Lengkap

                                    </span>

                                </div>

                            <?php } else { ?>

                                <div class="mb-3">

                                    <span class="badge bg-secondary">

                                        Belum Diinput

                                    </span>

                                </div>

                            <?php } ?>


                            <a href="input_nilai.php?id_kelas=<?= $kelas['id_kelas']; ?>"
                            class="btn btn-primary w-100">

                                Input / Edit Nilai

                            </a>
                            

                        </div>

                    </div>

                </div>


            <?php } ?>


        <?php } else { ?>


            <div class="col-12">

                <div class="alert alert-info">

                    Belum terdapat kelas yang diampu oleh dosen ini.

                </div>

            </div>


        <?php } ?>


    </div>



    <!-- =====================================================
         FOOTER
    ====================================================== -->

    <div class="text-center mt-5 mb-3 footer">

        Sistem Informasi Perkuliahan
        &copy; 2026

    </div>

</div>


</body>

</html>