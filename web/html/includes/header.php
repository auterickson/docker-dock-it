<?php
// start the session

include "includes/database.php";

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Site</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="#">My Site</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExample04">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item ">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="character.php">Characters</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="enemy.php">Enemies</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="character-discussion.php">Discussion</a>
            </li>
        </ul>
        <div class="text-light">
            <?php
            if(isset($_SESSION['users']) && $_SESSION['users']['userId']){
                echo 'Welcome ' . $_SESSION['users']['userId'] .
                    ' (<a href="sign-in.php?logout"> Logout </a>)';
            }else{
                echo '<a href="sign-in.php">Login</a>';
            }
            ?>
        </div>
    </div>
</nav>
