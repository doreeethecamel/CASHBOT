<?php 
  

   include("../php/config.php");

   function formatTimestamp($timestamp) {
    // Current timestamp
    $now = time();

    // Convert the given timestamp to seconds
    $timestamp = strtotime($timestamp);

    // Calculate the difference in seconds
    $difference = $now - $timestamp;

    // Calculate the time ago
    if ($difference < 60) {
        return 'Just now';
    } elseif ($difference < 3600) {
        $minutes = floor($difference / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    } elseif ($difference < 86400) {
        $hours = floor($difference / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } else {
        $days = floor($difference / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    }
}

// Function to get the count of records for a specific table
function getRecordCount($table, $conn) {
    $query = "SELECT COUNT(*) AS count FROM $table";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    } else {
        return 0;
    }
}

// Fetch record counts
$totalUsers = getRecordCount('users', $conn);
$totalMessages = getRecordCount('user_messages', $conn);
$totalNotifications = getRecordCount('admin_notifications', $conn);
?>
   

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashbot Admin</title>
    <!--  CSS -->
   <link rel="stylesheet" href="/style/admin_style.css">
  
   <!-- Boxicons CSS -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                echo "<a href='#'>New message received: " . $messages['Username'] . "</a>";
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
                <div class="dropdown-content"><!-- Fetch and display notifications -->
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
                    <!--  Notifications list here 
                    <a href="#">Notification 1</a>
                    <a href="#">Notification 2</a>
                    <a href="#">Notification 3</a> -->
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

    <div class="insights-container">
    

        <div class="insights">
            <div class="sales">
            <i class='bx bxs-user icon'></i>
            <div class="middle">
                <div class="left">
                    <h3>Total Users</h3>
                    <h1><?php echo $totalUsers; ?></h1>
                </div>
                
            </div>
            
            </div>
            
        
            <div class="expenses">
            <i class='bx bxs-conversation icon'></i>
            <div class="middle">
                <div class="left">
                    <h3>Total Messages</h3>
                    <h1><?php echo $totalMessages; ?></h1>
                </div>
                
            </div>
            
            </div>
            

            <div class="income">
            <i class='bx bxs-bell icon'></i>
            <div class="middle">
                <div class="left">
                    <h3>Total Notifications</h3>
                    <h1><?php echo $totalNotifications; ?></h1>
                </div>
                
            </div>
            
            </div>
            
        </div>
         

         
    </div>
    
    <?php
    // Fetch user notifications from the database
$query = "SELECT * FROM users ORDER BY timestamp DESC LIMIT 3"; // Fetching the latest 3 notifications
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo
    '<!-- New Users Section -->
    <div class="new-users">
                <h2 class="new-users-title">New Users</h2>
                <div class="user-list-container">
                ';
                // Loop through the fetched records and display them
    while ($row = mysqli_fetch_assoc($result)) {
        $username = $row['Username'];
        $timestamp = $row['timestamp'];
        // Format the timestamp using the formatTimestamp function
        $time_ago = formatTimestamp($timestamp);
                    echo '<div class="user-list"><div class="user">
                       
                        <h2>' .$username .'</h2>
                        <p>' .$time_ago .'</p>
                    </div></div>';
    }
                
                   echo '<a class="user-list more" href="view_users.php"><div class="user">
                        <h2>More<i class="bx bx-plus icon"></i></h2>
                        <p>View more</p>
                    </div>               </a>
            </div></div>
            <!-- End of New Users Section -->';
    }else{
        echo '<div class="user">
                       
        <h2>Now new users.</h2>';
    }?>

            

                
                <div class="wrapper">
      <div class="task-input">
        
        <input type="text" placeholder="Add a new task">
      </div>
      <div class="controls">
        <div class="filters">
          <span class="active" id="all">All</span>
          <span id="pending">Pending</span>
          <span id="completed">Completed</span>
        </div>
        <button class="clear-btn">Clear All</button>
      </div>
      <ul class="task-box"></ul>
    </div>
    <footer class="footer">
    <div class="footer-text">
        <p>Copyright &copy; 2024 | Dorelies Siloma</p>
    </div>

    
</footer>
                <!-- End of Reminders-->


    <script src="/admin/js/admin_script.js"></script>
</body>
</html> 