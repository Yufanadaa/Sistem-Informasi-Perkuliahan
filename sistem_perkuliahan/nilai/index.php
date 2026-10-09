<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";


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
          ORDER BY krs.nim ASC";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Data Nilai - Sistem Informasi Perkuliahan
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
            white-space: nowrap;
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

                Data Nilai

            </h3>

            <p class="text-muted mb-0">

                Kelola nilai akademik mahasiswa.

            </p>

        </div>


        <a href="tambah.php"
           class="btn btn-tambah">

            + Tambah Nilai

        </a>

    </div>


    <!-- Tabel -->

    <div class="card table-card shadow-sm">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>NIM</th>

                        <th>Mahasiswa</th>

                        <th>Kode MK</th>

                        <th>Mata Kuliah</th>

                        <th>Semester</th>

                        <th>Tahun Ajaran</th>

                        <th>Nilai Angka</th>

                        <th>Nilai Huruf</th>

                        <th class="text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $no = 1;

                    while ($nilai = mysqli_fetch_assoc($result)) {

                    ?>

                        <tr>

                            <td>
                                <?= $no++; ?>
                            </td>

                            <td>

                                <strong>

                                    <?= htmlspecialchars(
                                        $nilai['nim']
                                    ); ?>

                                </strong>

                            </td>

                            <td>

                                <?= htmlspecialchars(
                                    $nilai['nama_mahasiswa']
                                ); ?>

                            </td>

                            <td>

                                <strong>

                                    <?= htmlspecialchars(
                                        $nilai['kode_mk']
                                    ); ?>

                                </strong>

                            </td>

                            <td>

                                <?= htmlspecialchars(
                                    $nilai['nama_mk']
                                ); ?>

                            </td>

                            <td>

                                <span class="badge bg-primary">

                                    Semester
                                    <?= htmlspecialchars(
                                        $nilai['semester']
                                    ); ?>

                                </span>

                            </td>

                            <td>

                                <?= htmlspecialchars(
                                    $nilai['tahun_ajaran']
                                ); ?>

                            </td>

                            <td>

                                <strong>

                                    <?= htmlspecialchars(
                                        $nilai['nilai_angka']
                                    ); ?>

                                </strong>

                            </td>

                            <td>

                                <?php

                                $grade = $nilai['nilai_huruf'];

                                if ($grade == 'A') {

                                    $badge = 'bg-success';

                                } elseif ($grade == 'A-') {

                                    $badge = 'bg-primary';

                                } elseif ($grade == 'B+' ||
                                          $grade == 'B') {

                                    $badge = 'bg-info text-dark';

                                } elseif ($grade == 'B-' ||
                                          $grade == 'C+' ||
                                          $grade == 'C') {

                                    $badge = 'bg-warning text-dark';

                                } elseif ($grade == 'D') {

                                    $badge = 'bg-danger';

                                } else {

                                    $badge = 'bg-dark';

                                }

                                ?>

                                <span class="badge <?= $badge; ?>">

                                    <?= htmlspecialchars($grade); ?>

                                </span>

                            </td>

                            <td class="text-center">

                                <a href="edit.php?id=<?= urlencode(
                                    $nilai['id_nilai']
                                ); ?>"
                                   class="btn btn-warning btn-sm">

                                    Edit

                                </a>

                                <a href="hapus.php?id=<?= urlencode(
                                    $nilai['id_nilai']
                                ); ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm(
                                       'Yakin ingin menghapus data nilai ini?'
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