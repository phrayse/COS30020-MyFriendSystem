<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="author" content="STUID" />
    <link href="style.css" rel="stylesheet" />
    <title>My Friend System</title>
</head>
<body>
<header>
    <h1>My Friend System<br>Assignment 3</h1>
    <?php if(isset($_SESSION["name"])) { echo "Welcome back, " . $_SESSION["name"]; } ?>
</header>