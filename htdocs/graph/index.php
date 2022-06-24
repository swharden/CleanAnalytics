<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function fetchData() {
            const url = 'api/hitsByHour.php?urlMatch=' + document.getElementById("urlMatch").value;
            fetch(url)
                .then(response => response.json())
                .then(data => updateChartByHour(data));

            const url2 = 'api/hitsByDay.php?urlMatch=' + document.getElementById("urlMatch").value;
            fetch(url2)
                .then(response => response.json())
                .then(data => updateChartByDay(data));
        }

        function updateChartByDay(data) {
            const labels = Object.keys(data);
            const values = Object.values(data);

            // "#1f77b4", "#ff7f0e", "#2ca02c", "#d62728", "#9467bd",
            // "#8c564b", "#e377c2", "#7f7f7f", "#bcbd22", "#17becf",

            const chartData = {
                labels: labels,
                legend: {
                    display: false
                },
                datasets: [{
                    label: "Page Views",
                    backgroundColor: '#1f77b4',
                    borderColor: '#1f77b4',
                    data: values,
                }]
            };

            const config = {
                type: 'bar',
                data: chartData,
                options: {}
            };

            const oldChart = Chart.getChart("chartByDay");
            if (oldChart)
                oldChart.destroy();

            const newChart = new Chart(
                document.getElementById('chartByDay'),
                config
            );

        }

        function updateChartByHour(data) {
            const labels = Object.keys(data);
            const values = Object.values(data);

            // "#1f77b4", "#ff7f0e", "#2ca02c", "#d62728", "#9467bd",
            // "#8c564b", "#e377c2", "#7f7f7f", "#bcbd22", "#17becf",

            const chartData = {
                labels: labels,
                legend: {
                    display: false
                },
                datasets: [{
                    label: "Page Views",
                    backgroundColor: '#1f77b4',
                    borderColor: '#1f77b4',
                    data: values,
                }]
            };

            const config = {
                type: 'bar',
                data: chartData,
                options: {}
            };

            const oldChart = Chart.getChart("chartByHour");
            if (oldChart)
                oldChart.destroy();

            const newChart = new Chart(
                document.getElementById('chartByHour'),
                config
            );

        }
    </script>
</head>

<body>


    <div class="text-center my-5">
        <div class="text-center py-3 border rounded bg-light d-inline-block">
            <span>URL Filter:</span>
            <input type="text" class="form-control d-inline-block w-50" value="" id="urlMatch"></input>
            <button onclick="fetchData()" class="btn btn-primary">Update</button>
        </div>
    </div>

    <div>
        <canvas id="chartByDay"></canvas>
        <canvas id="chartByHour"></canvas>
    </div>

    <script>
        fetchData();
    </script>

</body>

</html>