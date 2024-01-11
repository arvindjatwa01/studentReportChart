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

    <style>
        #myChart {
            /* max-width: 50vw; */
            margin: 20px auto;
            width: 50vw;
        }

        @media (min-width: 768px) {}

        .filters {
            background-color: #fff;
        }

        .reset {
            all: revert;
        }

        #montheFilterEye,
        #sujectFilterEye {
            /* display: none; */
        }


        @media only screen and (max-width: 768px) {
            #myChart {
                /* position: relative; */
                margin: auto;
                height: 48vh;
                width: 100vw;
                transform: rotate(90deg);
            }

            #montheFilterEye,
            #sujectFilterEye {
                /* display: block; */
            }

            .monthsOpen {
                -webkit-animation: conditionalOpen 1s normal forwards ease-in-out;
                -moz-animation: conditionalOpen 1s normal forwards ease-in-out;
                animation: conditionalOpen 1s normal forwards ease-in-out;
                -webkit-transform-origin: 50% 0%;
                -moz-transform-origin: 50% 0%;
            }

            .monthsClose {
                -webkit-animation: conditionalClose 1s normal forwards ease-in-out;
                -moz-animation: conditionalClose 1s normal forwards ease-in-out;
                animation: conditionalClose 1s normal forwards ease-in-out;
                -webkit-transform-origin: 100% 0%;
                -moz-transform-origin: 100% 0%;
                transform-origin: 100% 0%;
                height: 0;
            }

            .subjectsOpen {
                -webkit-animation: conditionalOpen 1s normal forwards ease-in-out;
                -moz-animation: conditionalOpen 1s normal forwards ease-in-out;
                animation: conditionalOpen 1s normal forwards ease-in-out;
                -webkit-transform-origin: 50% 0%;
                -moz-transform-origin: 50% 0%;
            }

            .subjectsClose {
                -webkit-animation: conditionalClose 1s normal forwards ease-in-out;
                -moz-animation: conditionalClose 1s normal forwards ease-in-out;
                animation: conditionalClose 1s normal forwards ease-in-out;
                -webkit-transform-origin: 100% 0%;
                -moz-transform-origin: 100% 0%;
                transform-origin: 100% 0%;
                height: 0;
            }

            @-webkit-keyframes conditionalOpen {
                from {
                    transform: scaleY(0);
                }

                to {
                    transform: scaleY(1);
                }
            }

            @-moz-keyframes conditionalOpen {
                from {
                    transform: scaleY(0);
                }

                to {
                    transform: scaleY(1);
                }
            }

            @keyframes conditionalOpen {
                from {
                    transform: scaleY(0);
                }

                to {
                    transform: scaleY(1);
                }
            }

            @-webkit-keyframes conditionalClose {
                from {
                    transform: scaleY(1);
                }

                to {
                    transform: scaleY(0);
                }
            }

            @-moz-keyframes conditionalClose {
                from {
                    transform: scaleY(1);
                }

                to {
                    transform: scaleY(0);
                }
            }

            @keyframes conditionalClose {
                from {
                    transform: scaleY(1);
                }

                to {
                    transform: scaleY(0);
                }
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <h1 class="mt-5 text-center">Student Report</h1>
        <button class="btn btn-info mb-3" onclick="getReport()">Clear Filter</button>
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
            <div class="col-lg-7 col-md-12 col-sm-12 align-items-center">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script> -->
    <script src="./report.js"></script>
    <script>
        // var buttonClick = function () {
        //     $(".monthsOpen, .monthsClose").toggleClass(
        //         "monthsOpen monthsClose"
        //     );
        // };

        // $("#monthsFilter").on("click", function () {
        //     $(".monthsOpen, .monthsClose").toggleClass(
        //         "monthsOpen monthsClose"
        //     );
        // });

    </script>
</body>

</html>