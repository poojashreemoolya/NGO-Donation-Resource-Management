// Load and display resources
function loadResources() {

    fetch("backend/get_resources.php")
        .then(response => response.json())
        .then(resources => {

            const table = document.getElementById("resourceTable");
            table.innerHTML = "";

            // Search and category filter
            const searchText = document.getElementById("searchResource").value.toLowerCase();
            const selectedCategory = document.getElementById("filterCategory").value;

            const filteredResources = resources.filter(resource => {

                return resource.resource_name.toLowerCase().includes(searchText) &&
                    (selectedCategory === "" || resource.category === selectedCategory);

            });

            filteredResources.forEach(resource => {

                const row = document.createElement("tr");
                if (resource.quantity <= 5) {
    row.style.backgroundColor = "#ffcccc";
}

                row.innerHTML = `
                    <td>${resource.id}</td>
                    <td>${resource.resource_name}</td>
                    <td>${resource.category}</td>
                    <td>${resource.quantity}</td>
                    <td>${resource.unit}</td>
                    <td>${resource.description || ""}</td>
                    <td>
                        <button class="btn" onclick="editResource(${resource.id})">
                            Edit
                        </button>

                        <button class="btn" onclick="deleteResource(${resource.id})">
                            Delete
                        </button>
                    </td>
                `;

                table.appendChild(row);

            });

        })
        .catch(error => console.error("Error loading resources:", error));
}


// Add new resource
document.getElementById("resourceForm").addEventListener("submit", function(e) {

    e.preventDefault();

    const data = new URLSearchParams();

    data.append("resource_name", document.getElementById("resourceName").value);
    data.append("category", document.getElementById("category").value);
    data.append("quantity", document.getElementById("quantity").value);
    data.append("unit", document.getElementById("unit").value);
    data.append("description", document.getElementById("description").value);

    fetch("backend/add_resource.php", {
        method: "POST",
        body: data
    })
    .then(response => response.text())
    .then(result => {

        alert(result);

        document.getElementById("resourceForm").reset();

        loadResources();

    })
    .catch(error => console.error("Error adding resource:", error));

});


// Delete resource
function deleteResource(id) {

    if (!confirm("Are you sure you want to delete this resource?")) {
        return;
    }

    const data = new URLSearchParams();
    data.append("id", id);

    fetch("backend/delete_resource.php", {
        method: "POST",
        body: data
    })
    .then(response => response.text())
    .then(result => {
        alert(result);
        loadResources();
    })
    .catch(error => console.error("Delete error:", error));
}


// Edit resource
function editResource(id) {

    fetch("backend/get_resources.php")
        .then(response => response.json())
        .then(resources => {

            const resource = resources.find(item => item.id == id);

            if (!resource) {
                alert("Resource not found!");
                return;
            }

            const name = prompt("Enter Resource Name:", resource.resource_name);
            if (name === null) return;

            const category = prompt("Enter Category:", resource.category);
            if (category === null) return;

            const quantity = prompt("Enter Quantity:", resource.quantity);
            if (quantity === null) return;

            const unit = prompt("Enter Unit:", resource.unit);
            if (unit === null) return;

            const description = prompt("Enter Description:", resource.description);
            if (description === null) return;

            const data = new URLSearchParams();

            data.append("id", id);
            data.append("resource_name", name);
            data.append("category", category);
            data.append("quantity", quantity);
            data.append("unit", unit);
            data.append("description", description);

            fetch("backend/update_resource.php", {
                method: "POST",
                body: data
            })
            .then(response => response.text())
            .then(result => {
                alert(result);
                loadResources();
            });

        })
        .catch(error => console.error("Edit error:", error));
}


// Search resources while typing
document.getElementById("searchResource").addEventListener("input", loadResources);


// Filter resources by category
document.getElementById("filterCategory").addEventListener("change", loadResources);


// Display resources when page opens
loadResources();