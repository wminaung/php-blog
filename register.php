<?php
session_start();
require "config/config.php";
require "pre.php";


if ($_POST) {

    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 4) {
        if (empty($_POST['name'])) {
            $nameError = "*Name cannot be null";
        }
        if (empty($_POST['email'])) {
            $emailError = "*Email cannot be null";
        }
        if (empty($_POST['password'])) {
            $passwordError = "*Password cannot be null";
        }
        if (strlen($_POST['password']) < 4) {
            $passwordError = "*Password shound 4 character atleast";
        }
    } else {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];


        if ($name != '' and $email != '' and $password != '') {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                echo "<script>alert('This email is already exist! try again');window.location.href='register.php';</script>";
            } else {

                $stmt = $pdo->prepare("INSERT INTO users (name,password,email) VALUES (:name,:password,:email)");

                $result = $stmt->execute(
                    array(
                        ':name' => $name, ':password' => $password, ':email' => $email
                    )
                );


                if ($result) {
                    echo "<script>alert('Successfully Register! You can now login.');window.location.href='login.php';</script>";
                    exit();
                }
                echo "<script>alert('Incorrect credentials');window.location.href='register.php';</script>";
            }
        } else {
            echo "<script>alert('Set all field for register');window.location.href='register.php';</script>";
            exit();
        }
    }
}


?>

<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Blog | Register</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>Blog</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <h3 class="login-box-msg">Register Page</h3>

                <form action="register.php" method="post">
                    <div>
                        <p class="text-danger"><?php echo empty($nameError) ? "" : $nameError ?></p>
                        <div class="input-group  mb-3">
                            <input type="name" class="form-control" placeholder="Name" name="name">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-danger"><?php echo empty($emailError) ? "" : $emailError ?></p>
                        <div class="input-group mb-3">

                            <input type="email" class="form-control" placeholder="Email" name="email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-danger"><?php echo empty($passwordError) ? "" : $passwordError ?></p>

                        <div class="input-group mb-3">

                            <input type="password" class="form-control" placeholder="Password" name="password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                        <div class="col-4">
                        </div>
                        <div class="col-4">
                            <a href="login.php" class="btn btn-secondary btn-block">Login</a>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>


                <!-- <p class="mb-1">
                    <a href="forgot-password.html">I forgot my password</a>
                </p>
                <p class="mb-0">
                    <a href="register.html" class="text-center">Register a new membership</a>
                </p> -->
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>

</body>

</html>