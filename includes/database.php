<?php
$host       = "sql.hosted.hr.nl";
$database   = "prj_2023_2024_ressys_t1";
$user       = "prj_2023_2024_ressys_t1";
$password   = "ohyuleek";

$db = mysqli_connect($host, $user, $password, $database)
or die("Error: " . mysqli_connect_error());
