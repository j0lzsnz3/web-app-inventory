<?php 
session_start();

if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
} else {
    header("Location: home.php");
}
