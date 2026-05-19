function loadRevenueChart(){

    let xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function(){

        if(this.readyState == 4 && this.status == 200){

            let data = JSON.parse(this.responseText);

            let ctx = document.getElementById("revenueChart").getContext("2d");

            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: "Weekly Revenue",
                        data: data.revenues,
                        backgroundColor: "rgba(18, 92, 142, 0.6)",
                        borderColor: "rgba(18, 92, 142, 1)",
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    };

    xhttp.open("GET", "../../api/dashboard/revenue.php", true);
    xhttp.send();
}
