<?php
session_start();
require '../config/config.php';
require "../pre.php";

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php");
}
if ($_POST) {
    $file = 'images/' . $_FILES['image']['name'];
    $imageType = pathinfo($file, PATHINFO_EXTENSION);

    if ($imageType != "png" && $imageType != "jpg" && $imageType != "jpeg") {
        echo "<script>alert('Image must be png,jpg,jpeg');</script>";
    } else {
        move_uploaded_file($_FILES['image']['tmp_name'], $file);

        $stmt = $pdo->prepare("INSERT INTO posts(title,content,image,author_id)
            VALUES (:title, :content, :image, :author_id)
        ");
        $result = $stmt->execute(
            array(
                ':title' => $_POST['title'], ':content' => $_POST['content'], ':image' => $_FILES['image']['name'], ':author_id' => $_SESSION['user_id']
            )
        );
        if ($result) {
            echo "<script>alert('Successfully Added');window.location.href='index.php'</script>";
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
                        <form action="add.php" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="">Title</label>
                                <input type="text" name="title" id="" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="">Content</label>
                                <textarea name="content" id="" cols="30" rows="3" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Image</label><br>
                                <input type="file" name="image" id="" required>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" value="ADD" class="btn btn-success ">
                                <a href="index.php" class="btn btn-warning">Back</a>
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