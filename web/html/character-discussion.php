<?php
session_name('aerickson26_final_logins');
session_start();

require_once "includes/header.php";
require_once "includes/database.php"; // Assuming this file contains database connection logic

// Check if the user is logged in
$userLoggedIn = isset($_SESSION['users']['userId']);

// Handle form submission to add a new feedback or reply
if (isset($_POST['submit']) || isset($_POST['reply_submit'])) {
    $comment = strip_tags($_POST['comment']);
    $userId = $_SESSION['users']['userId'];
    $parentCommentId = isset($_POST['parentCommentId']) ? $_POST['parentCommentId'] : null;

    // Insert the new feedback or reply into the database
    $query = "INSERT INTO characterfeedback (userId, comment, parentCommentId) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "isi", $userId, $comment, $parentCommentId);

    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Redirect to the same page after successful submission
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit(); // Ensure no more code is executed after the redirect
    } else {
        die('Error: ' . mysqli_error($db));
    }

    mysqli_stmt_close($stmt);
}

// Handle comment deletion
if (isset($_POST['delete'])) {
    $commentId = $_POST['commentId'];
    $userId = $_SESSION['users']['userId'];

    // Query to delete the comment only if the user is the author of the comment
    $query = "DELETE FROM characterfeedback WHERE feedbackId = ? AND userId = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "ii", $commentId, $userId);

    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Redirect to the same page after successful deletion
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit(); // Ensure no more code is executed after the redirect
    } else {
        die('Error: ' . mysqli_error($db));
    }

    mysqli_stmt_close($stmt);
}

// Fetch existing feedback from the database including user information
$query = "SELECT feedbackId, comment, createdAt, users.email 
          FROM characterfeedback 
          INNER JOIN users ON characterfeedback.userId = users.userId 
          ORDER BY createdAt DESC";
$result = mysqli_query($db, $query);


?>
<div class="box">
    <div class="container">
        <h1>Character Feedback</h1>

        <!-- Form to add a new feedback -->
        <form method="post">
            <div class="mb-3">
                <label for="comment" class="form-label">Feedback</label>
                <textarea class="form-control" id="comment" name="comment" rows="5" required></textarea>
            </div>
            <input type="hidden" name="parentCommentId" value="">
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>

        <hr>
        <div class="discussion">
            <!-- Display existing feedback -->
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="card mb-3">
                    <div class="card-header">Author: <?= htmlspecialchars($row['email']) ?></div>
                    <div class="card-body">
                        <p class="card-text"><?= htmlspecialchars($row['comment']) ?></p>
                        <p class="card-text"><small class="text-muted">Posted on <?= htmlspecialchars($row['createdAt']) ?></small></p>

                        <!-- Reply form for each comment -->
                        <form method="post">
                            <div class="mb-3">
                                <label for="commentReply" class="form-label">Reply</label>
                                <textarea class="form-control" id="commentReply" name="comment" rows="2" required></textarea>
                            </div>
                            <input type="hidden" name="parentCommentId" value="<?= $row['feedbackId'] ?>">
                            <button type="submit" class="btn btn-primary" name="reply_submit">Reply</button>
                        </form>

                        <!-- Display replies -->
                        <?php
                        $parentCommentId = $row['feedbackId'];
                        $queryReplies = "SELECT feedbackId, comment, createdAt, users.email 
                                FROM characterfeedback 
                                INNER JOIN users ON characterfeedback.userId = users.userId 
                                WHERE parentCommentId = $parentCommentId";
                        $resultReplies = mysqli_query($db, $queryReplies);
                        while ($replyRow = mysqli_fetch_assoc($resultReplies)): ?>
                            <div class="card mb-2 ms-4">
                                <div class="card-header">Author: <?= htmlspecialchars($replyRow['email']) ?></div>
                                <div class="card-body">
                                    <p class="card-text"><?= htmlspecialchars($replyRow['comment']) ?></p>
                                    <p class="card-text"><small class="text-muted">Posted on <?= htmlspecialchars($replyRow['createdAt']) ?></small></p>
                                </div>
                            </div>
                        <?php endwhile; ?>

                        <?php if ($userLoggedIn): ?>
                            <!-- Delete form for each comment -->
                            <form method="post" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                <input type="hidden" name="commentId" value="<?= $row['feedbackId'] ?>">
                                <button type="submit" class="btn btn-danger" name="delete">Delete</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>


    <?php include "includes/footer.php"; ?>



