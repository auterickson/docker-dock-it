<?php
session_name('aerickson26_final_logins');
session_start();

include "includes/header.php";
/**
 * @var $db mysqli Database Connection
 */
?>
<div class="box">
<div class="container login-container">
    <div class="row">
        <div class="col-md-6 login-form-1">
            <h3>Sign Up</h3>
            <?php
            $accountCreated = false;

            if(isset($_POST['signup'])){
                // get form values
                $email = $_POST['email'];
                $password = $_POST['password'];

                // For example, you can use PHP's filter_var function for email validation
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo '<div class="alert alert-danger">
                          <b>Error creating account!</b><br> Invalid email.
                        </div>';
                    exit(); // Stop further execution
                }

                // Validate password (e.g., minimum length)
                if (strlen($password) < 6) {
                    echo '<div class="alert alert-danger">
                          <b>Error creating account!</b><br> Password must be at least 6 characters long.
                        </div>';
                    exit(); // Stop further execution
                }
                $password = password_hash($password, PASSWORD_DEFAULT);
                // add user to database
                $query = "INSERT INTO users
                            (email, password)
                            VALUES
                            (?, ?)";
                var_dump($query);

                // prepare, bind, and execute query
                $stmt = mysqli_prepare($db, $query);
                mysqli_stmt_bind_param($stmt, "ss", $email, $password);
                mysqli_stmt_execute($stmt);

                // debugging only
                //echo mysqli_stmt_error($stmt);

                // check if record was created
                if($newUserId = mysqli_stmt_insert_id($stmt)){
                    //use $newUserId to create any additional new user records
                    $accountCreated = true;
                    echo '<div class="alert alert-success">
                          <b>Account created!</b><br>Please login.
                        </div>';

                }else{
                    echo '<div class="alert alert-danger">
                          <b>Error creating account!</b><br> (Tell the user what to do...email already used?)
                        </div>';
                }
            }
            ?>

            <?php if(!$accountCreated): ?>
                <form method="post">
                    <div class="form-group">
                        <input type="text" name="email" class="form-control" placeholder="Your Email *" value="">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Your Password *" value="">
                    </div>
                    <div class="form-group">
                        <input type="submit" name="signup" class="btnSubmit" value="Sign Up">
                    </div>
                </form>
            <?php endif; ?>
        </div>
        <div class="col-md-6 login-form-2">
            <h3>Login</h3>
            <?php
            if(isset($_POST['login'])){
                // get form values
                $email = $_POST['email'];
                $password = $_POST['password'];

                // get user record from database and check login
                $query = "SELECT userId, email, password FROM users WHERE email = ?";
                $stmt = mysqli_prepare($db, $query);
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);

                // bind these variables to the columns in the record (same order)
                mysqli_stmt_bind_result($stmt, $userId, $email, $hashedPassword);

                // fetch the values into the variables
                // (this is what you would loop over if you had more than one record)
                mysqli_stmt_fetch($stmt);

                //  check the password
                if(password_verify($password, $hashedPassword)){
                    //  check if the password needs to be rehashed (updated)
                    // best practice for when PHP comes out with new hashing algorithm
                    if(password_needs_rehash($hashedPassword, PASSWORD_DEFAULT)){
                        // create a new password hash
                        $newHash = password_hash($password, PASSWORD_DEFAULT);

                        $query = "UPDATE users SET password = ? WHERE userId = ?";
                        $stmt = mysqli_prepare($db, $query);
                        mysqli_stmt_bind_param($stmt, "si", $newHash, $userId);
                        mysqli_stmt_execute($stmt);
                    }

                    // update the session with the current user
                    session_regenerate_id(true);
                    $_SESSION['users']['email'] = $email;
                    $_SESSION['users']['userId'] = $userId;

                    // redirect
                    header('Location: index.php');
                    die();
                }else{

                    echo '<div class="alert alert-danger">
                          <b>Error logging in!</b><br> Incorrect email or password.
                        </div>';
                }
            }

            // logout and redirect to login page
            if(isset($_GET['logout'])){
                // remove session data
                // (only removes username, reuses same cookie -- this is bad)
                unset($_SESSION['users']);

                // destroy the session (and cookie)
                session_destroy();

                // redirect
                header("Location: sign-in.php");
                die();
            }

            ?>
            <?php if(isset($_SESSION['users'])): ?>
                <form method="get">
                    <input type="submit" name="logout" class="btnSubmit" value="Log Out">
                </form>
            <?php else: ?>
                <form method="post">
                    <div class="form-group">
                        <input type="text" name="email" class="form-control" placeholder="Your Email *" value="">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Your Password *" value="">
                    </div>
                    <div class="form-group">
                        <input type="submit" name="login" class="btnSubmit" value="Login">
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>
