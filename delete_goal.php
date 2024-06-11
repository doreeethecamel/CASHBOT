<?php
include("php/config.php");
if(!isset($_SESSION['valid'])){
 header("Location: index.php");
}

// Check if the goal ID is provided in the URL
if (isset($_GET['goal_id'])) {
    // Retrieve the goal ID from the URL
    $goal_id = $_GET['goal_id'];

    // Retrieve goal details from the database based on the provided goal ID
    $query = $conn->query("SELECT * FROM user_goals WHERE id = $goal_id");
    $result = mysqli_fetch_assoc($query);
        $goal_name = $result['goal_name'];
        $amount = $result['amount'];
    } 

     

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_goal'])) {
    // Sanitize and set variables from POST data
    $goal_id = $_POST['goal_id'];

    // Prepare the update statement
    $delete_query = $conn->prepare("DELETE FROM user_goals WHERE id=?");

    // Check if prepare() succeeded
    if ($delete_query === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    // Bind parameters
    $delete_query->bind_param("i",  $goal_id);

    // Execute the update query
    if ($delete_query->execute()) {
        // Redirect to the page where the table is displayed after successful deletion
        header("Location: /goal.php");
        exit(); // Stop further execution
    } else {
        // Display error if deletion fails
        echo "Error: " . $delete_query->error;
    }

    // Close the statement
    $update_query->close();
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
    <title>Delete Goal</title>
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


    <form class="form" method="POST">
       <p class="form-title">Delete Goal</p>
       <input type="hidden" name="goal_id" value="<?php echo $goal_id; ?>">
        <div class="input-container">
        <label for="">Goal Name</label>
          <input type="text" name="goal_name" value="<?php echo $goal_name; ?>" readonly>
          <span>
          </span>
      </div>
      <div class="input-container">
      <label for="">Amount</label>
          <input type="number" name="amount" value="<?php echo $amount; ?>" readonly>
          <span>
          </span>
      </div>
      
        <div class="btn-container">
         <button type="submit" class="add_user" name="delete_goal">
            
        Delete Goal
      </button>
      <button type="button" class="back" onclick="window.location.href='goal.php'">Back</button>
      </div>
      
   </form>
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