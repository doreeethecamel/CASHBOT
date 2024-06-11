<?php 
  

   include("php/config.php");
   $status = "";
   if(!isset($_SESSION['valid'])){
    header("Location: index.php");
   }
   


 
$goals = [];

if(isset($_POST['submit_goal'])){
    $goal_name = $_POST['goal_name'];
    $amount = floatval($_POST['amount']);
    $goals[] = ['name' => $goal_name, 'amount' => $amount];

    // Insert the goal into the user_goals table for the currently logged-in user
    $user_id = $_SESSION['id']; // Assuming you're using sessions for user authentication
    $sql = "INSERT INTO user_goals (user_id, goal_name, amount) VALUES ('$user_id', '$goal_name', '$amount')";
    if ($conn->query($sql) === TRUE) {
        $status = "Goal Added Successfully";
    } else {
        $status = "Goal Not Added. Error Occurred!";
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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp"
    rel="stylesheet">
    <!-- Boxicons CSS -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Goal Setter</title>
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
    <div class="homebar">
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
    </div>
   
    <div class="container_side">
    
    <main>
        
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form" id="goal-setter">
    <h1 class="form-title">Financial Goals Tracker</h1>
            <div class="goal-input-container">
        <label for="goal_name">Goal Name:</label>
        <input type="text" id="goal_name" name="goal_name" required></div>
        <div class="goal-input-container">
        <label for="amount">Amount Needed:</label>
        <input type="number" id="amount" name="amount" required></div>
        <div class="btn-container">
        <button type="submit" name="submit_goal">Set Goal</button></div>
    </form>

    <section class="table">
            <h2 class="table-header">Saved Goals</h2>
            <?php if (!empty($status)): ?>
                <p class="message"><?php echo $status; ?></p>
            <?php endif; ?>
            <div class="table-section">
            <table class="goal-table">
                <thead>
                    <tr>
                        <th>Goal Name</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    
            <?php
            // Retrieve and display saved goals from the database
            
            $user_id = $_SESSION['id']; // Assuming you're using sessions for user authentication
            $sql = "SELECT * FROM user_goals WHERE user_id = '$user_id'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                        // Display goal name and amount in each row
                        echo "<td>" . $row['goal_name'] . "</td>";
                        echo "<td>" . $row['amount'] . "</td>";
                        echo "<td>";
                        // Edit button
                        echo "<button class='edit-btn'><a href='edit_goal.php?goal_id=" . $row['id'] . "'><i class='bx bx-edit icon edit'></i></a></button>";
                        // Delete button
                        echo "<button class='delete-btn'><a href='delete_goal.php?goal_id=" . $row['id'] . "'><i class='bx bx-trash icon delete'></i></a></button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No goals saved yet.</td></tr>";
                }
                $conn->close();
                ?></tbody>
                            
            </table>
        </section>
                      
      
    </main>
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