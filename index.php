<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Student Marks Bar Chart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <style>
        #chart-container {
            max-width: 600px;
            margin: 20px auto;
        }

        .filters {
            background-color: #fff;
            /* min-width: 10vw;
            max-width: 40vw; */
        }
    </style>
</head>

<body>

    <div class="container">
        <h1 class="mt-5 text-center">Monthly Student Marks</h1>
        <button class="btn btn-info mb-3" onclick="getStudentReport()">Clear Filter</button>
        <div class="row">
            <div class="col-4 align-items-center ">
                <div class="row">
                    <div class="col-lg-12 p-3 border-0 border-secondary rounded-3 shadow-lg filters">
                        <div class="row" id="months">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <h4 class="my-3 text-center">OR</h4>
                    </div>
                    <div class="col-lg-12 p-3 border-0 border-secondary rounded-3 shadow-lg filters">
                        <div class="row" id="subjects">

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <canvas id="myChart"></canvas>
            </div>
        </div>
        <div class="d-grid flex-column justify-content-center align-items-center text-center">
            <div class="d-flex justify-content-between align-items-center">
                <div class="row">

                </div>
                <!-- <div id="months"></div> -->
            </div>
            <div class="d-flex justify-content-center align-items-center text-center">
                <div id="subjects"></div>
            </div>
        </div>
        <div id="chart-container">
            <!-- <canvas id="myChart"></canvas> -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./report.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

</body>

</html>