<?php 

include('../php/config.php');

// Default SQL query to fetch all users
$query = "SELECT * FROM users";

// Check if month and year filters are applied
if(isset($_GET['month']) || isset($_GET['year'])) {
    // Initialize filter conditions
    $filterConditions = array();

    if(isset($_GET['month']) && $_GET['month'] !== "") {
        // If month is selected, construct condition for the selected month
        $selectedMonth = $_GET['month'];
        $filterConditions[] = "MONTH(timestamp) = '$selectedMonth'";
    }

    if(isset($_GET['year']) && $_GET['year'] !== "") {
        // If year is selected, construct condition for the selected year
        $selectedYear = $_GET['year'];
        $filterConditions[] = "YEAR(timestamp) = '$selectedYear'";
    }

    // Check if there are any filter conditions
    if (!empty($filterConditions)) {
    // Combine filter conditions using "AND"
    $filter = implode(" AND ", $filterConditions);

    // Add filter conditions to the SQL query
    $query .= " WHERE " . $filter;
}}

$result = mysqli_query($conn,$query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style/admin_style.css">
    <!-- Boxicons CSS -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>View Users</title>
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
    <div class="home">
    <div class="table">
        <div class="table-header">
            <p>All Users Registered</p>
            <div class="search">
                    <form method="POST" action="/admin/crud_user/add_user.php">
                    
                        <button type="submit" class="addnew">Add New</button>
                    </form>
            </div>
            <div class="filter">
            <form method="GET" action="">
                <label for="month">Filter by Month:</label>
                <select id="month" name="month">
                    <option value="">Select Month</option>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo date("F", mktime(0, 0, 0, $i, 1)); ?></option>
                    <?php endfor; ?>
                </select>
                <label for="year">Filter by Year:</label>
                <select id="year" name="year">
                    <option value="">Select Year</option>
                    <?php for ($i = date("Y"); $i >= 2000; $i--): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
                <button type="submit">Apply Filter</button>
            </form>
        </div>
        </div>
        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Actions</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        // Fetch all users if no filters applied
                        if(!isset($_GET['month']) || !isset($_GET['year']) || ($_GET['month'] == "" && $_GET['year'] == "")) {
                            $result = mysqli_query($conn, "SELECT * FROM users");
                        }

                        while($row = mysqli_fetch_assoc($result))
                        {

                            ?>
                            <td><?php echo $row['Id']; ?></td>
                            <td><?php echo $row['Username']; ?></td>
                            <td><?php echo $row['Email']; ?></td>
                            <td><?php echo $row['Password']; ?></td>
                            <td>
                                <button>
                                <a href="/admin/crud_user/edit_user.php?user_id=<?php echo $row['Id'];?>"><i class='bx bx-edit icon edit'></i></a></button>
                                <button>
                                <a href="/admin/crud_user/delete_user.php?user_id=<?php echo $row['Id'];?>"><i class='bx bx-trash icon delete'></i></a></button>
                            </td>
                        </tr>
                            <?php
                                   
                        }
                        ?></tbody>
                            
                        </table>
                    </div>
    </div>
    </div>
    <footer class="footer">
    <div class="footer-text">
        <p>Copyright &copy; 2024 | Dorelies Siloma</p>
    </div>

    
</footer>
    <script src="/admin/js/admin_script.js"></script>
</body>
</html>