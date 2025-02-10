<?php
session_name('aerickson26_final_logins');
session_start();
/**
 * @var mysqli $db Database Connection
 */
require_once "includes/database.php";
require_once "includes/functions.php";
include "includes/header.php";

// Check if the user is logged in
$userLoggedIn = isset($_SESSION['users']['userId']);
$id = $_GET['id'] ?? '1';

$search = isset($_GET['search']) ? mysqli_real_escape_string($db, $_GET['search']) : '';
$sortBy = $_GET['sort'] ?? 'name';
$dir = $_GET['dir'] ?? 'ASC';
$perPage = 12;
$page = $_GET['page'] ?? 1;

$start = ($page - 1) * $perPage;

$query = "SELECT enemyId, name, description, imageFileName
        FROM Enemies
        WHERE Name LIKE CONCAT('%', ?, '%')
        ORDER BY $sortBy $dir
        LIMIT ?, ?";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "sii", $search, $start, $perPage);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$enemyCount = mysqli_num_rows($result);
echo "<p>Found $enemyCount Enemies.</p>";
?>

<div class="box">
    <h1>Hollow Knight Characters</h1>
    <form method="get">
        <input type="hidden" name="sort" value="<?= $sortBy ?>">
        <input type="hidden" name="dir" value="<?= $dir ?>">
        <input type="search" name="search" id="search" aria-label="search" placeholder="Search" value="<?= $search ?>">
    </form>

    <table class="table table-striped">
        <thead>
        <tr>
            <th class="table-heading"><a href="?search=<?= $search ?>&sort=Name&dir=<?= $sortBy === 'Name' && $dir === 'ASC' ? 'DESC' : 'ASC' ?>">Name</a> <?= sortArrow($sortBy, 'Name', $dir) ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        // loop through results
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            ?>
            <tr>
                <!-- always use the PRIMARY KEY to link to a record -->
                <td><a href="enemyinfo.php?id=<?= $row['enemyId'] ?>"><?= $row['name'] ?></a></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>

    <nav aria-label="...">
        <ul class="pagination">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Previous</a>
            </li>
            <?php
            $pageCount = ceil($enemyCount / $perPage);
            $maxLinks = 10;
            $startingPage = ($page <= $maxLinks / 2) ? 1 : ($page - $maxLinks / 2);
            $endingPage = $startingPage + $maxLinks;
            for ($i = $startingPage; $i <= $pageCount && $i < $endingPage; $i++) {
                $pageLink = "?search=$search&sort=$sortBy&dir=$dir&page=$i";
                $active = $page == $i ? 'class="page-item active" "aria-current="page"' : 'class="page-item"';
                ?>
                <li <?= $active ?>><a class="page-link" href="<?= $pageLink ?>"><?= $i ?> </a></li>

            <?php } ?>
            <li class="page-item">
                <a class="page-link" href="?search=<?= $search ?>&sort=<?= $sortBy ?>&dir=<?= $dir ?>&page=<?= $page + 1 ?>">Next</a>
            </li>
        </ul>
    </nav>
    <?php if ($userLoggedIn) {
        echo "<a href='add-enemy.php?characterId={$id}' class='btn btn-primary'>Add Enemy</a>";
    } ?>

</div>

<?php
include "includes/footer.php";
mysqli_close($db);
?>

