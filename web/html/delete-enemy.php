<?php
/**
 * @var mysqli $db Database Connection
 */
session_name('aerickson_final');
session_start();
$_SESSION['csrf_token'] = $_SESSION['csrf_token'] ?? md5(uniqid());

require_once "includes/database.php"; // Assuming this file includes the database connection
require_once "includes/functions.php";

// get character ID from URL
$id = $_GET['id'] ?? '1';

// Fetch character details from the database
$query = 'SELECT enemyId, name, description FROM Enemies WHERE enemyId = ?';
$stmt = mysqli_prepare($db, $query) or die("Error fetching character details.");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $enemyId, $name, $description);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Initialize $characterId before HTML section
//$enemyId = $enemyId ?? '';

?>
<div class="box">
    <div class="container">
        <a href="enemy.php">&larr; Back</a>
        <h1>Delete Place </h1>

        <?php
        if(isset($_POST['submit'])) {
            // Now $characterId is defined properly
            $enemyId = $_POST['enemyId'];
            //$cityId = $_POST['city'];

            // query to add place
            $query = "DELETE FROM `Enemies` 
            WHERE `Enemies`.`enemyId` = $enemyId
            LIMIT 1;";

            // execute query
            $result = mysqli_query($db, $query) or die("Error deleting Enemy.");
            //redirect the user
            header('Location: enemy.php?id=' . $enemyId);
        }
        ?>

        <form method="post">
            <p>
                Are you sure that you want to delete <b><?= $name ?></b>
            </p>

            <!-- Use $characterId properly in the form -->
            <input type="hidden" name="characterId" value="<?= $enemyId ?>">

            <button type="submit"  class="btn btn-danger" name="submit"> Delete Enemy</button>
        </form>
    </div>
</div>


<?php
include "includes/footer.php";
// close database connection (put in footer to avoid doing multiple times)
mysqli_close($db);
?>