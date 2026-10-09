
<?php

session_start();

require_once "config/database.php";

if (isset($_SESSION['username'])) {

    if ($_SESSION['role'] === 'admin') {
        header("Location: dashboard.php");
        exit;
    }

    if ($_SESSION['role'] === 'dosen') {
        header("Location: halaman_dosen.php");
        exit;
    }

    if ($_SESSION['role'] === 'mahasiswa') {
        header("Location: halaman_mahasiswa.php");
        exit;
    }
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    $query = "SELECT * FROM users
              WHERE username = '$username'
              LIMIT 1";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {

        $user = mysqli_fetch_assoc($result);

        if ($password === $user['password']) {

            // Cek apakah role yang dipilih sesuai dengan akun
            if ($role !== $user['role']) {

                $error = "Role yang dipilih tidak sesuai dengan akun.";

            } else {

                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header("Location: dashboard.php");
                    exit;
                }

                if ($user['role'] === 'dosen') {
                    header("Location: halaman_dosen.php");
                    exit;
                }

                if ($user['role'] === 'mahasiswa') {
                    header("Location: halaman_mahasiswa.php");
                    exit;
                }
            }

        } else {

            $error = "Username atau password salah.";

        }

    } else {

        $error = "Username atau password salah.";

    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login - Sistem Informasi Perkuliahan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>

        body {
            background: linear-gradient(135deg, #93c5fd, #dbeafe);
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 15px;
        }

        .login-header {
            background-color: #1e3a8a;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 30px;
            text-align: center;
        }

        .login-body {
            padding: 30px;
        }

        .btn-login {
            background-color: #1e3a8a;
            border-color: #1e3a8a;
            color: white;
        }

        .btn-login:hover {
            background-color: #172f6d;
            border-color: #172f6d;
            color: white;
        }

        .form-control,
        .form-select {
            padding: 11px 12px;
        }

    </style>

</head>

<body>

    <div class="container">

        <div class="login-wrapper">

            <div class="card login-card shadow">

                <!-- Header -->
                <div class="login-header">

                    <h3 class="fw-bold mb-2">
                        Sistem Informasi Perkuliahan
                    </h3>

                    <p class="mb-0">
                        Silakan masuk untuk melanjutkan
                    </p>

                </div>


                <!-- Body -->
                <div class="login-body">

                    <?php if ($error != "") { ?>

                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error); ?>
                        </div>

                    <?php } ?>


                    <form method="POST">

                        <!-- Username -->
                        <div class="mb-3">

                            <label for="username"
                                   class="form-label fw-semibold">
                                Username
                            </label>

                            <input type="text"
                                   class="form-control"
                                   id="username"
                                   name="username"
                                   placeholder="Masukkan username"
                                   value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                                   required
                                   autofocus>

                        </div>


                        <!-- Password -->
                        <div class="mb-3">

                            <label for="password"
                                   class="form-label fw-semibold">
                                Password
                            </label>

                            <input type="password"
                                   class="form-control"
                                   id="password"
                                   name="password"
                                   placeholder="Masukkan password"
                                   required>

                        </div>


                        <!-- Role -->
                        <div class="mb-4">

                            <label for="role"
                                   class="form-label fw-semibold">
                                Role
                            </label>

                            <select name="role"
                                    id="role"
                                    class="form-select"
                                    required>

                                <option value="">
                                    -- Pilih Role --
                                </option>

                                <option value="admin"
                                    <?= (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : ''; ?>>
                                    Admin
                                </option>

                                <option value="dosen"
                                    <?= (isset($_POST['role']) && $_POST['role'] === 'dosen') ? 'selected' : ''; ?>>
                                    Dosen
                                </option>

                                <option value="mahasiswa"
                                    <?= (isset($_POST['role']) && $_POST['role'] === 'mahasiswa') ? 'selected' : ''; ?>>
                                    Mahasiswa
                                </option>

                            </select>

                        </div>


                        <!-- Button -->
                        <div class="d-grid">

                            <button type="submit"
                                    class="btn btn-login py-2">
                                Login
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</body>

</html>
```
