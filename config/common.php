<!-- Font Awesome Icons -->
<link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="../dist/css/adminlte.min.css">
<?php

if (empty($_SESSION['_token'])) {

    if (function_exists('random_bytes')) {
        $_SESSION['_token'] = bin2hex(random_bytes(32));
    } elseif (function_exists('mcrypt_create_iv')) {
        $_SESSION['_token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
    } else {
        $_SESSION['_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!hash_equals($_SESSION['_token'], $_POST['_token'])) {
        echo "<h1 class='text-danger text-center' style='background: black'><b>Invalid CSRF token</b></h1>";
        die();
    } else {
        unset($_SESSION['_token']);
    }
}
