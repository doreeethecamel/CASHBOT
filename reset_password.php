<?php
include("php/config.php");
$errors = array();

if (isset($_POST['reset_password'])) {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if (empty($new_password) || empty($confirm_password)) {
        $errors[] = "All fields are required";
    } elseif ($new_password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    if (empty($errors)) {
        $query = "SELECT * FROM password_reset WHERE token='$token' AND expires >= " . date("U");
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $email = $row['email'];
            $new_password_encrypted = md5($new_password);

            $query = "UPDATE users SET Password='$new_password_encrypted' WHERE Email='$email'";
            mysqli_query($conn, $query);

            $query = "DELETE FROM password_reset WHERE token='$token'";
            mysqli_query($conn, $query);

            echo "Your password has been reset successfully.";
        } else {
            $errors[] = "Invalid or expired token";
        }
    }
} elseif (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    $query = "SELECT * FROM password_reset WHERE token='$token' AND expires >= " . date("U");
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        die("Invalid or expired token");
    }
} else {
    die("No token provided");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
<nav class=" login-homebar">
        <h2 class="logo">CashBot</h2>
        <nav class="navigation">
            <a href="home.php">Home</a>
            
            <button class="btn-login"><a href="index.php"></a>Login</button>
        </nav>
</nav>
<div class="container">
        <!-- <div class="box form-box"> -->
        <div class="form-container sign-in">
    
    <form action="reset_password.php" method="post" class="login-form">
    <h2>Reset Password</h2>
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
        <div class="flex-column"><label for="new_password">New Password</label></div>
        <div class="inputForm"><input type="password" name="new_password" class="input" placeholder="Enter your new Password" required></div>
        <div class="flex-column"><label for="confirm_password">Confirm Password</label></div>
        <div class="inputForm"><input type="password" name="confirm_password" class="input" placeholder="Confirm your new Password" required></div>
        <br>

        <button type="submit" class="btn" name="reset_password">Reset Password</button><button type="button" class="btn" name="back" onclick="window.location.href='index.php'">back</button>
    </form>

    <?php if (count($errors) > 0): ?>
        <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    <?php endif; ?>
        </div>
        </div>
</body>
</html>
