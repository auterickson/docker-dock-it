<?php
/**
 * @var mysqli $db Database Connection
 */
session_name('aerickson_final');
session_start();
$_SESSION['csrf_token'] = $_SESSION['csrf_token'] ?? md5(uniqid());

require_once "includes/database.php"; // Assuming this file includes the database connection
require_once "includes/functions.php";
include "includes/header.php";

// get character ID from URL
$id = $_GET['id'] ?? '1';

// Fetch character details from the database
$query = 'SELECT characterId, name, description FROM Characters WHERE characterId = ?';
$stmt = mysqli_prepare($db, $query) or die("Error fetching character details.");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $characterId, $name, $description);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Initialize $characterId before HTML section
$characterId = $characterId ?? '';

?>
<div class="box">
<div class="container">
    <a href="character.php">&larr; Back</a>
    <h1>Delete Place </h1>

    <?php
    if(isset($_POST['submit'])) {
        // Now $characterId is defined properly
        $characterId = $_POST['characterId'];
        //$cityId = $_POST['city'];

        // query to add place
        $query = "DELETE FROM `Characters` 
            WHERE `Characters`.`characterId` = $characterId
            LIMIT 1;";

        // execute query
        $result = mysqli_query($db, $query) or die("Error delete character.");
        //redirect the user
        header('Location: character.php?id=' . $characterId);
    }
    ?>

    <form method="post">
        <p>
            Are you sure that you want to delete <b><?= $name ?></b>
        </p>

        <!-- Use $characterId properly in the form -->
        <input type="hidden" name="characterId" value="<?= $characterId ?>">

        <button type="submit" class="btn btn-danger" name="submit"> Delete Character</button>
    </form>
</div>
</div>

<?php
include "includes/footer.php";
// close database connection (put in footer to avoid doing multiple times)
mysqli_close($db);
?>
