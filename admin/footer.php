<?php
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in']) && empty($_SESSION['role'])) {
    header("location: login.php");
    exit();
}
if ($_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}
?>

</div>
<!-- /.content-wrapper -->





<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
        <a href="logout.php" type="button" class="btn btn-default">Logout</a>
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2022 <a href="#">WinMinAung </a>.</strong> All rights reserved.
</footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
</body>

</html>