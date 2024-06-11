  //TOGGLE SWITCH
const body = document.querySelector("body"),
        sidebar = body.querySelector(".sidebar"),
        toggle = body.querySelector(".toggle"),
        searchBtn = body.querySelector(".search-box"),
        modeSwitch = body.querySelector(".toggle-switch"),
        modeText = body.querySelector(".mode-text");

        

        toggle.addEventListener("click", () =>{
            sidebar.classList.toggle("close");
        });

        searchBtn.addEventListener("click", () =>{
            sidebar.classList.remove("close");
        });

        modeSwitch.addEventListener("click", () =>{
            body.classList.toggle("dark");

            if(body.classList.contains("dark")){
                modeText.innerText = "Light Mode"
            }else{
                modeText.innerText = "Dark Mode";
            }
        });

li = document.querySelectorAll(".faq-text li");
    for (var i = 0; i < li.length; i++) {
        li[i].addEventListener("click", (e)=>{
        let clickedLi;
        if(e.target.classList.contains("question-arrow")){
          clickedLi = e.target.parentElement;
        }else{
          clickedLi = e.target.parentElement.parentElement;
        }
        clickedLi.classList.toggle("showAnswer");
        });
    }

      



// Selecting DOM elements
const taskInput = document.querySelector(".task-input input"), // Input field for adding tasks
      filters = document.querySelectorAll(".filters span"), // Filter buttons
      clearAll = document.querySelector(".clear-btn"), // Clear all tasks button
      taskBox = document.querySelector(".task-box"); // Task container

let editId, // Variable to store the ID of the task being edited
    isEditTask = false, // Flag to track if the task is being edited
    todos = JSON.parse(localStorage.getItem("todo-list")); // Array to store todo items retrieved from localStorage

// Event listener to handle filter button clicks
filters.forEach(btn => {
    btn.addEventListener("click", () => {
        document.querySelector("span.active").classList.remove("active");
        btn.classList.add("active");
        showTodo(btn.id); // Call the showTodo function with the id of the clicked filter button
    });
});

// Function to display todo items based on filter
function showTodo(filter) {
    let liTag = ""; // Initialize an empty string to store the HTML for todo items
    // Check if todos array exists
    if(todos) {
        todos.forEach((todo, id) => {
            // Determine if the todo item is completed or pending
            let completed = todo.status == "completed" ? "checked" : "";
            // Check if the todo item matches the selected filter or if all tasks are to be displayed
            if(filter == todo.status || filter == "all") {
                // Construct HTML for each todo item
                liTag += `<li class="task">
                            <label for="${id}">
                                <input onclick="updateStatus(this)" type="checkbox" id="${id}" ${completed}>
                                <p class="${completed}">${todo.name}</p>
                            </label>
                            <div class="settings">
                                <i onclick="showMenu(this)" class="uil uil-ellipsis-h"></i>
                                <ul class="task-menu">
                                    <li onclick='editTask(${id}, "${todo.name}")'><i class="uil uil-pen"></i>Edit</li>
                                    <li onclick='deleteTask(${id}, "${filter}")'><i class="uil uil-trash"></i>Delete</li>
                                </ul>
                            </div>
                        </li>`;
            }
        });
    }
    // Update the taskBox HTML content with the constructed liTag or display a message if there are no tasks
    taskBox.innerHTML = liTag || `<span>You don't have any task here</span>`;
    // Update the visibility of the clearAll button based on the presence of tasks
    let checkTask = taskBox.querySelectorAll(".task");
    !checkTask.length ? clearAll.classList.remove("active") : clearAll.classList.add("active");
    // Add overflow class to taskBox if it exceeds a certain height
    taskBox.offsetHeight >= 300 ? taskBox.classList.add("overflow") : taskBox.classList.remove("overflow");
}
showTodo("all"); // Display all tasks initially

// Function to show task menu
function showMenu(selectedTask) {
    let menuDiv = selectedTask.parentElement.lastElementChild; // Get the task menu div
    menuDiv.classList.add("show"); // Add 'show' class to display the menu
    // Event listener to hide the menu when clicked outside
    document.addEventListener("click", e => {
        if(e.target.tagName != "I" || e.target != selectedTask) {
            menuDiv.classList.remove("show");
        }
    });
}

// Function to update task status
function updateStatus(selectedTask) {
    let taskName = selectedTask.parentElement.lastElementChild; // Get the task name element
    if(selectedTask.checked) {
        taskName.classList.add("checked"); // Add 'checked' class to mark the task as completed
        todos[selectedTask.id].status = "completed"; // Update status in todos array
    } else {
        taskName.classList.remove("checked"); // Remove 'checked' class
        todos[selectedTask.id].status = "pending"; // Update status in todos array
    }
    localStorage.setItem("todo-list", JSON.stringify(todos)); // Update localStorage
}

// Function to edit a task
function editTask(taskId, textName) {
    editId = taskId; // Set the editId to the ID of the task being edited
    isEditTask = true; // Set the isEditTask flag to true
    taskInput.value = textName; // Set the input field value to the task name
    taskInput.focus(); // Set focus on the input field
    taskInput.classList.add("active"); // Add 'active' class to the input field
}

// Function to delete a task
function deleteTask(deleteId, filter) {
    isEditTask = false; // Reset the isEditTask flag
    todos.splice(deleteId, 1); // Remove the task from the todos array
    localStorage.setItem("todo-list", JSON.stringify(todos)); // Update localStorage
    showTodo(filter); // Refresh the task list based on the selected filter
}

// Event listener for clearAll button click
clearAll.addEventListener("click", () => {
    isEditTask = false; // Reset the isEditTask flag
    todos.splice(0, todos.length); // Remove all tasks from the todos array
    localStorage.setItem("todo-list", JSON.stringify(todos)); // Update localStorage
    showTodo(); // Refresh the task list
});

// Event listener for keyup event on taskInput
taskInput.addEventListener("keyup", e => {
    let userTask = taskInput.value.trim(); // Get the trimmed value of the input field
    // Check if Enter key is pressed and input field is not empty
    if(e.key == "Enter" && userTask) {
        if(!isEditTask) {
            todos = !todos ? [] : todos; // Ensure todos array exists
            let taskInfo = {name: userTask, status: "pending"}; // Create task object
            todos.push(taskInfo); // Add task to todos array
        } else {
            isEditTask = false; // Reset isEditTask flag
            todos[editId].name = userTask; // Update task name in todos array
        }
        taskInput.value = ""; // Clear the input field
        localStorage.setItem("todo-list", JSON.stringify(todos)); // Update localStorage
        showTodo(document.querySelector("span.active").id); // Refresh the task list based on filter
    }
});


// Define a function to handle toast messages
function handleToasts() {
    const toastButtons = document.querySelectorAll(".toast button");

    toastButtons.forEach(button => {
        button.addEventListener("click", (e) => {
            e.target.closest(".toast").classList.remove("show");
        });
    });
}

function showToast(type, message) {
    const toast = document.querySelector(`.toast.${type}`);
    const messageContainer = toast.querySelector(".container-2 p:nth-child(2)");
    messageContainer.textContent = message;
    toast.classList.add("show");

    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}

// Call the function when DOM content is loaded
document.addEventListener("DOMContentLoaded", handleToasts);




