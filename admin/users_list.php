<?php
session_start();
require '../config/config.php';
require "../pre.php";

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in']) && empty($_SESSION['role'])) {
    header("location: login.php");
    exit();
}
if ($_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}

if (!empty($_POST['search'])) {
    setcookie('search', $_POST['search'], time() + (86400 + 30), '/');
} else {
    if (empty($_GET['pageno'])) {
        unset($_COOKIE['search']);
        setcookie('search', null, -1, '/');
    }
}

?>

<?php

############HEADER###############
include('header.php');
?>
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">User Listings</h3>
                    </div>
                    <!-- /.card-header -->

                    <?php
                    ##################pagination and retrieve #########################
                    if (!empty($_GET['pageno'])) {
                        $pageno = $_GET['pageno'];
                    } else {
                        $pageno = 1;
                    }
                    $num_rec = 3;
                    $offset = ($pageno - 1) * $num_rec;
                    if (empty($_POST['search']) && empty($_COOKIE['search'])) {

                        $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
                        $stmt->execute();
                        $rawresult = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $total_posts = count($rawresult);
                        $total_pages = ceil(count($rawresult) / $num_rec);

                        $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset,$num_rec");
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } else {

                        $userInput = !empty($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
                        $stmt = $pdo->prepare("SELECT * FROM users  WHERE name LIKE '%$userInput%' ORDER BY id DESC");
                        $stmt->execute();
                        $rawresult = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $total_pages = ceil(count($rawresult) / $num_rec);

                        $stmt = $pdo->prepare("SELECT * FROM users  WHERE name LIKE '%$userInput%' ORDER BY id DESC LIMIT $offset,$num_rec");
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }


                    ?>

                    <div class="card-body">

                        <div>
                            <a href="add_user.php" type="button" class="btn btn-primary">New User Add</a>
                        </div>
                        <br>
                        <table class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                if ($result) {
                                    //  $i=1;
                                    foreach ($result as $value) {
                                        $i = $value['id'];
                                ?>
                                        <tr>
                                            <td><?php echo "$i"; ?></td>
                                            <td><?php echo $value['name']; ?></td>
                                            <td>
                                                <?php echo $value['email']; ?>
                                            </td>
                                            <td>
                                                <?php echo $value['role'] == 1 ? "admin" :  'user'; ?>
                                            </td>
                                            <td style="width: 15%">
                                                <a href="edit_user.php?id=<?php echo $value['id'] ?>" class="btn btn-warning">Edit</a>
                                                <a href="delete_user.php?id=<?php echo $value['id'] ?>" onclick="return confirm('Are you sure you want to delete this item?');" type="button" class="btn btn-danger">Delete</a>

                                            </td>
                                        </tr>
                                <?php
                                        $i++;
                                    }
                                }
                                ?>

                            </tbody>
                        </table>
                        <br>

                        <nav aria-label="Page navigation example ">
                            <ul class="pagination float-right">
                                <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                                <li class="page-item <?php echo  $pageno <= 1 ? 'disabled' :  "" ?>"><a class="page-link" href="<?php echo $pageno <= 1 ? "#" : "?pageno=" . ($pageno - 1); ?>">Previous</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#"><?php echo $pageno; ?></a>
                                </li>
                                <li class="page-item <?php echo  $pageno >= $total_pages ? 'disabled' :  "" ?>">
                                    <a class="page-link" href="<?php echo $pageno >= $total_pages ? "#" : "?pageno=" . ($pageno + 1); ?>">Next</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a>
                                </li>
                            </ul>
                        </nav>
                        <!-- pagination navigatin -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<?php
############FOOTER###############
include('footer.php');
?>