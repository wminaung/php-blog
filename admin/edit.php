<?php
session_start();
require '../config/config.php';
require "../pre.php";

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php");
};

if ($_POST) {
    $title =  $_POST['title'];
    $content =  $_POST['content'];

    if ($_FILES['image']['name'] != null) {
        $image = $_FILES['image']['name'];
        $file = 'images/' . $image;
        $imageType = pathinfo($file, PATHINFO_EXTENSION);

        if ($imageType != "png" && $imageType != "jpg" && $imageType != "jpeg") {
            echo "<script>alert('Image must be png,jpg,jpeg');</script>";
        } else {
            move_uploaded_file($_FILES['image']['tmp_name'], $file);

            $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content',image='$image' WHERE id=" . $_GET['id']);
            $result = $stmt->execute();
            if ($result) {
                echo "<script>alert('Successfully Updated');window.location.href='index.php'</script>";
            }
        }
    } else {
        $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content' WHERE id=" . $_GET['id']);
        $result = $stmt->execute();
        if ($result) {
            echo "<script>alert('Successfully Updated');window.location.href='index.php'</script>";
        }
    }
}



$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=:id");
$stmt->bindValue(':id', $_GET['id']);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

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
                                <label for="">Title</label>
                                <input type="text" name="title" class="form-control" value='<?php echo $result['title'] ?>' required />
                            </div>
                            <div class="form-group">
                                <label for="">Content</label>
                                <textarea name="content" id="" cols="30" rows="3" class="form-control"><?php echo $result['content']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Image</label><br>
                                <input type="file" name="image">

                                <img src='images/<?php echo $result['image']; ?>' width="100" alt="photo">

                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" value="UPDATE" class="btn btn-success ">
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