<?php
// $host = "localhost";
// $user = "lsecuhdx_course";
// $pass = "Chris@Months1212";
// $db = "lsecuhdx_course";

$host = "localhost";
$user = "root";
$pass = "";
$db = "lsecuhdx_course";

$conn = mysqli_connect($host, $user, $pass, $db);



if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
