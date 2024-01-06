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


    <style>
        #chart-container {
            max-width: 600px;
            margin: 20px auto;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1 class="mt-5 text-center">Monthly Student Marks</h1>
        <div class="d-grid flex-column justify-content-center align-items-center text-center">

            <div class="d-flex justify-content-between align-items-center">
                <div id="months"></div>
            </div>
            <h4 class="my-3">OR</h4>
            <div class="d-flex justify-content-center align-items-center text-center">
                <div id="subjects"></div>
            </div>
        </div>
        <div id="chart-container">
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const months = [];
        const subjects = [];
        const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        var ctx = $('#myChart');
        var myChart;
        const getStudentReportChart = (months, convertedData, allFilters = true) => {
            if (myChart != undefined) {
                myChart.destroy();
            }

            myChart = new Chart(ctx, {
                type: 'bar',
                data: getChartData(months, convertedData, allFilters),
                // {
                //     labels: months,
                //     datasets: Object.entries(convertedData).map(([subName, subjectData]) => {
                //         return { ...subjectData }
                //     })
                // },
                options: {
                    scales: {
                        x: {
                            beginAtZero: true,
                            categoryPercentage: 0.8,  // Adjust the width of the bars on the x-axis
                            barPercentage: 0.8        // Adjust the width of the bars on the x-axis
                        },
                        y: {
                            type: 'linear',
                            beginAtZero: true,
                            max: 100, // Adjust this if your data range is different
                            ticks: {
                                stepSize: 5,
                                callback: function (value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        }
        const handleMonthFilter = (e) => {
            const monthIndex = monthNames.indexOf(e.target.innerText);
            $.ajax({
                url: "ajaxReport.php",
                type: "GET",
                dataType: "JSON",
                data: {
                    allFilters: false,
                    monthFilter: true,
                    monthNum: monthIndex + 1,
                    subjectFilter: false,
                    subjectName: null,
                },
                success: function (data) {
                    const convertedData = getConvertedData(data, true, false);
                    getStudentReportChart(subjects, convertedData, false)
                }, error: function (error) {
                    console.log("error : ", error);
                }
            })
        }

        const getConvertedData = (inputData, monthFilter = false, subjectFilter = false) => {
            const result = {};
            let subresult = {};

            inputData.forEach((item) => {
                const month = item.month_name.toLowerCase(); // Convert month name to lowercase
                const subject = item.subName;

                if (!monthFilter && !subjectFilter) {
                    if (!months.includes(item.month_name.substr(0, 3))) {
                        months.push(item.month_name.substr(0, 3));
                    }

                    if (!subjects.includes(subject)) {
                        subjects.push(subject);
                    }
                }

                if (!subresult[subject]) {
                    subresult[subject] = {
                        label: item.subName,
                        data: [],
                        backgroundColor: item.bgColor,
                        borderColor: item.borderColor,
                        borderWidth: 1,
                        barThickness: 10
                    };
                }
                subresult[subject].data.push(Number(item.average_percentage));
                // if (!monthFilter && !subjectFilter) {
                // } else {
                //     if (!subresult[subject]) {
                //         subresult[subject] = {
                //             label: item.subName,
                //             data: Number(item.average_percentage),
                //             backgroundColor: item.bgColor,
                //             borderColor: item.borderColor,
                //             borderWidth: 1,
                //             barThickness: 10
                //         };
                //     }
                // }
            });
            return subresult;
        };

        const getChartData = (xAxisLabels, responseData = {}, allFilters) => {
            let datasetsArr = [];
            if (allFilters) {
                datasetsArr = Object.entries(responseData).map(([subName, subjectData]) => {
                    return { ...subjectData }
                })
            } else {
                console.log(" :: ", responseData);
                Object.entries(responseData).map(([sub,data]) => {
                    
                })
                for (let key in responseData) {
                    datasetsArr.push({
                        label: key,
                        data: [...responseData[key].data, responseData[key] == responseData[key].lebel ? responseData[key].data : 0]
                    })
                    // datasetsArr.push({
                    //     label: key,
                    //     data: [...responseData[key].data]
                    // })
                }
                console.log("datasetsArr :: ", datasetsArr)
                // const data1 = Object.entries(responseData).map(([subName, subjectData]) => {
                //     console.log(responseData[subName])
                //     console.log("subjectData :: ", subjectData)
                //     console.log("responseData :: ", responseData)
                //     // console.log("subName :: ",subName)
                //     if (subName === "average_percentage") {
                //         // console.log("------- ", responseData["average_percentage"])
                //         // datasetsArr.push(subjectData)
                //     }
                //     datasetsArr.push({
                //         label: subjectData.label,
                //         data: [...responseData[subName].data]
                //     })
                //     // return [...subjectData[subName]]
                // });

                // console.log("responseData :: ", responseData);
                console.log("data1 :: ", datasetsArr);
                // console.log("xAxisLabels :: ", xAxisLabels);
            }
            return {
                labels: xAxisLabels,
                //     datasets: [{
                //         label: 'Dataset 1',
                //         data: [10, 20, 30],
                //         backgroundColor: ['red', 'green', 'blue'],
                //     },
                //     {
                //         label: 'Dataset 2',
                //         data: [90, 40, 50],
                //         backgroundColor: ['red', 'green', 'blue'],
                //     }
                // ]
                // labels: xAxisLabels,
                datasets: datasetsArr,
                // datasets: Object.entries(responseData).map(([subName, subjectData]) => {
                //     return { ...subjectData }
                // })
            }
        }

        const getStudentReport = () => {
            $.ajax({
                url: "ajaxReport.php",
                type: 'GET',
                dataType: 'json',
                success: function (data) {

                    var monthNameList = {};
                    const convertedData = getConvertedData(data);

                    var monthName = "";
                    for (let month of months) {
                        monthName = monthName + `<button class="btn btn-primary mx-2 monthBtn" onclick="handleMonthFilter(event)">${month}</button>`
                    }
                    $("#months").html(monthName);

                    var subjectName = "";
                    for (let subject of subjects) {
                        subjectName = subjectName + `<button class="btn btn-primary mx-2">${subject}</button>`
                    }
                    $("#subjects").html(subjectName);
                    getStudentReportChart(months, convertedData)
                },
                error: function (error) {
                    console.error('Error:', error);
                }
            });
        }

        $(document).ready(function () {
            getStudentReport()
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

</body>

</html>