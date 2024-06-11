<?php 
  

   include("php/config.php");
   $status = "";


// Redirect to index.php if user is not logged in
if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
    exit(); // Stop further execution
}
// Check if the form has been submitted
if(isset($_POST['send_message'])) {
    // Retrieve form data
    $user_id = $_SESSION['id'];
    $message = $_POST['message'];

    // Prepare and execute the SQL statement to insert the message into the 'user_messages' table
    $sql = "INSERT INTO user_messages (user_id, messages) VALUES ('$user_id','$message')";
    if ($conn->query($sql) === TRUE) {
     $status = "Message Sent.";
    
    } else {
      $status = "Error Occurred! Message Not Sent.";
     
    }
}
    
    // Fetch user data for the form
    $id = $_SESSION['id'];
    $query = $conn->query("SELECT * FROM users WHERE Id=$id");
    while($result = mysqli_fetch_assoc($query)){
    $res_Uname = $result['Username'];
    $res_Email = $result['Email'];
    $res_id = $result['Id'];
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

    <title>FAQ</title>
    <script src="/admin/js/admin_script.js"></script>
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
    <nav class="homebar">
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
        </nav>
   
        <div class="accordion">
    
    <div class="accordion-text">
      <div class="title">FAQ</div>
    <ul class="faq-text">
      <li>
        <div class="question-arrow">
          <span class="question">How to Use the Personal Financial Chatbot?</span>
          <i class="bx bxs-chevron-down arrow"></i>
        </div>
        <p>The Personal Financial Chatbot is designed to assist you with various financial queries. Follow these steps to use the chatbot:
        <br>1. Type your financial query or question in the chat input field.
        <br>2. Press the send button or hit Enter to submit your message.
        <br>3. The chatbot will process your message and provide a response.
        <br>4. You can continue the conversation by typing additional queries.
        <br>5. To end the conversation, simply reload the page or navigate away from the page.
        </p>
        <span class="line"></span>
      </li>
      <li>
        <div class="question-arrow">
          <span class="question">How to Use the Budget Advisor?</span>
          <i class="bx bxs-chevron-down arrow"></i>
        </div>
        <p>The Budget Advisor helps you manage your finances more effectively. Here's how to use it:
        
        <br>1.Enter your monthly income in the "Monthly Income" field.
        <br>2.Enter your monthly expenses in the "Monthly Expenses" field.
        <br>3.Set your monthly savings goal in the "Monthly Savings Goal" field.
        <br>4.Click the "Get Budget Advice" button to receive personalized budget advice.
        <br>5.The Budget Advisor will analyze your financial data and provide advice based on your income, expenses, and savings goal.
        <br>6.Review the advice provided and consider adjusting your budget accordingly.
        <br>7.To delete a budget advice, click the delete icon (trash can) next to the advice you want to remove.
        </p>
        <span class="line"></span>
      </li>
      <li>
        <div class="question-arrow">
          <span class="question">How to Use the Goal Setter?</span>
          <i class="bx bxs-chevron-down arrow"></i>
        </div>
        <p>The Goal Setter allows you to track and manage your financial goals. Here's how to use it:
        
        <br>1. Enter the name of your financial goal in the "Goal Name" field.
        <br>2. Specify the amount needed to achieve your goal in the "Amount Needed" field.
        <br>3. Click the "Set Goal" button to save your financial goal.
        <br>4. Review your saved goals in the table below.
        <br>5. To edit a goal, click the edit icon (pencil) next to the goal you want to modify.
        <br>6. To delete a goal, click the delete icon (trash can) next to the goal you want to remove.
        </p>
        <span class="line"></span>
      </li>
      <li>
        <div class="question-arrow">
          <span class="question">How to Change you Personal Details?</span>
          <i class="bx bxs-chevron-down arrow"></i>
        </div>
        <p>You can easily update your personal details by following these steps:
        
            <br>1. Navigate to the "Settings" page by clicking on your profile icon and selecting "Settings" from the menu.
            <br>2. In the "Account" section, you will find fields to edit your username, email, and password.
            <br>3. Enter your new details in the respective input fields.
            <br>4. Click the "Save" button to update your changes.
            <br>5. If you want to cancel the changes, click the "Cancel" button.
        </p>
        <span class="line"></span>
      </li>
      <li>
        <div class="question-arrow">
          <span class="question">How is Your Personal Data Handled by CASHBOT?</span>
          <i class="bx bxs-chevron-down arrow"></i>
        </div>
        <p>CASHBOT takes the protection of your personal data seriously and follows strict guidelines to ensure your privacy. Here's how we handle your data:
        
            <br>1. <strong>Data Security:</strong> We employ robust security measures to safeguard your personal information from unauthorized access, disclosure, alteration, or destruction.
            <br>2. <strong>Data Collection:</strong> We only collect the necessary personal information required to provide our services to you. This may include your username, email address, and other relevant details.
            <br>3. <strong>Data Usage:</strong> Your personal data is used solely for the purpose of delivering our services to you. We do not sell or share your information with third parties for marketing purposes without your explicit consent.
            <br>4. <strong>Data Access:</strong> You have the right to access, update, or delete your personal information stored in our systems. You can manage your account settings to control how your data is used.
            <br>5. <strong>Transparency:</strong> We are committed to transparency regarding our data handling practices. Our privacy policy provides detailed information about how we collect, use, and protect your personal information.
        </p>
        <span class="line"></span>
      </li>
    </ul>
    </div>
  </div>
  
  <div class="contact-container">
    <div class="contact-content">
     
              
      <div class="left-side">
        <div class="address details">
        <i class='bx bxs-map'></i>
          <div class="topic">Address</div>
          <div class="text-one">Nairobi, Kenya</div>
          <div class="text-two">Ongata Rongai</div>
        </div>
        <div class="phone details">
        <i class='bx bxs-phone'></i>
          <div class="topic">Phone</div>
          <div class="text-one">+254 123 456 789</div>
          <div class="text-two">+254 987 654 321</div>
        </div>
        <div class="email details">
        <i class='bx bxs-envelope' ></i>
          <div class="topic">Email</div>
          <div class="text-one">cashbot@gmail.com</div>
          <div class="text-two">info.cashbot@gmail.com</div>
        </div>
      </div>
      <div class="right-side">
      <?php if (!empty($status)): ?>
                <p class="message"><?php echo $status; ?></p>
            <?php endif; ?>
        <div class="topic-text">Send us a message</div>
        <p>If you have any complaint, request, question or need any type of assistance related to CASHBOT, send a message in the form below.</p>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <input type="hidden" name="user_id" value="<?php echo $res_id; ?>">
        <div class="input-box">
        <input type="text" placeholder="Enter your name" name="name" value="<?php echo $res_Uname; ?>" required readonly>
        </div>
        <div class="input-box">
        <input type="email" placeholder="Enter your email" name="email" value="<?php echo $res_Email; ?>" required readonly>
        </div>
        <div class="input-box message-box">
        <textarea type="text" placeholder="Enter your message" name="message" required></textarea>
        </div>
        
        <button type="submit" value="Send Now" name="send_message">Send Now</button>
      </form>
    </div>
    </div>
  </div>
   
<!--footer-->

<footer class="footer">
    <div class="footer-text">
        <p>Copyright &copy; 2024 | Dorelies Siloma</p>
    </div>

    
</footer>


<script>
    const faqItems = document.querySelectorAll(".faq-text li");
    faqItems.forEach(item => {
        item.addEventListener("click", () => {
            item.classList.toggle("showAnswer");
        });
    });

    
</script>



     <script src="script.js"></script>
     <script src="/admin/js/admin_script.js"></script>
</body>
</html>