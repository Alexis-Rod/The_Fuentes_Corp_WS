<?php
session_start();
$varemail = $_SESSION["Usuario"];
if ($varemail == null || $varemail == "") {
    header("location:https:login.php");
    die();
}
?>