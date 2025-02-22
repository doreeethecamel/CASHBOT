<?php

include('../../php/config.php');
$errors = array();
   

// Redirect to index.php if user is not logged in
if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
    exit(); // Stop further execution
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user'])) {
    // Sanitize and set variables from POST data
    $user_id = $_POST['user_id'];
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
        if ($update_query->execute()) {
            header("Location: /admin/view_users.php");
            exit();
        } else {
            $errors[] = "Error updating record: " . $update_query->error;
        }
        $update_query->close();
    }

    

    
}

// Fetch user data for the form
$id = $_GET['user_id'];
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style/admin_style.css">
    <!-- Boxicons CSS -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Edit User</title>
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
                            <i class='bx bx-message-detail icon'></i>
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

   
<!-- Edit user form -->

<form class="form" method="POST" action="edit_user.php?user_id=<?php echo $res_id; ?>">
       <p class="form-title">Edit User</p>
       <input type="hidden" name="user_id" value="<?php echo $res_id; ?>">
        <div class="input-container">
        <label for="">Username</label>
          <input type="text" name="username" placeholder="Enter username" value="<?php echo $res_Uname; ?>">
          <span>
          </span>
      </div>
      <div class="input-container">
      <label for="">Email</label>
          <input type="email" name="email" placeholder="Enter email" value="<?php echo $res_Email; ?>">
          <span>
          </span>
      </div>
      <div class="input-container">
      <label for="">Password</label>
          <input type="password" name="password" placeholder="Enter password" value="<?php echo $res_Pass; ?>">
        </div>
        <div class="btn-container">
         <button type="submit" class="add_user" name="edit_user">
            
        Update User
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

