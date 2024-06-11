<?php
include("php/config.php");
$errors = array();
$reset_link = "";

if (isset($_POST['reset_password'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($errors)) {
        $query = "SELECT * FROM users WHERE Email='$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $token = bin2hex(random_bytes(50));
            $expires = date("U") + 1800;

            $query = "INSERT INTO password_reset (email, token, expires) VALUES ('$email', '$token', '$expires')";
            mysqli_query($conn, $query);

            // Instead of sending an email, display the reset link
            $reset_link = "http://localhost:3000/reset_password.php?token=$token";
            
        } else {
            $errors[] = "No user found with this email address";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
    
    <form action="forgot_password.php" method="post" class="login-form">
    <h2>Forgot Password</h2>
    <div class="flex-column"><label for="email">Email</label></div>
    <div class="inputForm">
        <input type="email" name="email" class="input" placeholder="Enter your Email"></div>
        <br>
        <button type="button" class="btn" name="back" onclick="window.location.href='index.php'">Back</button>
        <button type="submit" class="btn" name="reset_password">Submit</button>
        <?php if (!empty($reset_link)): ?>
            <p class="message">A password reset link has been generated: <a href="<?php echo $reset_link; ?>"><?php echo $reset_link; ?></a></p>
        <?php endif; ?>
    </form>

    

    <?php if (count($errors) > 0): ?>
        <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    <?php endif; ?>
        </div></div>
</body>
</html>
