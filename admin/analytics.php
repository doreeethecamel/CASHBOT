<?php 
  

include("../php/config.php");
// Initialize arrays for data points
$dataPointsBar = array(); // For bar graph
$dataPointsLine = array(); // For line graph

// SQL query to fetch the number of users registered per month
$query = "SELECT MONTH(timestamp) AS registration_month, COUNT(*) AS user_count FROM users GROUP BY MONTH(timestamp)";

// Execute the query
$result = mysqli_query($conn, $query);

// Process the result set
while ($row = mysqli_fetch_assoc($result)) {
    // Extract the registration month and user count
    $registration_month = $row['registration_month'];
    $user_count = $row['user_count'];
    
    // Push data to dataPoints arrays
    $dataPointsBar[] = array("y" => $user_count, "label" => date('F', mktime(0, 0, 0, $registration_month, 1)));
    $dataPointsLine[] = array("y" => $user_count, "label" => date('F', mktime(0, 0, 0, $registration_month, 1)));
}


   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Analysis</title>
    <!--  CSS -->
   <link rel="stylesheet" href="/style/admin_style.css">
  
   <!-- Boxicons CSS -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>



<script>
window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        
        title:{
            text: "User Registration Analysis"
        },
        axisY: {
            title: "Users"
        },
        
        data: [{
            type: "column",
            name: "Users Per Month",
            showInLegend: true,
            dataPoints: <?php echo json_encode($dataPointsBar, JSON_NUMERIC_CHECK); ?>
        },
        {
            type: "spline",
            axisYType: "secondary",
            name: "Users Per Month (Line)",
            showInLegend: true,
            dataPoints: <?php echo json_encode($dataPointsLine, JSON_NUMERIC_CHECK); ?>
        }]
    });

    chart.render();

    
}
</script>

 
    

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

    

    <!-- Chart JS -->
    
    

    <div id="chartContainer" style="height: 370px; width: 1200px; margin-left: 300px; margin-top: 50px;"></div>
    <div id="chartGoalContainer" style="height: 370px; width: 1200px; margin-left: 300px; margin-top: 50px; margin-bottom: 100px; "></div>
    <div id="chartBudgetContainer" style="height: 370px; width: 1200px; margin-left: 300px; margin-top: 50px; margin-bottom: 100px; "></div>
    
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <!-- JS -->
    <footer class="footer">
    <div class="footer-text">
        <p>Copyright &copy; 2024 | Dorelies Siloma</p>
    </div>

    
</footer>

    <script src="/admin/js/charts.js"></script>
    <script src="/admin/js/admin_script.js"></script>
    
    
</body>
</html> 