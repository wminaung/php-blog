<?php
session_start();
require '../config/config.php';
require "../pre.php";

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php");
}
if ($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    if (!empty($_POST['role'])) {
        $role = 1;
    } else {
        $role = 0;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        echo "<script>alert('Email Duplicated');</script>";
        exit();
    } else {

        $stmt = $pdo->prepare("INSERT INTO users(name,email,role)
                                VALUES (:name, :email, :role)
                                ");
        $result = $stmt->execute(
            array(
                ':name' => $name, ':email' => $email,
                ':role' => $role
            )
        );
        if ($result) {
            echo "<script>alert('Successfully Added a User');window.location.href='users_list.php'</script>";
        }
    }
}




?>
<?php
include('header.php');
?>
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" name="name" id="" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" name="email" id="" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="admin">Admin</label>
                                <span>&nbsp;&nbsp;&nbsp;</span>
                                <input type="checkbox" name="role" class="btn" id="admin">
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" value="ADD USER" class="btn btn-success ">
                                <a href="users_list.php" class="btn btn-warning">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card -->

            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<?php
include('footer.php');
?>