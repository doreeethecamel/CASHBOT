<?php 
   

   include("php/config.php");
   $status = "";
   if(!isset($_SESSION['valid'])){
    header("Location: index.php");
   }
   if (isset($_POST['id'])) {
    $record_id = $_POST['id'];
    $user_id = $_SESSION['id'];
    $sql = "DELETE FROM user_budgets WHERE id = ? AND user_id = ?";
    $delete_budget = $conn->prepare($sql);
    $delete_budget->bind_param("ii", $record_id, $user_id);
    
    if ($delete_budget->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Unable to delete the record."]);
    }
    
    $delete_budget->close();
    
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
    <title>Budget Advisor</title>
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
    <main>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form" id="budget-advisor">
    <h1 class="form-title">Budget Advisor</h1>
    <div class="goal-input-container">
        <label for="income">Monthly Income:</label>
        <input type="number" id="income" name="income" required></div>
        <div class="goal-input-container">
        <label for="expenses">Monthly Expenses:</label>
        <input type="number" id="expenses" name="expenses" required></div>
        <div class="goal-input-container">
        <label for="savings">Monthly Savings Goal:</label>
        <input type="number" id="savings" name="savings" required></div>
        <div class="btn-container">
        <button type="submit" name="submit_budget">Get Budget Advice</button></div>
    </form>

    
   
    <?php

function encryptData($data, $key, $iv) {
    return openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
}

function decryptData($data, $key, $iv) {
    return openssl_decrypt($data, 'AES-256-CBC', $key, 0, $iv);
}
//  while ($row = $result->fetch_assoc()) {
    //     $decrypted_income = decryptData($row['income'], ENCRYPTION_KEY, ENCRYPTION_IV);
    //     $decrypted_expenses = decryptData($row['expenses'], ENCRYPTION_KEY, ENCRYPTION_IV);
    //     $decrypted_savings = decryptData($row['savings'], ENCRYPTION_KEY, ENCRYPTION_IV);
    //     $decrypted_total_spending = decryptData($row['total_spending'], ENCRYPTION_KEY, ENCRYPTION_IV);
    //     $decrypted_net_income = decryptData($row['net_income'], ENCRYPTION_KEY, ENCRYPTION_IV);
    //     $decrypted_advice = decryptData($row['advice'], ENCRYPTION_KEY, ENCRYPTION_IV);
    //     echo '<tr>
     
    //         <td>Ksh. ' . $decrypted_income . '</td>
    //         <td>Ksh. ' . $decrypted_expenses . '</td>
    //         <td>Ksh. ' . $decrypted_savings . '</td>
    //         <td>Ksh. ' . $decrypted_total_spending . '</td>
    //         <td>Ksh. ' . $decrypted_net_income . '</td>
    //         <td>' . $decrypted_advice . '</td>
    /* // Encrypt data
    $encrypted_income = encryptData($income, ENCRYPTION_KEY, ENCRYPTION_IV);
    $encrypted_expenses = encryptData($expenses, ENCRYPTION_KEY, ENCRYPTION_IV);
    $encrypted_savings = encryptData($savings, ENCRYPTION_KEY, ENCRYPTION_IV);
    $encrypted_total_spending = encryptData($total_spending, ENCRYPTION_KEY, ENCRYPTION_IV);
    $encrypted_net_income = encryptData($net_income, ENCRYPTION_KEY, ENCRYPTION_IV);
    $encrypted_advice = encryptData($advice, ENCRYPTION_KEY, ENCRYPTION_IV); */
if(isset($_POST['submit_budget'])){
    $income = floatval($_POST['income']);
    $expenses = floatval($_POST['expenses']);
    $savings = floatval($_POST['savings']);

    // Calculate total spending (expenses + savings)
    $total_spending = $expenses + $savings;

    // Calculate the remaining amount after spending
    $net_income = $income - $total_spending;

    // Generate budget advice based on the remaining amount
    if ($net_income > 0) {
        $advice = "You have some extra money! Consider saving it or investing it.";
    } elseif ($net_income < 0) {
        $advice = "You are spending more than you earn! Try to reduce your expenses or find ways to increase your income.";
    } else {
        $advice = "You are spending exactly what you earn. Consider budgeting to manage your finances better.";
    }

    
     // Insert the budget information into the user_budgets table for the currently logged-in user
     $user_id = $_SESSION['id']; 
     $sql = "INSERT INTO user_budgets (user_id, income, expenses, savings, total_spending, net_income, advice) 
             VALUES ('$user_id', '$income', '$expenses', '$savings', '$total_spending', '$net_income', '$advice')";
     if ($conn->query($sql) === TRUE) {
        $status = "Budget Advice Received Successfully";
     } else {
        $status = "Error Occurred! Budget Advice Not Received";
     }}

     
     // Retrieve and display user-specific budget advice from the database
    $user_id = $_SESSION['id'];
    $sql = "SELECT * FROM user_budgets WHERE user_id = '$user_id'";
    $result = $conn->query($sql);

    // Check if there are budget records for the user
    if ($result->num_rows > 0) {
    // Display budget records
    echo '
      <section class="table">
            <h2 class="table-header">Budgets Made</h2>';
            if (!empty($status)): ?>
                <p class="message"><?php echo $status; ?></p>
            <?php endif; 
            echo '<div class="table-section">
    <table class="goal-table">
    <thead>
     <tr>
         <th>Income</th>
         <th>Expenses</th>
         <th>Savings</th>
         <th>Total Spending</th>
         <th>Remaining Amount</th>
         <th>Advice</th>
         <th>Actions</th>
     </tr>
     </thead>
     <tbody>';
     while ($row = $result->fetch_assoc()) {
        echo '<tr>
     
        <td>Ksh. ' . $row['income'] . '</td>
        <td>Ksh. ' . $row['expenses'] . '</td>
        <td>Ksh. ' . $row['savings'] . '</td>
        <td>Ksh. ' . $row['total_spending'] . '</td>
        <td>Ksh. ' . $row['net_income'] . '</td>
        <td>' . $row['advice'] . '</td>
    
        <td><button class="delete-btn" onclick="deleteRecord(' . $row['id'] . ')"><i class="bx bx-trash icon delete"></i></button></td>
            </tr>';
     }
     echo '</tbody>
     </table>';
} else {
echo "<p>No budgets made yet.</p>";
}?>
       
    
    
    </main>
    </div>
<!--footer-->

<footer class="footer">
    <div class="footer-text">
        <p>Copyright &copy; 2023 | Dorelies Siloma</p>
    </div>

   
</footer>
     <script src="script.js"></script>
     <script src="/admin/js/admin_script.js"></script>
     <script>
        function deleteRecord(id) {
            if (confirm('Are you sure you want to delete this record?')) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "budget_assist.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === "success") {
                            var row = document.getElementById("record-" + id);
                            row.parentNode.removeChild(row);
                        } else {
                            alert(response.message);
                        }
                    }
                };
                xhr.send("id=" + id);
            }
        }
    </script>
</body>
</html>