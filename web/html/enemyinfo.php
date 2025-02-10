<?php
// Start the session
session_name('aerickson26_final_logins');
session_start();

// Include necessary files
include "includes/header.php";
require_once "includes/database.php";
require_once "includes/functions.php";

// Check if the user is logged in
$userLoggedIn = isset($_SESSION['users']['userId']);

// Get character ID from the URL
$id = $_GET['id'] ?? '1';

// Query to retrieve character information
$query = "SELECT Name, description, imageFileName FROM Enemies WHERE enemyId = ?";
$stmt = mysqli_prepare($db, $query) or die('Error in query.');
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if a character was found with the given ID
if ($row = mysqli_fetch_assoc($result)) {
    // Display character information
    echo "<div class='box'>";
    echo "<div class='container'>";
    echo "<h1>{$row['Name']}</h1>";
    echo "<p>{$row['description']}</p>";
    echo "<img src='uploads/characters/{$row['imageFileName']}' alt='Image of {$row['Name']}' />";

    // Debugging statement
    // echo "<p>Image file path: {$row['imageFileName']}</p>";

    // Display edit and delete buttons if the user is logged in
    if ($userLoggedIn) {
        echo "<a href='edit-enemy.php?id={$id}' class='btn btn-primary'>Edit</a>";
        echo "<a href='delete-enemy.php?id={$id}' class='btn btn-danger'>Delete</a>";
        //echo "<a href='character-discussion.php?id= {$id}' class='btn btn-primary'>Discuss</a>";
    }

    echo "</div>"; // Close the container
} else {
    // Character not found
    echo "<div class='container'>";
    echo "<p>Character not found.</p>";
    echo "</div>"; // Close the container
    echo "</div>"; // Close the container
}

// Close database connection
mysqli_stmt_close($stmt);
mysqli_close($db);

include "includes/footer.php";
?>
