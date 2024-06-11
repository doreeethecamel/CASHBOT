<?php 
  

   include("../php/config.php");

  
   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin FAQ</title>
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

<div class="accordion">
    
  <div class="accordion-text">
      <div class="title">FAQ</div>
    <ul class="faq-text">
    <li>
        <div class="question-arrow">
          <span class="question">What is the Information displayed on the Dashboard?</span>
          <i class="bx bxs-chevron-down arrow"></i>
        </div>
        <p>This is the information displayed on the dashboard once logged in:
        <br>1. Total Users: This indicates the number of users registered on the platform.
        <br>2. Total Messages: This displays the total number of messages exchanged within the platform.
        <br>3. Total Notifications: This shows the count of notifications generated by the system.
        <br>4. Under the "New Users" title, you'll find a list of users displayed along with their registration timestamps.
        <br>5. To view more new users, click on the "More" link:
                This link redirects you to the "View Users" page, where you can see a comprehensive list of all users registered.
        <br>6. Below the "New Users" section, you'll find a task input field labeled "Add a new task."
        <br><small>-> Enter a task in the input field and press Enter to add it to the task list.</small>
        <br><small>-> You can filter tasks based on their status:
                Use the "All," "Pending," and "Completed" buttons to filter tasks accordingly.</small>
        <br><small>-> To clear all tasks from the list, click on the "Clear All" button.</small>
        
        </p>
        <span class="line"></span>
      </li>
    <li>
        <div class="question-arrow">
          <span class="question">How to View Users Registered?</span>
          <i class="bx bxs-chevron-down arrow"></i>
        </div>
        <p>Here's how to add a new user to the CASHBOT database:
        <br>1. Navigate to the "Users" section from the sidebar menu on the page.
        <br>2. Upon reaching the "Users" page, you'll find a table displaying all users saved to the CASHBOT database.
        <br>3. Use the filter options provided at the top of the table to refine your users view based on the month and year.
        <br>4. To apply filters, select a month and/or year from the dropdown menus provided.
        <br>5. Click the "Apply Filter" button to activate the selected filters.
        <br>6. After applying filters, the table will update to display users registered within the selected month and year.
        <br>7. Review the users in the table, which includes columns for ID, Username, Email, Password, and Date Sent.
        <br>8. To clear filters and view all users again, reset the filter options by selecting "Select Month" and "Select Year" from the dropdown menus.
        </p>
        <span class="line"></span>
      </li>
      <li>
        <div class="question-arrow">
          <span class="question">How to Add New User?</span>
          <i class="bx bxs-chevron-down arrow"></i>
        </div>
        <p>Here's how to add a new user to the CASHBOT database:
        <br>1. Navigate to the "Users" section from the sidebar menu.
        <br>2. Upon reaching the "Users" page, you'll find a table displaying all users saved to the CASHBOT database.
        <br>3. In the table header, click on the "Add New" button.
        <br>4. This action will take you to a new page, where you'll see a form titled "Add New User."
        <br>5. Fill in the required information, including username, email, and password.
        <br>6. Click on the "Add User" button to submit the form.
        <br>7. Upon successful submission, the user will be added to the system, and you will be redirected back to the Users table, where you can see the updated list of users.
        </p>
        <span class="line"></span>
      </li>
      
      <li>
        <div class="question-arrow">
          <span class="question">How to Edit User Information?</span>
          <i class="bx bxs-chevron-down arrow"></i>
        </div>
        <p>Here's how to edit an existing user's information:
        
        <br>1. Navigate to the "Users" section from the sidebar menu.
        <br>2. Upon reaching the "Users" page, you'll find a table displaying all users saved to the CASHBOT database.
        <br>3. Locate the user whose information you want to edit.
        <br>4. Click on the edit icon/button associated with that user.
        <br>5. This action will take you to a new page, where you'll see a form titled "Edit User" pre-filled with the user's current information.
        <br>6. Modify the desired fields (e.g., username, email, password).
        <br>7. Click on the "Update User" button to submit the form.
        <br>8. Upon successful submission, the user's information will be updated in the system, and you will be redirected back to the Users table, where you can view the updated user list.
        </p>
        <span class="line"></span>
      </li>
      <li>
        <div class="question-arrow">
          <span class="question">How to Delete a User?</span>
          <i class="bx bxs-chevron-down arrow"></i>
        </div>
        <p>Here's how to delete an existing user from the CASHBOT database:
        
        <br>1. Navigate to the "Users" section from the sidebar menu.
        <br>2. Upon reaching the "Users" page, you'll find a table displaying all users saved to the CASHBOT database.
        <br>3. Locate the user you want to delete.
        <br>4. Click on the delete icon/button associated with that user.
        <br>5. This action will take you to a new page, where you'll see a form titled "Edit User" pre-filled with the user's information for confirmation.
        <br>6. Review the user's details to ensure it's the correct user you want to delete.
        <br>7. Click on the "Delete User" button to confirm deletion.
        <br>8. Upon successful deletion, the user will be removed from the system, and you will be redirected back to the Users table, where you can view the updated user list.
        </p>
        <span class="line"></span>
      </li>
      <li>
        <div class="question-arrow">
          <span class="question">How to View User Messages?</span>
          <i class="bx bxs-chevron-down arrow"></i>
        </div>
        <p>Here's how to view user messages saved in the CASHBOT database:
        
        <br>1. Navigate to the "Messages" section from the sidebar menu on the page.
        <br>2. Upon reaching the "Messages" page, you'll find a table displaying all messages sent by users.
        <br>3. Use the filter options provided at the top of the table to refine your message view based on the month and year.
        <br>4. To apply filters, select a month and/or year from the dropdown menus provided.
        <br>5. Click the "Apply Filter" button to activate the selected filters.
        <br>6. After applying filters, the table will update to display messages sent only within the selected month and year.
        <br>7. Review the messages in the table, which includes columns for ID, Username, Email, Messages, and Date Sent.
        <br>8. To clear filters and view all messages again, reset the filter options by selecting "Select Month" and "Select Year" from the dropdown menus.
        
        </p>
        <span class="line"></span>
      </li>
      <li>
        <div class="question-arrow">
          <span class="question">How to Change you Personal Details?</span>
          <i class="bx bxs-chevron-down arrow"></i>
        </div>
        <p>You can easily update your personal details by following these steps:
        
            <br>1. Click on your profile icon, and then select "Account Details" from the menu. This will take you to the settings page.
            <br>2. On the settings page, you'll find fields to edit your username, email, and password.
            <br>3. Enter your new details in the respective input fields.
            <br>4. Click the "Save" button to update your changes.
            <br>5. If you want to cancel the changes, click the "Cancel" button.
        </p>
        <span class="line"></span>
      </li>
      <li>
        <div class="question-arrow">
          <span class="question">How is Monitor User Registration Trend?</span>
          <i class="bx bxs-chevron-down arrow"></i>
        </div>
        <p>You can easily monitor user registration trends by following these steps:
        
            <br>1. From the dashboard, click on the "Analytics" tab in the sidebar menu.
            <br>2. You will be directed to the page displaying the user registration analysis chart, where you can explore trends in user registrations over time.
            
        </p>
        <span class="line"></span>
      </li>
    </ul>
  </div>
</div>
    
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


    <script src="/admin/js/admin_script.js"></script>
</body>
</html> 