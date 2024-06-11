<?php 

include("php/config.php"); 
$errors = array();

// Function to add new user to database and log notification
function addUserToDatabase($conn, $userData) {
    // Your existing code to insert user into the database
    $username = mysqli_real_escape_string($conn, $userData['username']);
    $email = mysqli_real_escape_string($conn, $userData['email']);
    $password = mysqli_real_escape_string($conn, $userData['password']);
   
    // Password encryption
    $password = md5($password);
   
    
    

    // Check if username or email already exists
    $verify_query = mysqli_query($conn, "SELECT * FROM users WHERE Email='$email' OR  Username='$username'");
    $user = mysqli_fetch_assoc($verify_query);
    if ($user) {
        if ($user['Username'] === $username) {
            $errors[] = "Username already exists";
        }
        if ($user['Email'] === $email) {
            $errors[] = "Email already exists";
        }
    }

   

    // Insert user into database
    $result = mysqli_query($conn, "INSERT INTO users (Username, Email, Password, timestamp) VALUES ('$username', '$email', '$password', NOW())");
    return $result;
}



// Process user registration form
if (isset($_POST['reg_user'])) {
    
    $userData = array(
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'password' => $_POST['password']
        
    );

     // Email domain validation
     if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } elseif (!preg_match("/@gmail\.com$/", $userData['email'])) {
        $errors[] = "Email must end with @gmail.com.";
    }

    // Password length validation
    if (strlen($userData['password']) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    

    if (empty($errors) && addUserToDatabase($conn, $userData)) {
        // Registration successful, redirect to login page
        header('location: index.php');
        exit; // Ensure that no further code execution occurs after redirection
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Register</title>
</head>
<body>
<nav class="login-homebar">
        <h2 class="logo">CashBot</h2>
        <nav class="navigation">
            <a href="home.php">Home</a>
            
            <button type="button" onclick="window.location.href='index.php'">
            Login</button>
        </nav>
</nav>
      <div class="container">
        <div class="form-container sign-in">

       

            
            <form action="register.php" method="post" class="login-form">
            
            <h1>Create Account</h1>
            
               
                <div class="flex-column">
                <label for="username">Username</label></div>
                    <div class="inputForm">
                    <svg height="20" viewBox="0 0 32 32" width="20" xmlns="http://www.w3.org/2000/svg"><g id="Layer_3" data-name="Layer 3"><path d="m30.853 13.87a15 15 0 0 0 -29.729 4.082 15.1 15.1 0 0 0 12.876 12.918 15.6 15.6 0 0 0 2.016.13 14.85 14.85 0 0 0 7.715-2.145 1 1 0 1 0 -1.031-1.711 13.007 13.007 0 1 1 5.458-6.529 2.149 2.149 0 0 1 -4.158-.759v-10.856a1 1 0 0 0 -2 0v1.726a8 8 0 1 0 .2 10.325 4.135 4.135 0 0 0 7.83.274 15.2 15.2 0 0 0 .823-7.455zm-14.853 8.13a6 6 0 1 1 6-6 6.006 6.006 0 0 1 -6 6z"></path></g></svg>
        
                    <input type="text" name="username" id="username" class="input" placeholder="Enter your Username" autocomplete="off" required>
                </div>

               
                <div class="flex-column">
                    <label for="email">Email</label></div>
                    <div class="inputForm">
                    <svg height="20" viewBox="0 0 32 32" width="20" xmlns="http://www.w3.org/2000/svg"><g id="Layer_3" data-name="Layer 3"><path d="m30.853 13.87a15 15 0 0 0 -29.729 4.082 15.1 15.1 0 0 0 12.876 12.918 15.6 15.6 0 0 0 2.016.13 14.85 14.85 0 0 0 7.715-2.145 1 1 0 1 0 -1.031-1.711 13.007 13.007 0 1 1 5.458-6.529 2.149 2.149 0 0 1 -4.158-.759v-10.856a1 1 0 0 0 -2 0v1.726a8 8 0 1 0 .2 10.325 4.135 4.135 0 0 0 7.83.274 15.2 15.2 0 0 0 .823-7.455zm-14.853 8.13a6 6 0 1 1 6-6 6.006 6.006 0 0 1 -6 6z"></path></g></svg>
        
                    <input type="text" name="email" id="email" class="input" placeholder="Enter your Email" autocomplete="off" required>
                </div>

              
               
                <div class="flex-column">
                    <label for="password">Password</label></div>
                    <div class="inputForm">
                    <svg height="20" viewBox="-64 0 512 512" width="20" xmlns="http://www.w3.org/2000/svg"><path d="m336 512h-288c-26.453125 0-48-21.523438-48-48v-224c0-26.476562 21.546875-48 48-48h288c26.453125 0 48 21.523438 48 48v224c0 26.476562-21.546875 48-48 48zm-288-288c-8.8125 0-16 7.167969-16 16v224c0 8.832031 7.1875 16 16 16h288c8.8125 0 16-7.167969 16-16v-224c0-8.832031-7.1875-16-16-16zm0 0"></path><path d="m304 224c-8.832031 0-16-7.167969-16-16v-80c0-52.929688-43.070312-96-96-96s-96 43.070312-96 96v80c0 8.832031-7.167969 16-16 16s-16-7.167969-16-16v-80c0-70.59375 57.40625-128 128-128s128 57.40625 128 128v80c0 8.832031-7.167969 16-16 16zm0 0"></path></svg>        
                    <input type="password" class="input" placeholder="Enter your Password" name="password" id="password" autocomplete="off" required>
                    <svg id="togglePassword" viewBox="0 0 576 512" height="1em" xmlns="http://www.w3.org/2000/svg"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"></path></svg>
                    </div>
                    <?php if (count($errors) > 0): ?>
                    
                    <?php foreach ($errors as $error): ?>
                        <p class="message"><?php echo $error; ?></p>
                    <?php endforeach; ?>
                    
                    <?php endif; ?>
                    
                    <input type="submit" class="btn" name="reg_user" value="Register" required>
                
                
                    <p class="p">Already a member? <a href="index.php">Sign Up Now</a>

                        </p>
                
                
                   
            </form>
        </div>
       

       
      </div>
      <script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
});
</script>
</body>
</html>