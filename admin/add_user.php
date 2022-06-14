<?php
session_start();
require '../config/config.php';
require '../config/common.php';
require "../pre.php";

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in']) && empty($_SESSION['role'])) {
    header("location: login.php");
    exit();
}
if ($_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}



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
        $password = password_hash($password, PASSWORD_DEFAULT);



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

            $stmt = $pdo->prepare("INSERT INTO users(name,email,role,password)
                                    VALUES (:name, :email, :role,:password)
                                    ");
            $result = $stmt->execute(
                array(
                    ':name' => $name, ':email' => $email,
                    ':role' => $role, ':password' => $password
                )
            );
            if ($result) {
                echo "<script>alert('Successfully Added a User');window.location.href='users_list.php'</script>";
            }
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
                            <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                            <div class="form-group">
                                <label for="">Name</label>
                                <p class="text-danger"><?php echo empty($nameError) ? "" : $nameError ?></p>

                                <input type="text" name="name" id="" class="form-control">
                                <hr>
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <p class="text-danger"><?php echo empty($emailError) ? "" : $emailError ?></p>

                                <input type="email" name="email" id="" class="form-control">
                                <hr>
                            </div>
                            <div class="form-group">

                                <label for="">Password</label>
                                <p class="text-danger"><?php echo empty($passwordError) ? "" : $passwordError ?></p>

                                <input type="password" class="form-control" placeholder="Password" name="password">

                                <hr>
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