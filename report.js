var urlParams = new URLSearchParams(window.location.search);
var param1Value = urlParams.get("userReport");

// Check parameters exist or not
const checkParameters = () => {
  if (!urlParams.has("userReport")) {
    return false;
  } else if (
    param1Value === "" ||
    param1Value === null ||
    param1Value === undefined
  ) {
    return false;
  }
  return true;
};

const months = [];
const subjects = [];
const filterMonths = [];
const monthNames = [
  "Jan",
  "Feb",
  "Mar",
  "Apr",
  "May",
  "Jun",
  "Jul",
  "Aug",
  "Sep",
  "Oct",
  "Nov",
  "Dec",
];

var ctx = $("#myChart");
var myChart;

// get Report Chart
const getReportChart = (
  monthByFilter,
  months,
  convertedData,
  allFilters = true,
  data
) => {
  if (myChart != undefined) {
    myChart.destroy();
  }

  myChart = new Chart(ctx, {
    // type: !allFilters && !monthByFilter ? "line" : "bar",
    type: "bar",
    data: getChartData(months, convertedData, allFilters, data),
    options: {
      scales: {
        x: {
          beginAtZero: true,
          categoryPercentage: 0.8,
          barPercentage: 0.8,
          display: true,
          title: {
            display: true,
            text: !allFilters && monthByFilter ? "Subjects": "Months",
            color: "#911",
            font: {
              family: "Comic Sans MS",
              size: 20,
              weight: "bold",
              lineHeight: 1.2,
            },
            padding: { top: 20, left: 0, right: 0, bottom: 0 },
          },
        },
        y: {
          type: "linear",
          beginAtZero: true,
          max: 100, // Adjust this if your data range is different
          ticks: {
            stepSize: 10,
            callback: function (value) {
              return value;
            },
          },
          display: true,
          title: {
            display: true,
            text: "Percentage",
            color: "#000000",
            font: {
              family: "Times",
              size: 20,
              style: "normal",
              lineHeight: 1.2,
              weight: "bold",
            },
            padding: { top: 20, left: 0, right: 0, bottom: 0 },
          },
        },
      },
      // plugins: {
      //   legend: {
      //     display: true,
      //     position: "bottom",
      //     labels: {
      //       boxWidth: 50,
      //       color: "black",
      //       font: {
      //         size: 24,
      //         weight: "bold",
      //       },
      //     },
      //   },
      // },
    },
  });
};

// get Chart according to Month|Subject Name Wise Filter
const handleGetChartFilter = (e, monthFilter) => {
  $("button").removeClass("btn-secondary"); // Remove highlight class from all buttons
  $(e.target).addClass("btn-secondary"); // Add highlight class to the clicked button

  let subjectId = null;
  if (!monthFilter) {
    subjectId = $(e.currentTarget).data("subjectid");
  }

  const monthIndex = monthNames.indexOf(e.target.innerText);
  $.ajax({
    url: "ajaxReport.php",
    type: "GET",
    dataType: "JSON",
    data: {
      allFilters: 0,
      monthFilter: monthFilter ? 1 : 0,
      monthNum: monthFilter ? monthIndex + 1 : null,
      subjectFilter: !monthFilter ? 1 : 0,
      subjectId: subjectId,
      // subjectName: monthFilter ? null : e.target.innerText,
      userReport: checkParameters() ? param1Value : "",
    },
    success: function (data) {
      if (data.apiSuccess === 0) {
        Swal.fire({
          icon: "alert",
          title: "Alert!",
          text: "Somthing Went wrong Or Report not found!",
          confirmButtonColor: "#FF7F50", // Customize confirm button color
        }).then((result) => {
          if (result.isConfirmed) {
            // Navigate to another page
            window.location.href = "searchUser.php";
          }
        });
        return;
      }
      const convertedData = getConvertedData(
        data.responsePacket,
        monthFilter,
        !monthFilter
      );
      getReportChart(
        monthFilter,
        monthFilter ? subjects.map((sub) => sub.subjectName) : months,
        convertedData,
        false,
        data.responsePacket
      );
    },
    error: function (error) {
      console.log("error : ", error);
    },
  });
};

// convert data according to the filter
const getConvertedData = (
  inputData,
  monthFilter = false,
  subjectFilter = false
) => {
  let subresult = {};
  inputData.forEach((item) => {
    const month = item.month_name.toLowerCase(); // Convert month name to lowercase
    const subject = item.subName;

    if (!monthFilter && !subjectFilter) {
      // Chart show names
      if (!months.includes(item.month_name.substr(0, 3))) {
        months.push(item.month_name.substr(0, 3));
      }

      // filter screen Months names
      if (!filterMonths.includes(item.month_name)) {
        filterMonths.push(item.month_name);
      }

      // Subject name
      if (!subjects.some((obj) => obj.subjectId === item.subId)) {
        subjects.push({
          subjectId: item.subId,
          subjectName: subject,
        });
      }
    }

    if (
      !subresult[
        monthFilter ? item.month_name : subjectFilter ? subject : subject
      ]
    ) {
      subresult[
        monthFilter ? item.month_name : subjectFilter ? subject : subject
      ] = {
        label: monthFilter
          ? item.month_name
          : subjectFilter
          ? item.subName
          : item.subName,
        data: [],
        backgroundColor: item.bgColor,
        borderColor: item.borderColor,
        borderWidth: 1,
        barThickness: 10,
        // type: !monthFilter && item.subName === "English" ? "line": "bar",
      };
    }

    subresult[
      monthFilter ? item.month_name : subjectFilter ? subject : subject
    ].data.push(Number(item.average_percentage));
  });
  return subresult;
};

// get the chart props data
const getChartData = (xAxisLabels, responseData = {}, allFilters, response) => {
  let datasetsArr = [];
  if (allFilters) {
    datasetsArr = Object.entries(responseData).map(([subName, subjectData]) => {
      return { ...subjectData };
    });
  } else {
    const bgColor = [];
    for (let obj of response) {
      if (!bgColor.includes(obj.bgColor)) {
        bgColor.push(obj.bgColor);
      }
    }

    for (let key in responseData) {
      console.log("key ---- ", key);
      datasetsArr.push({
        label: key,
        data: [
          ...responseData[key].data,
          responseData[key] == responseData[key].lebel
            ? responseData[key].data
            : 0,
        ],
        backgroundColor: [...bgColor],
      });
    }
  }
  return {
    labels: xAxisLabels,
    datasets: datasetsArr,
  };
};

const getReport = () => {
  $.ajax({
    url: "ajaxReport.php",
    type: "GET",
    dataType: "json",
    data: {
      allFilters: 1,
      monthFilter: 0,
      monthNum: null,
      subjectFilter: 0,
      subjectId: null,
      userReport: checkParameters() ? param1Value : "",
    },
    success: function (data) {
      if (data.apiSuccess === 0) {
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
        return;
      }

      const convertedData = getConvertedData(data.responsePacket);

      var monthName = "";
      // for (let month of months) {
      for (let month of monthNames) {
        monthName =
          monthName +
          `<div class="col-lg-2 col-md-3 col-sm-3 d-flex justify-content-center align-items-center my-2"><button class="btn btn-primary mx-2 monthBtn" onclick="handleGetChartFilter(event, true)">${month}</button></div>`;
      }
      $("#months").html(monthName);

      var subjectName = "";
      for (let row of subjects) {
        subjectName =
          subjectName +
          `<div class="col-lg-2 col-md-3 col-sm-3 d-flex justify-content-center align-items-center my-2 mx-3"><button class="btn btn-primary" data-subjectid="${row.subjectId}" onclick="handleGetChartFilter(event, false)">${row.subjectName}</button></div>`;
      }
      $("#subjects").html(subjectName);
      getReportChart(false, months, convertedData);
    },
    error: function (error) {
      console.error("Error:", error);
    },
  });
};

$(document).ready(function () {
  if (!checkParameters()) {
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
    return;
  }
  getReport();
});

// **************** Search Mobile number nad go to the report page **************** //

$("#searchBtn").on("click", function (e) {
  const mobileNo = $("#searchInput").val();
  if (!mobileNo || mobileNo === "" || mobileNo.length === 0) {
    $("#searchInputErr").text("Please enter mobile number");
    $("#searchInputErr").css("color", "red");
  } else if (!/^[0-9]{10}$/.test(mobileNo)) {
    $("#searchInputErr").text("Please enter valid mobile number");
    $("#searchInputErr").css("color", "red");
  } else {
    $("#searchInputErr").text("");
    $.ajax({
      url: "query.php",
      type: "GET",
      dataType: "JSON",
      data: { searchInput: 1, mobileNo: mobileNo },
      success: function (data) {
        if (data.userid !== 0) {
          console.log(data);
          // var encodedData = encodeURIComponent(data.userid);
          var url = `index.php?userReport=${data.useridencode}`;

          // Navigate to the other PHP page
          window.location.href = url;
        } else {
          Swal.fire({
            icon: "info",
            title: "Stuent Report not found!",
            text: "",
          });
        }
        console.log("query.php", data);
      },
      error: function (error) {
        console.log("error: " + error);
      },
    });
  }
});
