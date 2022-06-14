<?php
session_start();
require 'config/config.php';
require "pre.php";

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php");
}




if (!empty($_GET['id'])) {
    $blogId = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id=$blogId");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmtcmt = $pdo->prepare("SELECT * FROM comments WHERE post_id=$blogId");
    $stmtcmt->execute();
    $cmResult = $stmtcmt->fetchAll(PDO::FETCH_ASSOC);

    // if (!empty($cmResult)) {
    //     pre($cmResult);
    //     exit();
    //     // $authorId = $cmResult[0]['author_id'];
    //     // $stmtau = $pdo->prepare("SELECT * FROM users WHERE id=$authorId");
    //     // $stmtau->execute();
    //     // $auResult = $stmtau->fetch(PDO::FETCH_ASSOC);
    // }


    if (!empty($_POST)) {

        if (empty($_POST['comment'])) {
            if (empty($_POST['comment'])) {
                $commentError = "*Comment cannot be null";
            }
        } else {
            $comment = $_POST['comment'];

            $stmt = $pdo->prepare("INSERT INTO comments (content,author_id,post_id) VALUES (:content,:author_id,:post_id)");

            $result = $stmt->execute(
                array(
                    ':content' => $comment, ':author_id' => $_SESSION['user_id'], ':post_id' => $blogId
                )
            );

            if ($result) {
                header('Location: blogdetail.php?id=' . $blogId);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>blog detail</title>
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
</head>

<body class="hold-transition sidebar-mini">
    <div class="container-fluid">
        <!-- Content Wrapper. Contains page content -->
        <div class="">
            <!-- Content Header (Page header) -->
            <section class="content-header text-center">
                <div class="container-fluid">
                    <h1><?php echo $result['title'] ?></h1>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">

                <div class="row">
                    <div class="col-md-12">
                        <!-- Box Comment -->
                        <div class="card card-widget">

                            <div class="card-body">
                                <img class="img-fluid pad" src="admin/images/<?php echo $result['image'] ?>" alt="Photo">
                                <br><br>
                                <p><?php echo $result['content'] ?></p>

                            </div>
                            <!-- /.card-body -->
                            <h1>Comment</h1>
                            <a href="./" class="btn btn-default" type="button"><b>Go Back</b></a>
                            <hr>
                            <?php
                            if ($cmResult) {

                                foreach ($cmResult as $value) {

                                    $authorId = $value['author_id'];
                                    $stmtau = $pdo->prepare("SELECT * FROM users WHERE id=$authorId");
                                    $stmtau->execute();
                                    $auResult = $stmtau->fetch(PDO::FETCH_ASSOC);

                            ?>
                                    <div class="card-footer card-comments">
                                        <div class="card-comment">

                                            <div class="comment-text" style="margin-left:0px;">
                                                <span class="username">
                                                    <?php echo empty($auResult['name']) ? "" : $auResult['name'] ?>
                                                    <span class="text-muted float-right">
                                                        <?php echo $value['created_at']; ?>
                                                    </span>
                                                </span><!-- /.username -->
                                                <?php echo $value['content']; ?>
                                            </div>
                                            <!-- /.comment-text -->
                                        </div>
                                        <!-- /.card-comment -->
                                    </div>
                            <?php
                                }
                            }
                            ?>
                            <!-- /.card-footer -->
                            <div class="card-footer">
                                <form action="" method="post">

                                    <!-- .img-push is used to add margin to elements next to floating images -->
                                    <div class="img-push">
                                        <p class="text-danger"><?php echo empty($commentError) ? "" : $commentError ?></p>

                                        <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                                    </div>
                                </form>
                            </div>
                            <!-- /.card-footer -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </section>
            <!-- /.content -->

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