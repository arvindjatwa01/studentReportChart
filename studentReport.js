const allMonths = [
  "January",
  "February",
  "March",
  "April",
  "May",
  "June",
  "July",
  "August",
  "September",
  "October",
  "November",
  "December",
];

const requestObj = {
  allFilters: 0,
  monthFilter: 0,
  monthNum: null,
  subjectFilter: 0,
  subjectId: null,
};

const chartScalesX = (buMonth) => {
  return {
    beginAtZero: true,
    categoryPercentage: 0.8,
    barPercentage: 0.8,
    display: true,
    title: {
      display: true,
      text: buMonth ? "Subjects" : "Months",
      color: "#911",
      font: {
        // family: "Comic Sans MS",
        size: 18,
        weight: "bold",
        lineHeight: 1.2,
      },
      padding: { top: 10, left: 0, right: 0, bottom: 10 },
    },
  };
};

const chartScalesY = (byMonth) => {
  return {
    beginAtZero: true,
    max: 100, // Adjust this if your data range is different
    // ticks: {
    //   stepSize: 10,
    //   callback: function (value) {
    //     return value;
    //   },
    // },
    display: true,
    // title: {
    //   display: true,
    //   text: "Percentage",
    //   color: "#000000",
    //   font: {
    //     family: "Times",
    //     size: 10,
    //     style: "normal",
    //     lineHeight: 1.2,
    //     weight: "bold",
    //   },
    //   padding: { top: 20, left: 0, right: 0, bottom: 0 },
    // },
  };
};

const chartPlugins = {
  legend: {
    display: true,
    position: "bottom",
    labels: {
      boxWidth: 30,
      color: "black",
      font: {
        size: 10,
        weight: "bold",
      },
    },
  },
};

// Chart Vairables
let subjects = [];
let examMonths = [];

const urlParams = new URLSearchParams(window.location.search);
const userReportParamValue = urlParams.get("userReport");

// Check "userReport" Paramter exists || !== undefined
const handleUserReportParameterCheck = () => {
  if (!urlParams.has("userReport")) {
    return false;
  } else if (!userReportParamValue && userReportParamValue === undefined) {
    return false;
  }
  return true;
};

// error Message Sweet alert || Warning
const handleSwatWarning = () => {
  Swal.fire({
    icon: "warning",
    title: "Alert!",
    text: "Somthing Went wrong Or Report not found!",
    confirmButtonColor: "#FF7F50", // Customize confirm button color
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "searchUser.php";
    }
  });
};

let canvaTagId = $("#myChart");
let studentReportChart;

// get the student Report Chart
const getStudentReportChart = (response, noFilter, byMonth, bySubject) => {
  if (studentReportChart) {
    studentReportChart.destroy();
  }
  let chartLabels = [];
  if (byMonth) {
    chartLabels = subjects.map((sub) => sub.subjectName);
  } else {
    chartLabels = examMonths.map((month) => month.substring(0, 3)).slice(-6);
  }

  let datasets = [];
  const datasetsObj = handleGetChartDataSets(response, byMonth);
  if (noFilter) {
    datasets = Object.entries(datasetsObj).map(([subName, subjectData]) => {
      return {
        ...subjectData,
        data: subjectData["data"].slice(-6),
      };
    });
  } else {
    const bgColor = response.map((obj) => obj.bgColor);
    for (let key in datasetsObj) {
      datasets.push({
        label: key,
        data: [
          ...datasetsObj[key].data,
          datasetsObj[key] == datasetsObj[key].lebel
            ? datasetsObj[key].data
            : 0,
        ],
        backgroundColor: [...new Set(bgColor)],
        // barPercentage: 0.5,
        // barThickness: 6,
        // maxBarThickness: 8,
        // minBarLength: 2,
        borderRadius: "10px",
      });
    }
  }
  studentReportChart = new Chart(canvaTagId, {
    type: "bar",
    // data: getChartResponseData(response, noFilter, byMonth, bySubject),
    data: {
      labels: chartLabels,
      datasets: datasets,
    },
    options: {
      scales: {
        x: chartScalesX(byMonth),
        // y: chartScalesY(byMonth),
      },
      animation: {
        duration: 2000, // Animation duration in milliseconds
        easing: "easeInOutQuart", // Easing function for the animation
      },
      plugins: { ...chartPlugins },
    },
  });
};

// convert response data according to the dataset
const handleGetChartDataSets = (response, byMonth) => {
  let dataSetsObj = {};
  response.forEach((item) => {
    const monthName = item.month_name;
    const subjectName = item.subName;
    const avgPercentage = Number(item.average_percentage);
    if (!dataSetsObj[byMonth ? monthName : subjectName]) {
      dataSetsObj[byMonth ? monthName : subjectName] = {
        label: byMonth ? monthName : subjectName,
        data: [],
        backgroundColor: item.bgColor,
        borderColor: item.borderColor,
        // borderWidth: 1,
        // barThickness: 10,
        // type: !byMonth && subjectName === "English" ? "line": "bar",
      };
    }
    dataSetsObj[byMonth ? monthName : subjectName].data.push(avgPercentage);
  });
  return dataSetsObj;
};

// get Student Report Chart
const getStudentReport = () => {
  $.ajax({
    url: "ajaxReport.php",
    type: "GET",
    dataType: "json",
    data: {
      ...requestObj,
      allFilters: 1,
      userReport: userReportParamValue,
    },
    success: (response) => {
      if (response.apiSuccess === 0) handleSwatWarning();
      const result = response.responsePacket;

      $("#studentName").text(`${result[0].studentName} Report`);

      if (result.length !== 0) {
        // map exam Months and remvoe duplicate name
        const allExamMonths = Array.from(
          new Set(result.map((obj) => obj.month_name))
        );

        examMonths = [...allExamMonths];

        // set months name for month Wise Filter
        let monthsList = "";
        for (let monthName of allMonths) {
          if (examMonths.includes(monthName)) {
            monthsList =
              monthsList +
              `<div class="col-lg-2 col-md-3 col-4 d-flex justify-content-center align-items-center my-2"><button class="btn btn-primary mx-2 monthBtn" onclick="handleMonthFilterChart('${monthName}', event)">
              ${monthName.substr(0, 3)}</button></div>`;
          }
        }
        $("#months").html(monthsList);

        // map subjects names and remove duplcate name
        const allSubject = Array.from(
          new Set(
            result.map((obj) =>
              JSON.stringify({ subjectName: obj.subName, subjectId: obj.subId })
            )
          )
        ).map((str) => JSON.parse(str));

        subjects = [...allSubject];

        // set subjects name for subject wise filter
        let subjectName = "";
        for (let row of allSubject) {
          subjectName =
            subjectName +
            `<div class="col-lg-2 col-md-3 col-4 d-flex justify-content-center align-items-center my-2 mx-3"><button class="btn btn-primary" data-subjectid="${row.subjectId}" onclick="handleSubjectFilterChart(${row.subjectId}, event)">${row.subjectName}</button></div>`;
        }
        $("#subjects").html(subjectName);
      }

      getStudentReportChart(result, true, false, false);
    },
    error: () => {
      handleSwatWarning();
    },
  });
};

// Get Studen tchart Report Month wise Filter
const handleMonthFilterChart = (monthName, e) => {
  $("button").removeClass("btn-secondary"); // Remove highlight class from all buttons
  $(e.target).addClass("btn-secondary");
  $.ajax({
    url: "ajaxReport.php",
    type: "GET",
    dataType: "json",
    data: {
      ...requestObj,
      monthFilter: 1,
      monthNum: allMonths.indexOf(monthName) + 1,
      userReport: userReportParamValue,
    },
    success: (response) => {
      if (response.apiSuccess === 1) {
        const result = response.responsePacket;
        handleFilterAnimation("monthsFilter");
        getStudentReportChart(result, false, true, false);
      }
    },
    error: () => {},
  });
};

// Get Student chart Report Month wise Filter
const handleSubjectFilterChart = (subjectId, e) => {
  $("button").removeClass("btn-secondary");
  $(e.target).addClass("btn-secondary");
  $.ajax({
    url: "ajaxReport.php",
    type: "GET",
    dataType: "json",
    data: {
      ...requestObj,
      subjectFilter: 1,
      subjectId: Number(subjectId),
      userReport: userReportParamValue,
    },
    success: (response) => {
      if (response.apiSuccess === 1) {
        const result = response.responsePacket;
        handleFilterAnimation("subjectFilter");
        getStudentReportChart(result, false, false, true);
      }
    },
    error: () => {},
  });
};

// clear all filter
const handleClearAllFilters = () => {
  getStudentReport();
};

// show hide the Months name
const handleShowHideMonthsName = () => {
  $(".monthsOpen, .monthsClose").toggleClass("monthsOpen monthsClose");
  $(".subjectsOpen, .subjectsClose").removeClass("subjectsClose");
  $("#subjects").addClass("subjectsClose");

  $("#sujectFilterEye").addClass("fa-eye").removeClass("fa-eye-slash");

  if ($("#montheFilterEye").hasClass("fa-eye")) {
    $("#montheFilterEye").addClass("fa-eye-slash").removeClass("fa-eye");
  } else if ($("#montheFilterEye").hasClass("fa-eye-slash")) {
    $("#montheFilterEye").addClass("fa-eye").removeClass("fa-eye-slash");
  }
};

// Show hide Subjects name
const handleShowHideSubjects = () => {
  $(".subjectsOpen, .subjectsClose").toggleClass("subjectsOpen subjectsClose");
  $(".monthsOpen, .monthsClose").removeClass("monthsOpen");
  $("#months").addClass("monthsClose");

  $("#montheFilterEye").addClass("fa-eye").removeClass("fa-eye-slash");

  if ($("#sujectFilterEye").hasClass("fa-eye")) {
    $("#sujectFilterEye").addClass("fa-eye-slash").removeClass("fa-eye");
    $("#sujectFilterEye");
  } else if ($("#sujectFilterEye").hasClass("fa-eye-slash")) {
    $("#sujectFilterEye").addClass("fa-eye").removeClass("fa-eye-slash");
  }
};

// Filter animations
const handleFilterAnimation = (filterType = "byLabel") => {
  if (window.screen.width <= 768) {
    if (filterType === "byLabel") {
      $("#monthsFilter").on("click", handleShowHideMonthsName);
      $("#subjectsFilter").on("click", handleShowHideSubjects);
    } else if (filterType === "monthsFilter") {
      $(".monthsOpen, .monthsClose").toggleClass("monthsOpen monthsClose");
      $(".subjectsOpen, .subjectsClose").removeClass("subjectsClose");
      $("#subjects").addClass("subjectsClose");

      $("#sujectFilterEye").addClass("fa-eye").removeClass("fa-eye-slash");
      $("#montheFilterEye").addClass("fa-eye").removeClass("fa-eye-slash");
    } else if (filterType === "subjectFilter") {
      $(".subjectsOpen, .subjectsClose").toggleClass(
        "subjectsOpen subjectsClose"
      );
      $(".monthsOpen, .monthsClose").removeClass("monthsOpen");
      $("#months").addClass("monthsClose");
      $("#montheFilterEye").addClass("fa-eye").removeClass("fa-eye-slash");
      $("#sujectFilterEye").addClass("fa-eye").removeClass("fa-eye-slash");
    }
  }
};

$(document).ready(function () {
  getStudentReport();
  handleFilterAnimation();
});
