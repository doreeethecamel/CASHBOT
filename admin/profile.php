<?php
     
       include ("../php/config.php");
       $status = "";


        // Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    // Sanitize and set variables from POST data
    $user_id = $_SESSION['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    /// Validation
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
        $status = "Profile Details Not Updated Successfully.";
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
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="/style/admin_style.css">
    <!-- Boxicons CSS -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                        <a href="a_home.php">
                            <i class='bx bx-home-alt icon'></i>
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="view_users.php">
                            <i class='bx bx-user icon'></i>
                            <span class="text nav-text">Users</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="analytics.php">
                            <i class='bx bx-bar-chart icon'></i>
                            <span class="text nav-text">Analytics</span>
                        </a>
                    </li>
                    
                    <li class="nav-link">
                        <a href="messages.php">
                            <i class='bx bx-message-detail icon'></i>
                            <span class="text nav-text">Messages</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="a_help_page.php">
                            <i class='bx bx-help-circle icon'></i>
                            <span class="text nav-text">Help</span>
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

    

    <nav class="admin-navbar home">
        <div class="navbar-left">
            <span class="dashboard-title">Dashboard</span>
        </div>
        <div class="navbar-right">
            <!-- Messages dropdown -->
            <div class="dropdown">
                <button class="icon-button"><i class='bx bx-envelope'></i></button>
                <div class="dropdown-content">
                    <!-- Messages list here -->
                    <?php
        

        // Fetch most recent notifications
        $messages_query = mysqli_query($conn, "SELECT user_messages.id, users.Username, user_messages.timestamp 
        FROM user_messages 
        INNER JOIN users ON user_messages.user_id = users.Id ORDER BY timestamp DESC LIMIT 5");

        // Check if there are notifications
        if (mysqli_num_rows($messages_query) > 0) {
            // Loop through notifications and display them as links
            while ($messages = mysqli_fetch_assoc($messages_query)) {
                echo "<a href='#'>New message sent: " . $messages['Username'] . "</a>";
            }
        } else {
            echo "<span>No new messages</span>";
        }
        ?>
                </div>
            </div>
            <!-- Notifications dropdown -->
            <div class="dropdown">
                <button class="icon-button"><i class='bx bx-bell'></i></i></button>
                <div class="dropdown-content">
                    <!-- Notifications list here -->
                    <?php
        

        // Fetch most recent notifications
        $notifications_query = mysqli_query($conn, "SELECT * FROM users ORDER BY timestamp DESC LIMIT 5");

        // Check if there are notifications
        if (mysqli_num_rows($notifications_query) > 0) {
            // Loop through notifications and display them as links
            while ($notification = mysqli_fetch_assoc($notifications_query)) {
                echo "<a href='#'>New user registered: " . $notification['Username'] . "</a>";
            }
        } else {
            echo "<span>No new notifications</span>";
        }
        ?>
                </div>
            </div>
            <!-- Profile dropdown -->
            <div class="dropdown">
                <button class="profile-button">
                    <img src="/images/profile.jpg" alt="Profile Picture">
                </button>
                <div class="dropdown-content">
                    <a href="/admin/profile.php">Account Details</a>
                    
                    <a href="/php/logout.php">Logout</a>
                </div>
            </div>
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
                    <button class="btn-cancel" onclick="window.location.href='profile.php'">Cancel</button>
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
    <footer class="footer">
    <div class="footer-text">
        <p>Copyright &copy; 2024 | Dorelies Siloma</p>
    </div>

    
</footer>
    <script src="/admin/js/admin_script.js"></script>
</body>
</html>