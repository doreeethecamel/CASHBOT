<?php

include('../../php/config.php');


// Redirect to index.php if user is not logged in
if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
    exit(); // Stop further execution
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    // Sanitize and set variables from POST data
    $user_id = $_POST['user_id'];
    

    // Prepare and bind the delete statement
    $delete_query = $conn->prepare("DELETE FROM users WHERE Id=?");

    // Check if prepare() succeeded
    if ($delete_query === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    // Bind parameters
    $delete_query->bind_param("i", $user_id);

    // Execute the delete query
    if ($delete_query->execute()) {
        // Redirect to view_users.php after successful update
        header("Location: /admin/view_users.php");
        exit(); // Stop further execution
    } else {
        
        echo "Error: " . $delete_query->error;
    }

    // Close the statement
    $delete_query->close();
}

// Fetch user data for the form
if (isset($_GET['user_id'])) {
$user_id = $_GET['user_id'];
$query = $conn->query("SELECT * FROM users WHERE Id=$user_id");
$result = mysqli_fetch_assoc($query);
    $username = $result['Username'];
    $email = $result['Email'];
    $password = $result['Password'];
} else {
    // Handle case where user ID is not provided
    // Redirect or display an error message
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
    <title>Delete User</title>
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
<!-- Delete user form or button -->


<form class="form" method="POST">
       <p class="form-title">Delete User</p>
       <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <div class="input-container">
        <label for="">Username</label>
          <input type="text" name="username" placeholder="Enter username" value="<?php echo $username; ?>" readonly>
          <span>
          </span>
         </div>

        <div class="input-container">
        <label for="">Email</label>
          <input type="email" name="email" placeholder="Enter email" value="<?php echo $email; ?>" readonly>
          <span>
          </span>
        </div>
        <div class="input-container">
        <label for="">Password</label>
          <input type="password" name="password" placeholder="Enter password" value="<?php echo $password; ?>" readonly>
        </div>
        <div class="btn-container">
         <button type="submit" class="add_user" name="delete_user">
            
        Delete User
      </button>
      <button type="submit" class="back" name="delete_user" href="/admin/view_users.php">Back</button>
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