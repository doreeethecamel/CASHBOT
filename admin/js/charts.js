// Function to fetch data and update the chart based on filters
function updateChart() {
    // Get selected month and year
    var selectedMonth = document.getElementById("monthFilter").value;
    var selectedYear = document.getElementById("yearFilter").value;

    // AJAX request to fetch filtered data from the server
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "analytics.php?month=" + selectedMonth + "&year=" + selectedYear, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                // Data received, update the chart
                try {
                    var data = JSON.parse(xhr.responseText);
                    updateChartWithData(data);
                } catch (error) {
                    console.error("Error parsing JSON:", error);
                }
            } else {
                console.error("Failed to fetch data (status code " + xhr.status + ")");
            }
        }
    };
    xhr.send();
}

// Function to update the chart with new data
function updateChartWithData(data) {
    var labels = Object.keys(data);
    var values = Object.values(data);

    // Get canvas element
    var ctx = document.getElementById('userChart').getContext('2d');

    // Clear existing chart (if any)
    if (window.userChart !== undefined) {
        window.userChart.destroy();
    }

    // Create new chart
    window.userChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of Registrations',
                data: values,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}



// Initial chart update on page load
updateChart();