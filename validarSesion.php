<?php
session_start();
$varemail = $_SESSION["Usuario"];
if ($varemail == null || $varemail == "") {
    header("location:https://the-fuentes-corp-ws1-460518334160.us-central1.run.app/login.php");
    die();
}
?>