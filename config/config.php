<?php

define('MYSQL_USER', 'root');
define('MYSQL_PASSWORD', '');
define('MYSQL_HOST', 'localhost');
define('MYSQL_DATABASE', 'blog');

$pdoOptions = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
);

## connection code
$pdo = new PDO(
    'mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DATABASE,
    MYSQL_USER,
    MYSQL_PASSWORD,
    $pdoOptions

);

function escape($html)
{
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}
