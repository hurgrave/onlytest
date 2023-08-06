<?php
//echo "Connected!";
$link = mysqli_connect('localhost','root','','testjob') or die(mysqli_error($link));
$w = mysqli_query ($link, "SET NAMES 'utf8'");
if (mysqli_connect_errno())
    {
        echo "Database connection error ".mysqli_connect_errno().": ".mysqli_connect_error();
        exit();
    } 
?>