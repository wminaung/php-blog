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

    if (empty($_POST['name']) || empty($_POST['email'])) {
        if (empty($_POST['name'])) {
            $nameError = "*Name cannot be null";
        }
        if (empty($_POST['email'])) {
            $emailError = "*Email cannot be null";
        }
    } elseif (!empty($_POST['password']) && strlen($_POST['password']) < 4) {
        $passwordError = "*Password must have at least 4 characters";
    } else {
        $id = $_GET['id'];
        $name =  $_POST['name'];
        $email =  $_POST['email'];
        $password = $_POST['password'];
        $password = password_hash($password, PASSWORD_DEFAULT);

        if (!empty($_POST['role'])) {
            $role = 1;
        } else {
            $role = 0;
        }

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
        $stmt->execute(array(':email' => $email, ':id' => $id));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            echo "<script>alert('Email duplicated');</script>";
        } else {
            if (!empty($password)) {
                $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',role='$role',password='$password' WHERE id=" . $_GET['id']);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',role='$role' WHERE id=" . $_GET['id']);
            }
            $result = $stmt->execute();
            if ($result) {
                echo "<script>alert('Successfully Updated user');window.location.href='users_list.php'</script>";
            }
        }
    }
}
// $stmt = $pdo->prepare("UPDATE posts SET name='$name',email='$email',role='$role' WHERE id=" . $_GET['id']);
// $result = $stmt->execute();
// if ($result) {
//     echo "<script>alert('Successfully Updated');window.location.href='index.php'</script>";
// }


$stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
$stmt->bindValue(':id', $_GET['id']);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$role = $result['role'];

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
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                            <div class="form-group">
                                <label for="">Name</label>
                                <p class="text-danger"><?php echo empty($nameError) ? "" : $nameError ?></p>
                                <input type="text" name="name" class="form-control" value='<?php echo escape($result['name']) ?>' />
                                <hr>
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <p class="text-danger"><?php echo empty($emailError) ? "" : $emailError ?></p>
                                <input type="email" name="email" class="form-control" value='<?php echo escape($result['email']) ?>' />
                                <hr>
                            </div>
                            <div class="form-group">

                                <label for="">Password</label>

                                <p class="text-danger"><?php echo empty($passwordError) ? "" : $passwordError ?></p>
                                <span style="font-size: 10px" class="text-info">The user is already has a password</span>
                                <input type="password" class="form-control" placeholder="Password" name="password">

                                <hr>
                            </div>
                            <div class="form-group">
                                <label for="">Admin</label><span>&nbsp;&nbsp;</span>
                                <input type="checkbox" name="role" id="Admin" />
                                <?php echo $role == 1 ?  "<script> document.getElementById('Admin').checked= true;</script>" : "<script>document.getElementById('Admin').checked= false;</script>" ?>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" value="UPDATE" class="btn btn-success ">
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