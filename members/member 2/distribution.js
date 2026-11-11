// Load available resources
function loadResourceOptions() {

    fetch("backend/get_resources.php")
        .then(response => response.json())
        .then(resources => {

            const select = document.getElementById("resourceId");

            select.innerHTML = '<option value="">Select Resource</option>';

            resources.forEach(resource => {

                const option = document.createElement("option");

                option.value = resource.id;
                option.textContent = resource.resource_name +
                    " (Available: " + resource.quantity + " " + resource.unit + ")";

                select.appendChild(option);

            });

        })
        .catch(error => console.error(error));
}


// Load distribution records
function loadDistributions() {

    fetch("backend/get_distributions.php")
        .then(response => response.json())
        .then(records => {

            const table = document.getElementById("distributionTable");

            table.innerHTML = "";

            records.forEach(record => {

                const row = document.createElement("tr");

                row.innerHTML = `
                    <td>${record.id}</td>
                    <td>${record.resource_name}</td>
                    <td>${record.quantity}</td>
                    <td>${record.recipient_name}</td>
                    <td>${record.distribution_date}</td>
                    <td>${record.notes || ""}</td>
                `;

                table.appendChild(row);

            });

        })
        .catch(error => console.error(error));
}


// Submit distribution form
document.getElementById("distributionForm").addEventListener("submit", function(e) {

    e.preventDefault();

    const data = new URLSearchParams();

    data.append("resource_id", document.getElementById("resourceId").value);
    data.append("quantity", document.getElementById("quantity").value);
    data.append("recipient_name", document.getElementById("recipientName").value);
    data.append("distribution_date", document.getElementById("distributionDate").value);
    data.append("notes", document.getElementById("notes").value);

    fetch("backend/distribute.php", {
        method: "POST",
        body: data
    })

    .then(response => response.text())

    .then(result => {

        alert(result);

        document.getElementById("distributionForm").reset();

        loadResourceOptions();
        loadDistributions();

    })

    .catch(error => console.error(error));

});


loadResourceOptions();
loadDistributions();