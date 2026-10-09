<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] !== 'mahasiswa') {
    header("Location: dashboard.php");
    exit;
}

require_once "config/database.php";


/*
|--------------------------------------------------------------------------
| Identitas Mahasiswa
|--------------------------------------------------------------------------
*/

$nim = $_SESSION['username'];


/*
|--------------------------------------------------------------------------
| Data Mahasiswa
|--------------------------------------------------------------------------
*/

$query_mahasiswa = mysqli_query(
    $conn,
    "SELECT
        nim,
        nama_mahasiswa,
        jenis_kelamin,
        tanggal_lahir,
        id_prodi
     FROM mahasiswa
     WHERE nim = '$nim'
     LIMIT 1"
);

$mahasiswa = mysqli_fetch_assoc($query_mahasiswa);


/*
|--------------------------------------------------------------------------
| Jika Data Mahasiswa Tidak Ditemukan
|--------------------------------------------------------------------------
*/

if (!$mahasiswa) {

    echo "Data mahasiswa tidak ditemukan.";
    exit;

}


/*
|--------------------------------------------------------------------------
| Data KRS dan Nilai
|--------------------------------------------------------------------------
*/

$query_nilai = mysqli_query(
    $conn,
    "SELECT
        krs.id_krs,
        kelas.id_kelas,
        mata_kuliah.kode_mk,
        mata_kuliah.nama_mk,
        mata_kuliah.sks,
        kelas.semester,
        kelas.tahun_ajaran,
        kelas.ruang,
        dosen.nama_dosen,
        nilai.nilai_angka,
        nilai.nilai_huruf
     FROM krs
     INNER JOIN kelas
        ON krs.id_kelas = kelas.id_kelas
     INNER JOIN mata_kuliah
        ON kelas.id_mk = mata_kuliah.id_mk
     INNER JOIN dosen
        ON kelas.nidn = dosen.nidn
     LEFT JOIN nilai
        ON krs.id_krs = nilai.id_krs
     WHERE krs.nim = '$nim'
     ORDER BY kelas.tahun_ajaran DESC, kelas.semester ASC"
);


/*
|--------------------------------------------------------------------------
| Statistik
|--------------------------------------------------------------------------
*/

$total_matkul = mysqli_num_rows($query_nilai);

$total_sudah_dinilai = 0;
$total_belum_dinilai = 0;


/*
|--------------------------------------------------------------------------
| Simpan Data untuk Ditampilkan
|--------------------------------------------------------------------------
*/

$data_nilai = [];

while ($row = mysqli_fetch_assoc($query_nilai)) {

    $data_nilai[] = $row;

    if ($row['nilai_angka'] !== null) {

        $total_sudah_dinilai++;

    } else {

        $total_belum_dinilai++;

    }

}


/*
|--------------------------------------------------------------------------
| Persentase Nilai
|--------------------------------------------------------------------------
*/

if ($total_matkul > 0) {

    $persentase_nilai = round(
        ($total_sudah_dinilai / $total_matkul) * 100
    );

} else {

    $persentase_nilai = 0;

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Dashboard Mahasiswa - Sistem Informasi Perkuliahan
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

        .info-card {
            background: linear-gradient(
                135deg,
                #dbeafe,
                #bfdbfe
            );

            border: none;
            border-radius: 15px;
        }

        .stat-card {
            border: none;
            border-radius: 15px;
        }

        .table-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .table thead {
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
           href="halaman_mahasiswa.php">

            Sistem Informasi Perkuliahan

        </a>


        <div class="d-flex align-items-center gap-3">

            <span class="text-white">

                👨‍🎓
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
         INFORMASI MAHASISWA
    ====================================================== -->

    <div class="card info-card shadow-sm mb-4">

        <div class="card-body p-4">

            <h3 class="fw-bold mb-3">

                Selamat Datang, 
                <?= htmlspecialchars($mahasiswa['nama_mahasiswa']); ?>

            </h3>


            <div class="row">

                <div class="col-md-6">

                    <p class="mb-2">

                        <strong>NIM:</strong><br>

                        <?= htmlspecialchars($mahasiswa['nim']); ?>

                    </p>


                    <p class="mb-0">

                        <strong>Jenis Kelamin:</strong><br>

                        <?= $mahasiswa['jenis_kelamin'] === 'L'
                            ? 'Laki-laki'
                            : 'Perempuan'; ?>

                    </p>

                </div>


                <div class="col-md-6">

                    <p class="mb-2">

                        <strong>Tanggal Lahir:</strong><br>

                        <?= $mahasiswa['tanggal_lahir']
                            ? htmlspecialchars($mahasiswa['tanggal_lahir'])
                            : '-'; ?>

                    </p>


                    <p class="mb-0">

                        <strong>ID Program Studi:</strong><br>

                        <?= htmlspecialchars($mahasiswa['id_prodi']); ?>

                    </p>

                </div>

            </div>

        </div>

    </div>



    <!-- =====================================================
         STATISTIK
    ====================================================== -->

    <div class="row g-4 mb-4">


        <!-- Total Mata Kuliah -->

        <div class="col-md-4">

            <div class="card stat-card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted">

                        Total Mata Kuliah

                    </div>

                    <div class="fs-3 fw-bold text-primary">

                        <?= $total_matkul; ?>

                    </div>

                    <small class="text-muted">

                        Mata kuliah yang diambil

                    </small>

                </div>

            </div>

        </div>


        <!-- Sudah Dinilai -->

        <div class="col-md-4">

            <div class="card stat-card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted">

                        Sudah Dinilai

                    </div>

                    <div class="fs-3 fw-bold text-success">

                        <?= $total_sudah_dinilai; ?>

                    </div>

                    <small class="text-muted">

                        Mata kuliah sudah memiliki nilai

                    </small>

                </div>

            </div>

        </div>


        <!-- Belum Dinilai -->

        <div class="col-md-4">

            <div class="card stat-card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted">

                        Belum Dinilai

                    </div>

                    <div class="fs-3 fw-bold text-danger">

                        <?= $total_belum_dinilai; ?>

                    </div>

                    <small class="text-muted">

                        Mata kuliah belum memiliki nilai

                    </small>

                </div>

            </div>

        </div>

    </div>



    <!-- =====================================================
         DAFTAR NILAI
    ====================================================== -->

    <div class="card table-card shadow-sm">

        <div class="card-body p-4">

            <h4 class="fw-bold mb-3">

                Kartu Hasil Studi

            </h4>


            <?php if (count($data_nilai) > 0) { ?>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead>

                            <tr>

                                <th>No</th>

                                <th>Kode MK</th>

                                <th>Mata Kuliah</th>

                                <th>SKS</th>

                                <th>Dosen</th>

                                <th>Semester</th>

                                <th>Nilai Angka</th>

                                <th>Nilai Huruf</th>

                                <th>Status</th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php

                            $no = 1;

                            foreach ($data_nilai as $row) {

                            ?>

                                <tr>

                                    <td>

                                        <?= $no++; ?>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $row['kode_mk']
                                        ); ?>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $row['nama_mk']
                                        ); ?>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $row['sks']
                                        ); ?>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $row['nama_dosen']
                                        ); ?>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $row['semester']
                                        ); ?>

                                    </td>


                                    <td>

                                        <?php if ($row['nilai_angka'] !== null) { ?>

                                            <?= htmlspecialchars(
                                                $row['nilai_angka']
                                            ); ?>

                                        <?php } else { ?>

                                            -

                                        <?php } ?>

                                    </td>


                                    <td class="text-center">

                                        <?php if ($row['nilai_huruf'] !== null) { ?>

                                            <span class="badge bg-success">

                                                <?= htmlspecialchars(
                                                    $row['nilai_huruf']
                                                ); ?>

                                            </span>

                                        <?php } else { ?>

                                            <span class="badge bg-secondary">

                                                -

                                            </span>

                                        <?php } ?>

                                    </td>


                                    <td class="text-center">

                                        <?php if ($row['nilai_angka'] !== null) { ?>

                                            <span class="badge bg-success">

                                                Sudah Dinilai

                                            </span>

                                        <?php } else { ?>

                                            <span class="badge bg-warning text-dark">

                                                Belum Dinilai

                                            </span>

                                        <?php } ?>

                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            <?php } else { ?>

                <div class="alert alert-info mb-0">

                    Belum terdapat mata kuliah yang diambil.

                </div>

            <?php } ?>

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