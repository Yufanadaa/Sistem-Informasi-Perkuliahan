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
| Cek ID Kelas
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id_kelas'])) {
    header("Location: halaman_dosen.php");
    exit;
}

$id_kelas = (int) $_GET['id_kelas'];


/*
|--------------------------------------------------------------------------
| Proses Simpan Nilai
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_krs = (int) $_POST['id_krs'];
    $nilai_angka = (float) $_POST['nilai_angka'];


    /*
    |--------------------------------------------------------------------------
    | Validasi Nilai
    |--------------------------------------------------------------------------
    */

    if ($nilai_angka < 0 || $nilai_angka > 100) {

        $error = "Nilai harus berada antara 0 sampai 100.";

    } else {


        /*
        |--------------------------------------------------------------------------
        | Konversi Nilai Huruf
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
        | Cek Apakah Nilai Sudah Ada
        |--------------------------------------------------------------------------
        */

        $cek_nilai = mysqli_query(
            $conn,
            "SELECT id_nilai
             FROM nilai
             WHERE id_krs = $id_krs
             LIMIT 1"
        );


        if (mysqli_num_rows($cek_nilai) > 0) {

            $data_nilai = mysqli_fetch_assoc($cek_nilai);

            $id_nilai = $data_nilai['id_nilai'];


            /*
            |--------------------------------------------------------------------------
            | Update Nilai
            |--------------------------------------------------------------------------
            */

            mysqli_query(
                $conn,
                "UPDATE nilai
                 SET nilai_angka = $nilai_angka,
                     nilai_huruf = '$nilai_huruf'
                 WHERE id_nilai = $id_nilai"
            );

        } else {


            /*
            |--------------------------------------------------------------------------
            | Insert Nilai
            |--------------------------------------------------------------------------
            */

            mysqli_query(
                $conn,
                "INSERT INTO nilai
                 (id_krs, nilai_angka, nilai_huruf)
                 VALUES
                 ($id_krs, $nilai_angka, '$nilai_huruf')"
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Kembali ke Halaman
        |--------------------------------------------------------------------------
        */

        header("Location: input_nilai.php?id_kelas=$id_kelas&success=1");
        exit;
    }
}


/*
|--------------------------------------------------------------------------
| Data Kelas
|--------------------------------------------------------------------------
*/

$query_kelas = mysqli_query(
    $conn,
    "SELECT
        kelas.id_kelas,
        kelas.semester,
        kelas.tahun_ajaran,
        kelas.ruang,
        kelas.nidn,
        mata_kuliah.kode_mk,
        mata_kuliah.nama_mk,
        mata_kuliah.sks,
        dosen.nama_dosen
     FROM kelas
     INNER JOIN mata_kuliah
        ON kelas.id_mk = mata_kuliah.id_mk
     INNER JOIN dosen
        ON kelas.nidn = dosen.nidn
     WHERE kelas.id_kelas = $id_kelas
     LIMIT 1"
);

$kelas = mysqli_fetch_assoc($query_kelas);


/*
|--------------------------------------------------------------------------
| Jika Kelas Tidak Ditemukan
|--------------------------------------------------------------------------
*/

if (!$kelas) {

    echo "Data kelas tidak ditemukan.";
    exit;

}


/*
|--------------------------------------------------------------------------
| Pastikan Dosen Hanya Bisa Mengakses Kelasnya
|--------------------------------------------------------------------------
*/

if ($kelas['nidn'] !== '0012345678') {

    echo "Anda tidak memiliki akses ke kelas ini.";
    exit;

}


/*
|--------------------------------------------------------------------------
| Data Mahasiswa dan Nilai
|--------------------------------------------------------------------------
*/

$query_mahasiswa = mysqli_query(
    $conn,
    "SELECT
        krs.id_krs,
        mahasiswa.nim,
        mahasiswa.nama_mahasiswa,
        nilai.id_nilai,
        nilai.nilai_angka,
        nilai.nilai_huruf
     FROM krs
     INNER JOIN mahasiswa
        ON krs.nim = mahasiswa.nim
     LEFT JOIN nilai
        ON krs.id_krs = nilai.id_krs
     WHERE krs.id_kelas = $id_kelas
     ORDER BY mahasiswa.nim ASC"
);


/*
|--------------------------------------------------------------------------
| Statistik Nilai
|--------------------------------------------------------------------------
*/

/* Total mahasiswa dalam kelas */
$total_mahasiswa = mysqli_num_rows($query_mahasiswa);


/* Total mahasiswa yang sudah memiliki nilai */
$query_sudah_dinilai = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM krs
     INNER JOIN nilai
        ON krs.id_krs = nilai.id_krs
     WHERE krs.id_kelas = $id_kelas"
);

$data_sudah_dinilai = mysqli_fetch_assoc($query_sudah_dinilai);

$total_sudah_dinilai = (int) $data_sudah_dinilai['total'];


/* Total mahasiswa yang belum memiliki nilai */
$total_belum_dinilai =
    $total_mahasiswa - $total_sudah_dinilai;


/* Persentase kelengkapan nilai */
if ($total_mahasiswa > 0) {

    $persentase_nilai = round(
        ($total_sudah_dinilai / $total_mahasiswa) * 100
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
        Input Nilai - Sistem Informasi Perkuliahan
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
         KEMBALI
    ====================================================== -->

    <div class="mb-3">

        <a href="halaman_dosen.php"
           class="btn btn-secondary">

            ← Kembali ke Dashboard

        </a>

    </div>



    <!-- =====================================================
         PESAN
    ====================================================== -->

    <?php if (isset($_GET['success'])) { ?>

        <div class="alert alert-success">

            Nilai berhasil disimpan.

        </div>

    <?php } ?>


    <?php if (isset($error)) { ?>

        <div class="alert alert-danger">

            <?= htmlspecialchars($error); ?>

        </div>

    <?php } ?>



    <!-- =====================================================
         INFORMASI KELAS
    ====================================================== -->

    <div class="card info-card shadow-sm mb-4">

        <div class="card-body p-4">

            <h3 class="fw-bold mb-3">

                Input Nilai Mahasiswa

            </h3>


            <div class="row">

                <div class="col-md-6">

                    <p class="mb-2">

                        <strong>Kode Mata Kuliah:</strong><br>

                        <?= htmlspecialchars($kelas['kode_mk']); ?>

                    </p>


                    <p class="mb-2">

                        <strong>Mata Kuliah:</strong><br>

                        <?= htmlspecialchars($kelas['nama_mk']); ?>

                    </p>


                    <p class="mb-0">

                        <strong>SKS:</strong>

                        <?= htmlspecialchars($kelas['sks']); ?>

                    </p>

                </div>


                <div class="col-md-6">

                    <p class="mb-2">

                        <strong>Dosen:</strong><br>

                        <?= htmlspecialchars($kelas['nama_dosen']); ?>

                    </p>


                    <p class="mb-2">

                        <strong>Semester:</strong><br>

                        <?= htmlspecialchars($kelas['semester']); ?>

                    </p>


                    <p class="mb-0">

                        <strong>Tahun Ajaran:</strong>

                        <?= htmlspecialchars($kelas['tahun_ajaran']); ?>

                        &nbsp; | &nbsp;

                        <strong>Ruang:</strong>

                        <?= htmlspecialchars($kelas['ruang']); ?>

                    </p>

                </div>

            </div>

        </div>

    </div>



    <!-- =====================================================
         STATISTIK NILAI
    ====================================================== -->

    <div class="row g-4 mb-4">


        <!-- Total Mahasiswa -->

        <div class="col-md-3">

            <div class="card stat-card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted">

                        Total Mahasiswa

                    </div>

                    <div class="fs-3 fw-bold text-primary">

                        <?= $total_mahasiswa; ?>

                    </div>

                    <small class="text-muted">

                        Mahasiswa dalam kelas

                    </small>

                </div>

            </div>

        </div>


        <!-- Sudah Dinilai -->

        <div class="col-md-3">

            <div class="card stat-card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted">

                        Sudah Dinilai

                    </div>

                    <div class="fs-3 fw-bold text-success">

                        <?= $total_sudah_dinilai; ?>

                    </div>

                    <small class="text-muted">

                        Mahasiswa sudah memiliki nilai

                    </small>

                </div>

            </div>

        </div>


        <!-- Belum Dinilai -->

        <div class="col-md-3">

            <div class="card stat-card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted">

                        Belum Dinilai

                    </div>

                    <div class="fs-3 fw-bold text-danger">

                        <?= $total_belum_dinilai; ?>

                    </div>

                    <small class="text-muted">

                        Mahasiswa belum memiliki nilai

                    </small>

                </div>

            </div>

        </div>


        <!-- Persentase -->

        <div class="col-md-3">

            <div class="card stat-card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted">

                        Kelengkapan Nilai

                    </div>

                    <div class="fs-3 fw-bold text-info">

                        <?= $persentase_nilai; ?>%

                    </div>

                    <small class="text-muted">

                        Kelengkapan input nilai

                    </small>

                </div>

            </div>

        </div>

    </div>



    <!-- =====================================================
         DAFTAR MAHASISWA
    ====================================================== -->

    <div class="card table-card shadow-sm">

        <div class="card-body p-4">

            <h4 class="fw-bold mb-3">

                Daftar Mahasiswa

            </h4>


            <?php if (mysqli_num_rows($query_mahasiswa) > 0) { ?>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead>

                            <tr>

                                <th width="7%">
                                    No
                                </th>

                                <th>
                                    NIM
                                </th>

                                <th>
                                    Nama Mahasiswa
                                </th>

                                <th width="18%">
                                    Nilai Angka
                                </th>

                                <th width="15%">
                                    Nilai Huruf
                                </th>

                                <th width="18%">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php

                            $no = 1;

                            while ($mahasiswa = mysqli_fetch_assoc($query_mahasiswa)) {

                            ?>

                                <tr>

                                    <td>

                                        <?= $no++; ?>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $mahasiswa['nim']
                                        ); ?>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $mahasiswa['nama_mahasiswa']
                                        ); ?>

                                    </td>


                                    <td>

                                        <form method="POST"
                                              class="d-flex gap-2">

                                            <input type="hidden"
                                                   name="id_krs"
                                                   value="<?= $mahasiswa['id_krs']; ?>">

                                            <input type="number"
                                                   name="nilai_angka"
                                                   class="form-control"
                                                   min="0"
                                                   max="100"
                                                   step="0.01"
                                                   value="<?= $mahasiswa['nilai_angka'] !== null
                                                       ? htmlspecialchars($mahasiswa['nilai_angka'])
                                                       : ''; ?>"
                                                   placeholder="0 - 100"
                                                   required>

                                    </td>


                                    <td class="text-center">

                                        <?php if ($mahasiswa['nilai_huruf'] !== null) { ?>

                                            <span class="badge bg-success">

                                                <?= htmlspecialchars(
                                                    $mahasiswa['nilai_huruf']
                                                ); ?>

                                            </span>

                                        <?php } else { ?>

                                            <span class="badge bg-secondary">

                                                Belum Diinput

                                            </span>

                                        <?php } ?>

                                    </td>


                                    <td>

                                            <button type="submit"
                                                    class="btn btn-primary btn-sm">

                                                Simpan

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            <?php } else { ?>

                <div class="alert alert-info mb-0">

                    Belum terdapat mahasiswa yang mengambil kelas ini.

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