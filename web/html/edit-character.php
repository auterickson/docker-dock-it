<?php
session_name('aerickson26_final_logins');
session_start();
require_once "includes/database.php";
require_once "includes/functions.php";
include "includes/header.php";
$_SESSION['csrf_token'] = $_SESSION['csrf_token'] ?? md5(uniqid());

$id = $_GET['id'] ?? '1';

$query = 'SELECT characterId, name, description, imageFileName FROM Characters WHERE characterId = ?';
$stmt = mysqli_prepare($db, $query) or die("Error fetching character details.");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $characterId, $name, $description, $imageFileName);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
?>


<div class="box">

    <div class="container">
        <a href="character.php?id=<?= $characterId ?>">&larr; Back</a>
        <h1>Edit Character: <?= $name ?></h1>

        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="characterId" value="<?= $characterId ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $name ?>">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description"><?= htmlspecialchars($description) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>

            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <button type="submit" class="btn btn-primary" name="submit">Save Changes</button>
        </form>
    </div>

</div>

<?php
if(isset($_POST['submit'])) {
    // Verify CSRF token
    if ($_SESSION['csrf_token'] != $_POST['csrf_token']) {
        die('Invalid token. Please try again');
    }

    // Retrieve form data
    $characterId = $_POST['characterId'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $query = "UPDATE `Characters` 
        SET 
            `name` = ?, 
            `description` = ?
        WHERE `characterId` = ?";
    $stmt = mysqli_prepare($db, $query) or die("Error updating character.");
    mysqli_stmt_bind_param($stmt, 'ssi', $name, $description, $characterId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $image = $_FILES['image'] ?? false;
    if ($image && $image['name']) {
        // Get file extension
        $imageExt = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        // Check if the file extension is one of the accepted types
        if (in_array($imageExt, ['jpg', 'jpeg', 'gif', 'png'])) {
            // Generate unique filename
            $imageFilename = time() . '-' . uniqid() . '.' . $imageExt;
            // Upload image
            $uploadsDir = 'uploads/characters/';

            if (move_uploaded_file($image['tmp_name'], $uploadsDir . $imageFilename)) {
                // Update image filename in the database
                $query = "UPDATE `Characters` 
                          SET 
                              `imageFileName` = ?
                          WHERE `characterId` = ?";
                $stmt = mysqli_prepare($db, $query) or die("Error updating character.");
                mysqli_stmt_bind_param($stmt, 'si', $imageFilename, $characterId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                // Redirect user
                header('Location: characterinfo.php?id=' . $characterId);
                exit();
            } else {
                die('Failed to move file');
            }
        } else {
            die('Invalid file type');
        }
    }
}
?>

<?php include "includes/footer.php"; ?>



