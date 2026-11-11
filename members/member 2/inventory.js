// Load dashboard statistics
fetch("backend/get_stats.php")
.then(response => response.json())
.then(data => {

    document.getElementById("totalResources").textContent = data.totalResources;

    document.getElementById("availableStock").textContent = data.availableStock;

    document.getElementById("distributedResources").textContent = data.distributedResources;

    document.getElementById("lowStock").textContent = data.lowStock;

    // Display Low Stock Alert
    const alertMessage = document.getElementById("lowStockMessage");

    if (data.lowStock > 0) {
        alertMessage.textContent = data.lowStock +
            " resource(s) are running low. Please replenish stock!";
    } else {
        alertMessage.textContent =
            "All resources have sufficient stock!";
    }

})
.catch(error => console.error("Error loading dashboard:", error));