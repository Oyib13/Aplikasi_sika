<?php
session_start();

// Kalau sudah login, redirect
if (isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sika Login</title>

    <!-- CSS -->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="row justify-content-center w-100">
        <div class="col-xl-8 col-lg-10 col-md-9">

            <div class="card o-hidden border-0 shadow-lg">
                <div class="card-body p-0">
                    <div class="row">

                        <div class="col-lg-6 d-none d-lg-flex bg-login-image
                            justify-content-center align-items-center flex-column">
                            <img src="assets/img/stmik.png" class="img-fluid mb-3" style="max-width:30%;">
                            <h4 class="text-dark font-weight-bold">STMIK LOMBOK</h4>
                        </div>

                        <div class="col-lg-6">
                            <div class="p-5">

                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Login</h1>
                                </div>

                                <form class="user" action="proses_login.php" method="POST">
                                    <div class="form-group">
                                        <input type="text" name="username"
                                               class="form-control form-control-user"
                                               placeholder="Username" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="pasword" name="pasword"
                                               class="form-control form-control-user"
                                               placeholder="Password" required>
                                    </div>

                                    <button type="submit" name="login"
                                            class="btn btn-primary btn-user btn-block">
                                        Login
                                    </button>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- JS -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

</body>
</html>
