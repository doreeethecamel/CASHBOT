<?php 
  

   include("php/config.php");
   $status = "";
   $errors = array();


// Redirect to index.php if user is not logged in
if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
    exit(); // Stop further execution
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    // Sanitize and set variables from POST data
    $user_id = $_SESSION['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

   // Validation
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
} elseif (!preg_match("/@gmail\.com$/", $email)) {
    $errors[] = "Email must end with @gmail.com.";
}

if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
}

if (count($errors) == 0) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $update_query = $conn->prepare("UPDATE users SET Username=?, Email=?, Password=? WHERE Id=?");
    if ($update_query === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    $update_query->bind_param("sssi", $username, $email, $hashed_password, $user_id);

    // Execute the update query
    if ($update_query->execute()) {
        // Redirect to settings.php after successful update
        $status = "Profile Details Updated Successfully.";
        
    } else {
        // Display error if update fails
        $status = "Error Occurred! Profile Details Not Updated.";
        
    }

    // Close the statement
    $update_query->close();
}
}

// Fetch user data for the form
$id = $_SESSION['id'];
$query = $conn->query("SELECT * FROM users WHERE Id=$id");
while($result = mysqli_fetch_assoc($query)){
    $res_Uname = $result['Username'];
    $res_Email = $result['Email'];
    $res_Pass = $result['Password'];
    $res_id = $result['Id'];
}



?>
   

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp"
    rel="stylesheet">
    <!-- Boxicons CSS -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Profile</title>
</head>
<body>

    <nav class="sidebar">
        <header>
            <div class="image-text">
            <span class="image">
                    <img src="/images/logo.png" alt="logo">
                </span>
                <div class="text header-text">
                <span class="name">CASHBOT</span>
                </div>
            </div>
            <i class='bx bx-chevron-right toggle'></i>
        </header>
        <div class="menu-bar">
            <div class="menu">
                
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="home.php">
                            <i class='bx bx-conversation icon'></i>
                            <span class="text nav-text">New Chat</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="budget_assist.php">
                            <i class='bx bx-money icon'></i>
                            <span class="text nav-text">Budget Assistance</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="goal.php">
                            <i class='bx bx-task icon'></i>
                            <span class="text nav-text">Goal Setting</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="help_page.php">
                            <i class='bx bx-help-circle icon'></i>
                            <span class="text nav-text">Help</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="settings.php">
                            <i class='bx bx-cog icon'></i>
                            <span class="text nav-text">Settings</span>
                        </a>
                    </li>
                    
                </ul>
            </div>

            <div class="bottom-content">
                <li class="">
                    <a href="/php/logout.php">
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>

                <li class="mode">
                    <div class="moon-sun">
                        <i class='bx bx-moon icon moon'></i>
                        <i class='bx bx-sun icon sun'></i>
                        
                    </div>
                    <span class="text mode-text">Dark Mode</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>
            </div>
        </div>
    </nav>
    <nav class="homebar">
        <div class="navbar-left">
        <h2 class="logo"><a href="home.php">CashBot</a></h2></div>
        
        
        <div class="right-links">

        <?php 
            
            $id = $_SESSION['id'];
            $query = mysqli_query($conn,"SELECT*FROM users WHERE Id=$id");

            while($result = mysqli_fetch_assoc($query)){
                $res_Uname = $result['Username'];
                $res_Email = $result['Email'];
                
                $res_id = $result['Id'];
            }
            
            
            echo "Hello, $res_Uname";
            ?>


</div>
        </nav>
   
  
    <div class="settings-container">
        <div class="settings-profile">
            <div class="settings-profile-header">
                <div class="profile-text-container">
                    <h1 class="profile-title"><?php echo $res_Uname; ?></h1>
                    <p class="profile-email"><?php echo $res_Email; ?></p>
                </div>
            </div>

            <div class="settings-menu">
                <a href="" class="settings-menu-link"><i class='bx bx-user-circle menu-icon'></i>Account</a>
                
                
                
            </div>
        </div>

        <form action="" class="account" method="post">
        <?php if (!empty($status)): ?>
                <p class="message"><?php echo $status; ?></p>
            <?php endif; ?>
        <?php if (count($errors) > 0): ?>
                <?php foreach ($errors as $error): ?>
                    <p class="message"><?php echo $error; ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="account-header">
                <h1 class="account-title">Account Setting</h1>
                <div class="btn-container">
                    <button class="btn-cancel" type="button" onclick="window.location.href='settings.php'">Cancel</button>
                    <button class="btn-save" name="update_profile">Save</button>
                </div>
            </div>
            <input type="hidden" name="user_id" value="<?php echo $res_id; ?>">
            <div class="account-edit">
            
                <div class="input-container">
                    <label for="">Username</label>
                    <input type="text" name="username"  placeholder="Username" value="<?php echo $res_Uname; ?>"/>
                </div>
            </div>
            <div class="account-edit">
                <div class="input-container">
                    <label for="">Email</label>
                    <input type="text" name="email" placeholder="Email" value="<?php echo $res_Email; ?>"/>
                </div>
            </div>
            <div class="account-edit">
                <div class="input-container">
                    <label for="">Password</label>
                    <input type="text" name="password" placeholder="Password" value="<?php echo $res_Pass; ?>"/>
                </div>
            </div>
        </form>
    </div>
   
<!--footer-->

<footer class="footer">
    <div class="footer-text">
        <p>Copyright &copy; 2024 | Dorelies Siloma</p>
    </div>

    
</footer>
    
  
     <script src="script.js"></script>
     <script src="/admin/js/admin_script.js"></script>
</body>
</html>