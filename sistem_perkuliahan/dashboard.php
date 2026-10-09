<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";


/*
|--------------------------------------------------------------------------
| Statistik
|--------------------------------------------------------------------------
*/

$result_mahasiswa = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM mahasiswa"
);

$total_mahasiswa = mysqli_fetch_assoc(
    $result_mahasiswa
)['total'];


$result_dosen = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM dosen"
);

$total_dosen = mysqli_fetch_assoc(
    $result_dosen
)['total'];


$result_mk = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM mata_kuliah"
);

$total_mk = mysqli_fetch_assoc(
    $result_mk
)['total'];


$result_kelas = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM kelas"
);

$total_kelas = mysqli_fetch_assoc(
    $result_kelas
)['total'];


$result_krs = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM krs"
);

$total_krs = mysqli_fetch_assoc(
    $result_krs
)['total'];


$result_nilai = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM nilai"
);

$total_nilai = mysqli_fetch_assoc(
    $result_nilai
)['total'];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Dashboard - Sistem Informasi Perkuliahan
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

        /* Warna statistik */
        .stat-mahasiswa {
            background-color: #bfdbfe;
        }

        .stat-dosen {
            background-color: #bae6fd;
        }

        .stat-mk {
            background-color: #c7d2fe;
        }

        .stat-kelas {
            background-color: #a5f3fc;
        }

        .stat-krs {
            background-color: #dbeafe;
        }

        .stat-nilai {
            background-color: #bae6fd;
        }

        /* Menu */
        .menu-card {
            border: none;
            border-radius: 15px;
            transition: 0.2s;
            overflow: hidden;
        }

        .menu-card:hover {
            transform: translateY(-4px);
        }

        .menu-icon {
            font-size: 32px;
        }

        .menu-mahasiswa {
            border-top: 5px solid #93c5fd;
        }

        .menu-dosen {
            border-top: 5px solid #7dd3fc;
        }

        .menu-mk {
            border-top: 5px solid #a5b4fc;
        }

        .menu-kelas {
            border-top: 5px solid #67e8f9;
        }

        .menu-krs {
            border-top: 5px solid #bfdbfe;
        }

        .menu-nilai {
            border-top: 5px solid #93c5fd;
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
           href="dashboard.php">

            Sistem Informasi Perkuliahan

        </a>


        <div class="d-flex align-items-center gap-3">

            <span class="text-white">

                👤
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

                Dashboard

            </h3>

            <p class="mb-0">

                Selamat datang,
                <strong>
                    <?= htmlspecialchars($_SESSION['username']); ?>
                </strong>.

                Kelola data perkuliahan melalui menu yang tersedia.

            </p>

        </div>

    </div>



    <!-- =====================================================
         STATISTIK
    ====================================================== -->

    <div class="mb-3">

        <h4 class="fw-bold mb-1">

            Statistik

        </h4>

        <p class="text-muted mb-0">

            Ringkasan data pada sistem perkuliahan.

        </p>

    </div>


    <div class="row g-4 mb-5">


        <!-- Mahasiswa -->
        <div class="col-md-6 col-lg-4">

            <div class="card stat-card stat-mahasiswa shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="stat-title">
                                Mahasiswa
                            </div>

                            <div class="stat-number">
                                <?= $total_mahasiswa; ?>
                            </div>

                        </div>

                        <div class="menu-icon">
                            👨‍🎓
                        </div>

                    </div>


                    <a href="mahasiswa/index.php"
                       class="btn btn-primary btn-sm mt-3">

                        Kelola Data

                    </a>

                </div>

            </div>

        </div>



        <!-- Dosen -->
        <div class="col-md-6 col-lg-4">

            <div class="card stat-card stat-dosen shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="stat-title">
                                Dosen
                            </div>

                            <div class="stat-number">
                                <?= $total_dosen; ?>
                            </div>

                        </div>

                        <div class="menu-icon">
                            👨‍🏫
                        </div>

                    </div>


                    <a href="dosen/index.php"
                       class="btn btn-primary btn-sm mt-3">

                        Kelola Data

                    </a>

                </div>

            </div>

        </div>



        <!-- Mata Kuliah -->
        <div class="col-md-6 col-lg-4">

            <div class="card stat-card stat-mk shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="stat-title">
                                Mata Kuliah
                            </div>

                            <div class="stat-number">
                                <?= $total_mk; ?>
                            </div>

                        </div>

                        <div class="menu-icon">
                            📚
                        </div>

                    </div>


                    <a href="mata_kuliah/index.php"
                       class="btn btn-primary btn-sm mt-3">

                        Kelola Data

                    </a>

                </div>

            </div>

        </div>



        <!-- Kelas -->
        <div class="col-md-6 col-lg-4">

            <div class="card stat-card stat-kelas shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="stat-title">
                                Kelas
                            </div>

                            <div class="stat-number">
                                <?= $total_kelas; ?>
                            </div>

                        </div>

                        <div class="menu-icon">
                            🏫
                        </div>

                    </div>


                    <a href="kelas/index.php"
                       class="btn btn-primary btn-sm mt-3">

                        Kelola Data

                    </a>

                </div>

            </div>

        </div>



        <!-- KRS -->
        <div class="col-md-6 col-lg-4">

            <div class="card stat-card stat-krs shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="stat-title">
                                KRS
                            </div>

                            <div class="stat-number">
                                <?= $total_krs; ?>
                            </div>

                        </div>

                        <div class="menu-icon">
                            📝
                        </div>

                    </div>


                    <a href="krs/index.php"
                       class="btn btn-primary btn-sm mt-3">

                        Kelola Data

                    </a>

                </div>

            </div>

        </div>



        <!-- Nilai -->
        <div class="col-md-6 col-lg-4">

            <div class="card stat-card stat-nilai shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="stat-title">
                                Nilai
                            </div>

                            <div class="stat-number">
                                <?= $total_nilai; ?>
                            </div>

                        </div>

                        <div class="menu-icon">
                            📊
                        </div>

                    </div>


                    <a href="nilai/index.php"
                       class="btn btn-primary btn-sm mt-3">

                        Kelola Data

                    </a>

                </div>

            </div>

        </div>

    </div>



    <!-- =====================================================
         MENU UTAMA
    ====================================================== -->

    <div class="mb-3">

        <h4 class="fw-bold">

            Menu Utama

        </h4>

        <p class="text-muted">

            Akses pengelolaan data sistem perkuliahan.

        </p>

    </div>


    <div class="row g-4">


        <!-- Data Mahasiswa -->
        <div class="col-md-4">

            <div class="card menu-card menu-mahasiswa shadow-sm h-100">

                <div class="card-body text-center p-4">

                    <div class="menu-icon mb-3">
                        👨‍🎓
                    </div>

                    <h5 class="fw-bold">
                        Data Mahasiswa
                    </h5>

                    <p class="text-muted">
                        Kelola data mahasiswa dan program studi.
                    </p>

                    <a href="mahasiswa/index.php"
                       class="btn btn-primary">

                        Buka Data

                    </a>

                </div>

            </div>

        </div>



        <!-- Data Dosen -->
        <div class="col-md-4">

            <div class="card menu-card menu-dosen shadow-sm h-100">

                <div class="card-body text-center p-4">

                    <div class="menu-icon mb-3">
                        👨‍🏫
                    </div>

                    <h5 class="fw-bold">
                        Data Dosen
                    </h5>

                    <p class="text-muted">
                        Kelola data dosen dan program studi.
                    </p>

                    <a href="dosen/index.php"
                       class="btn btn-primary">

                        Buka Data

                    </a>

                </div>

            </div>

        </div>



        <!-- Mata Kuliah -->
        <div class="col-md-4">

            <div class="card menu-card menu-mk shadow-sm h-100">

                <div class="card-body text-center p-4">

                    <div class="menu-icon mb-3">
                        📚
                    </div>

                    <h5 class="fw-bold">
                        Mata Kuliah
                    </h5>

                    <p class="text-muted">
                        Kelola mata kuliah yang tersedia.
                    </p>

                    <a href="mata_kuliah/index.php"
                       class="btn btn-primary">

                        Buka Data

                    </a>

                </div>

            </div>

        </div>



        <!-- Kelas -->
        <div class="col-md-4">

            <div class="card menu-card menu-kelas shadow-sm h-100">

                <div class="card-body text-center p-4">

                    <div class="menu-icon mb-3">
                        🏫
                    </div>

                    <h5 class="fw-bold">
                        Data Kelas
                    </h5>

                    <p class="text-muted">
                        Kelola kelas, dosen, semester, dan ruang.
                    </p>

                    <a href="kelas/index.php"
                       class="btn btn-primary">

                        Buka Data

                    </a>

                </div>

            </div>

        </div>



        <!-- KRS -->
        <div class="col-md-4">

            <div class="card menu-card menu-krs shadow-sm h-100">

                <div class="card-body text-center p-4">

                    <div class="menu-icon mb-3">
                        📝
                    </div>

                    <h5 class="fw-bold">
                        KRS
                    </h5>

                    <p class="text-muted">
                        Kelola pengambilan mata kuliah mahasiswa.
                    </p>

                    <a href="krs/index.php"
                       class="btn btn-primary">

                        Buka Data

                    </a>

                </div>

            </div>

        </div>



        <!-- Nilai -->
        <div class="col-md-4">

            <div class="card menu-card menu-nilai shadow-sm h-100">

                <div class="card-body text-center p-4">

                    <div class="menu-icon mb-3">
                        📊
                    </div>

                    <h5 class="fw-bold">
                        Data Nilai
                    </h5>

                    <p class="text-muted">
                        Kelola nilai akademik mahasiswa.
                    </p>

                    <a href="nilai/index.php"
                       class="btn btn-primary">

                        Buka Data

                    </a>

                </div>

            </div>

        </div>

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