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
// Check if the user is logged in
$userLoggedIn = isset($_SESSION['users']['userId']);
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
?>
<div class="box">
<div class="container">
    <a href="character.php?id=<?= $characterId ?>">&larr; Back</a>
    <h1>Add Character</h1>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="characterId" value="<?= $characterId ?>">

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>

        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <button type="submit" class="btn btn-primary" name="submit">Add Character</button>
    </form>
</div>
<?php
include "includes/footer.php";
// Handle form submission
if (isset($_POST['submit'])) {
    // Verify CSRF token
    if ($_SESSION['csrf_token'] != $_POST['csrf_token']) {
        die('Invalid token. Please try again');
    }

    // Retrieve form data
    $characterId = $_POST['characterId'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    // File upload handling
    $imageFileName = ''; // Initialize image file name variable
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // File upload was successful
        $tempName = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $imageFileName = 'uploads/' . $fileName; // Path to store image, adjust as needed
        move_uploaded_file($tempName, $imageFileName);
    }

    // Insert character into database
    $query = "INSERT INTO `Characters` (`name`, `description`, `imageFileName`) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($db, $query) or die("Error adding character.");
    mysqli_stmt_bind_param($stmt, 'sss', $name, $description, $imageFileName);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirect user
    header('Location: character.php?id=' . $characterId);
    exit();
}

// Close database connection
mysqli_close($db);
?>

