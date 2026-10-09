<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";


$query = "SELECT dosen.*,
                 prodi.nama_prodi
          FROM dosen
          INNER JOIN prodi
              ON dosen.id_prodi = prodi.id_prodi
          ORDER BY dosen.nidn ASC";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Data Dosen - Sistem Informasi Perkuliahan
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>

        body {
            background-color: #ffffff;
        }

        .navbar-custom {
            background-color: #1e3a8a;
        }

        .page-title {
            color: #1e3a8a;
        }

        .table-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .table thead th {
            background-color: #dbeafe;
            color: #1e3a8a;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-tambah {
            background-color: #1e3a8a;
            border-color: #1e3a8a;
            color: white;
            font-weight: 600;
        }

        .btn-tambah:hover {
            background-color: #2563eb;
            border-color: #2563eb;
            color: white;
        }

    </style>

</head>

<body>


<!-- Navbar -->

<nav class="navbar navbar-dark navbar-custom">

    <div class="container">

        <a class="navbar-brand fw-bold"
           href="../dashboard.php">

            Sistem Informasi Perkuliahan

        </a>

        <div class="d-flex align-items-center gap-3">

            <span class="text-white">

                👤
                <?= htmlspecialchars($_SESSION['username']); ?>

            </span>

            <a href="../logout.php"
               class="btn btn-light btn-sm">

                Logout

            </a>

        </div>

    </div>

</nav>


<!-- Konten -->

<div class="container py-4">


    <!-- Header halaman -->

    <div class="d-flex justify-content-between
                align-items-center mb-4">

        <div>

            <h3 class="fw-bold page-title mb-1">

                Data Dosen

            </h3>

            <p class="text-muted mb-0">

                Kelola data dosen yang terdaftar.

            </p>

        </div>


        <a href="tambah.php"
           class="btn btn-tambah">

            + Tambah Dosen

        </a>

    </div>


    <!-- Tabel -->

    <div class="card table-card shadow-sm">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>NIDN</th>

                        <th>Nama Dosen</th>

                        <th>Program Studi</th>

                        <th class="text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $no = 1;

                    while ($dosen = mysqli_fetch_assoc($result)) {

                    ?>

                        <tr>

                            <td>
                                <?= $no++; ?>
                            </td>

                            <td>

                                <strong>

                                    <?= htmlspecialchars(
                                        $dosen['nidn']
                                    ); ?>

                                </strong>

                            </td>

                            <td>

                                <?= htmlspecialchars(
                                    $dosen['nama_dosen']
                                ); ?>

                            </td>

                            <td>

                                <?= htmlspecialchars(
                                    $dosen['nama_prodi']
                                ); ?>

                            </td>

                            <td class="text-center">

                                <a href="edit.php?nidn=<?= urlencode(
                                    $dosen['nidn']
                                ); ?>"
                                   class="btn btn-warning btn-sm">

                                    Edit

                                </a>

                                <a href="hapus.php?nidn=<?= urlencode(
                                    $dosen['nidn']
                                ); ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm(
                                       'Yakin ingin menghapus data dosen ini?'
                                   );">

                                    Hapus

                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>


    <!-- Kembali -->

    <div class="mt-4">

        <a href="../dashboard.php"
           class="btn btn-outline-secondary">

            ← Kembali ke Dashboard

        </a>

    </div>


</div>

</body>

</html>