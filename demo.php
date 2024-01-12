<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animated Chart</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <canvas id="myChart" width="400" height="200"></canvas>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart data and options
            var data = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', "Jun"],
                datasets: [
                    {
                        label: 'My Dataset',
                        data: [12, 19, 3, 5, 2, 1],
                        backgroundColor: 'rgba(75,192,192,0.2)',
                        borderColor: 'rgba(75,192,192,1)',
                        borderWidth: 1,
                    },
                    {
                        label: 'My 11',
                        data: [2, 9, 13, 15, 12,5],
                        backgroundColor: 'rgba(75,192,192,0.2)',
                        borderColor: 'rgba(75,192,192,1)',
                        borderWidth: 1,
                    },
                    {
                        label: 'My 22',
                        data: [18, 16, 2, 7, 8, 6],
                        backgroundColor: 'rgba(75,192,192,0.2)',
                        borderColor: 'rgba(75,192,192,1)',
                        borderWidth: 1,
                    },
                    {
                        label: 'My 27',
                        data: [8, 6, 12, 17, 18, 7],
                        backgroundColor: 'rgba(75,192,192,0.2)',
                        borderColor: 'rgba(75,192,192,1)',
                        borderWidth: 1,
                    },
                ],
            };

            var options = {
                animation: {
                    duration: 3000, // Animation duration in milliseconds
                    easing: 'easeInOutQuart', // Easing function for the animation
                },
            };

            // Create a bar chart
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: data,
                options: options,
            });
        });
    </script>

</body>

</html>