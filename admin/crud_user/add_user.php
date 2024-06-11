<?php

include('../../php/config.php');
$errors = array();

// Function to add new user to database and log notification
function addUserToDatabase($conn, $userData) {
    global $errors;
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

    

    // If there are no errors, proceed to insert the user into the database
    if (empty($errors)) {
        $result = mysqli_query($conn, "INSERT INTO users (Username, Email, Password, timestamp) VALUES ('$username', '$email', '$password', NOW())");
        return $result;
    } else {
        return false; // Return false if there were errors
    }
}



// Process user registration form
if (isset($_POST['add_user'])) {
    
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

    

    // Attempt to add the user to the database
    if (empty($errors) && addUserToDatabase($conn, $userData)) {
        // Registration successful, redirect to login page
        header('location: /admin/view_users.php');
        exit; // Ensure that no further code execution occurs after redirection
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style/admin_style.css">
    <!-- Boxicons CSS -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Add New User</title>
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
                        <a href="/admin/a_home.php">
                            <i class='bx bx-home-alt icon'></i>
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="/admin/view_users.php">
                            <i class='bx bx-user icon'></i>
                            <span class="text nav-text">Users</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="/admin/analytics.php">
                            <i class='bx bx-bar-chart icon'></i>
                            <span class="text nav-text">Analytics</span>
                        </a>
                    </li>
                    
                    <li class="nav-link">
                        <a href="/admin/messages.php">
                            <i class='bx bx-message-detail icon'></i>
                            <span class="text nav-text">Messages</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="/admin/a_help_page.php">
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
    <div class="home">


<!-- Add new user form -->

<form class="form" method="POST">
       <p class="form-title">Add New User</p>
        <div class="input-container">
          <input type="text" name="username" placeholder="Enter username">
          <span>
          </span>
      </div>
      <div class="input-container">
          <input type="email" name="email" placeholder="Enter email">
          <span>
          </span>
      </div>
      <div class="input-container">
          <input type="password" name="password" placeholder="Enter password">
        </div>
        <div class="btn-container">
         <button type="submit" class="add_user" name="add_user">
            
        Add User
      </button>
      <button type="button" class="back" name="edit_user" onclick="window.location.href='/admin/view_users.php'">Back</button>
      </div>
      <?php if (count($errors) > 0): ?>
                    
                    <?php foreach ($errors as $error): ?>
                        <p class="message"><?php echo $error; ?></p>
                    <?php endforeach; ?>
                    
                    <?php endif; ?>
      
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

