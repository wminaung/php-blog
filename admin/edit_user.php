<?php
session_start();
require '../config/config.php';
require "../pre.php";

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php");
};

if ($_POST) {
    $name =  $_POST['name'];
    $email =  $_POST['email'];
    if (!empty($_POST['role'])) {
        $role = 1;
    } else {
        $role = 0;
    }
    $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',role='$role' WHERE id=" . $_GET['id']);
    $result = $stmt->execute();
    if ($result) {
        echo "<script>alert('Successfully Updated user');window.location.href='users_list.php'</script>";
    }
} else {
    // $stmt = $pdo->prepare("UPDATE posts SET name='$name',email='$email',role='$role' WHERE id=" . $_GET['id']);
    // $result = $stmt->execute();
    // if ($result) {
    //     echo "<script>alert('Successfully Updated');window.location.href='index.php'</script>";
    // }
}




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
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" name="name" class="form-control" value='<?php echo $result['name'] ?>' required />
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" name="email" class="form-control" value='<?php echo $result['email'] ?>' required />
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