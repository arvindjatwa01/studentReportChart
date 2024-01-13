<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Student Report</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">
        <div class="row my-5">
            <div class="col-auto">
                <button type="button" class="btn btn-info" onclick="handleClearAllFilters()">Clear Filter</button>
            </div>
            <div class="col text-center">
                <h1 id="studentName">Student Report</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-md-12 col-sm-12 align-items-center ">
                <div class="row my-2">
                    <div class="col-lg-12 col-sm-5 col-md-5 p-3 border-0 border-secondary rounded-3 shadow-lg filters">
                        <fieldset class="reset">
                            <legend class="reset" id="monthsFilter">Filter By Month <i class="fa-solid fa-eye"
                                    id="montheFilterEye"></i></legend>
                            <div class="row monthsClose" id="months"></div>
                        </fieldset>
                    </div>
                    <div
                        class="col-lg-12 col-sm-1 d-flex justify-content-center text-center col-md-1 align-item-center">
                        <h4 class="my-3 text-center">OR</h4>
                    </div>
                    <div class="col-lg-12 col-sm-5 col-md-5 p-3 border-0 border-secondary rounded-3 shadow-lg filters">
                        <fieldset class="reset">
                            <legend class="reset" id="subjectsFilter">Filter By Subject <i class="fa-solid fa-eye"
                                    id="sujectFilterEye"></i>
                            </legend>
                            <div class="row subjectsClose" id="subjects"></div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-12 col-sm-12 align-items-center h-100">
                <canvas id="myChart" class="barChart-fade-in"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="./report.js"></script> -->
    <script src="./studentReport.js"></script>

</body>

</html>