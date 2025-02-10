<?php
session_name('aerickson26_final_logins');
session_start();
/**
 * @var mysqli $db Database Connection
 */
require_once "includes/database.php";
require_once "includes/functions.php";
include "includes/header.php";
?>
<div class="box">
<div class="banner">
    <h1>Hollow Knight Archives </h1>
    <h2>Welcome to the Archives!</h2>
    <p>Here you will find various information about the Hollow Knight game.</p>
</div>
    <div class="card-group">
        <div class="card">
            <img class="card-img-top" src="images/Characters_0022_sprite.png" alt="Hollow Knight Characters">
            <div class="card-body">
                <h5 class="card-title">Hollow Knight Character</h5>
                <p class="card-text">Discover the various NPCs that are found throughout Hollow Knight.</p>
                <a href="character.php" class=" btn btn-primary">Find Characters</a>
            </div>
        </div>
        <div class="card">
            <img class="card-img-top" src="images/Bestiary_Grimm_Nightmare_boss.png" alt="Hollow Knight Enemies">
            <div class="card-body">
                <h5 class="card-title">Hollow Knight Enemies</h5>
                <p class="card-text">Look through the numerous bosses that you can take on in Hollow Knight</p>
                <a href="character.php" class=" btn btn-primary">Find Characters</a>
            </div>
        </div>
        <div class="card">
            <img class="card-img-top" src="images/Characters_0017_sprite.png" alt="Discussion">
            <div class="card-body">
                <h5 class="card-title">Discussion</h5>
                <p class="card-text">Discuss with other community members about characters, puzzles, items, and other game aspects.</p>
                <a href="character-discussion.php" class=" btn btn-primary">Discuss</a>
            </div>
        </div>
    </div>
</div>

<?php
include "includes/footer.php";
mysqli_close($db);
?>