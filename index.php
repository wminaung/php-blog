<?php
session_start();
require 'config/config.php';
require "pre.php";

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Blog user</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style>
        .blog-img {
            width: 100%;
            height: 250px;
            object-fit: contain;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="container-fluid">
        <a href="logout.php" class="btn btn-primary float-right">logout</a>
        <!-- Content Wrapper. Contains page content -->
        <div class="">
            <!-- Content Header (Page header) -->
            <section class="content-header text-center">
                <div class="container-fluid">
                    <h1>Blog site</h1>
                </div><!-- /.container-fluid -->
            </section>


            <?php

            if (!empty($_GET['pageno'])) {
                $pageno = $_GET['pageno'];
            } else {
                $pageno = 1;
            }
            $number_rec = 3;

            $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $total_posts = count($result);

            $total_pages = ceil($total_posts / $number_rec);

            $offset = ($pageno - 1) * $number_rec;




            ?>
            <!-- Pagination  -->
            <nav aria-label="Page navigation example" class="d-flex justify-content-end">
                <ul class="pagination">
                    <li class="page-item <?php echo $pageno <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link " href="?pageno=1">First</a>
                    </li>
                    <li class="page-item <?php echo $pageno <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?php echo $pageno <= 1 ? '#' : '?pageno=' . ($pageno - 1); ?>">Previous</a>
                    </li>

                    <li class="page-item">
                        <a class="page-link" href="#">
                            <?php echo $pageno >= 1 && $pageno <= $total_pages ? $pageno : '' ?>
                        </a>
                    </li>

                    <li class="page-item <?php echo  $pageno >= $total_pages ? 'disabled' :  "" ?>">
                        <a class="page-link" href="?pageno=<?php echo $pageno + 1 ?>">Next</a>
                    </li>
                    <li class="page-item <?php echo $pageno >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?pageno=<?php echo $total_pages ?>">Last</a>
                    </li>
                </ul>
            </nav>
            <!-- Pagination end -->

            <?php
            $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$number_rec");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
            // $stmt->execute();
            // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <!-- Main content -->
            <section class="content">

                <div class="row">
                    <?php
                    if ($result) {
                        //  $i=1;
                        foreach ($result as $value) {
                            $i = 1;
                    ?>
                            <div class="col-md-4">
                                <!-- Box Comment -->
                                <div class="card card-widget">
                                    <div class="card-header">
                                        <div class="card-title text-center" style="float: none;">
                                            <h4><?php echo $value["title"]; ?></h4>
                                        </div>

                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">

                                        <a href="blogdetail.php?id=<?php echo $value['id']  ?>">
                                            <img class="img-fluid pad blog-img" src='./admin/images/<?php echo $value['image']; ?>' alt="photo">
                                        </a>
                                        <p><?php echo substr($value['content'], 0, 50) ?></p>

                                    </div>
                                    <!-- /.card-body -->

                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                    <?php
                            $i++;
                        }
                    }
                    ?>

                </div>
                <!-- /.row -->
            </section>
            <!-- /.content -->
            <br><br><br>
            <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
                <i class="fas fa-chevron-up"></i>
            </a>
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer ml-0">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline ">
                <a href="logout.php" type="button" class="btn btn-default">Logout</a>
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2022 <a href="#">WinMinAung </a>.</strong> All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
</body>

</html>