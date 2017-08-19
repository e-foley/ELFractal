<?php
require_once('./includes/db_connect.php');

$result = mysqli_query($con,"SELECT `title` FROM `Fractal List` WHERE ID=3");

$foo = mysqli_fetch_array($result);

echo($foo['title']);

echo(__DIR__);

// $result = db_query("SELECT `name`,`email` FROM `users` WHERE id=5");

// Load configuration as an array. Use the actual location of your configuration file
// $config = parse_ini_file('../private/config.ini');

// Try and connect to the database
// $connection = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);

// If connection was not successful, handle the error
// if($connection === false) {
    // Handle error - notify administrator, log to a file, show an error screen, etc.
    // echo "Ohhh.";
// } else {
//    echo "Yaya?";
// }


?>