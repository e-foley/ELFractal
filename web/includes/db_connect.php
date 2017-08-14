<!--See https://www.binpress.com/tutorial/using-php-with-mysql-the-right-way/17 for info.-->

<?php
define('PROJECT_ROOT', '/srv/disk8/2412696/www/elfractal.com/');

function db_connect() {

    // Define connection as a static variable, to avoid connecting more than once. 
    static $con;

    // Try and connect to the database, if a connection has not been established yet.
    if(!isset($con)) {
        // Load configuration as an array.
        $config = parse_ini_file(PROJECT_ROOT.'../private/config.ini');
        $con = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
    }

    // If connection was not successful, handle the error
    if($con === false) {
        // Handle error - notify administrator, log to a file, show an error screen, etc.
        return mysqli_connect_error(); 
    }
    return $con;
}

// Connect to the database
$con = db_connect();

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
