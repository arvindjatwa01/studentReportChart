<?php
$db = mysqli_connect('localhost', 'root', '', 'ReportDB');
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    die;
}
// Assuming you have already established a MySQL connection

function enc_dec($str, $type = "enc", $time = '')
{
    $key = 'crypt';
    if ($type == 'enc') {
        $encrypted = base64_encode(time() . "|" . $str);
        return $encrypted;
    } else {
        $de = base64_decode($str);
        $dec = explode("|", $de);
        if (empty($time)) {
            if(isset($dec[1])) {
                return $dec[1];
            }
            return 0;
        } else {
            return array($dec[0], $dec[1]);
        }
    }
}

$one_year_ago = date('Y-m-d', strtotime('-12 months'));
$userid = isset($_GET['userReport']) ? $_GET['userReport'] : 0;
$userid = enc_dec($userid, 'dec', '');
$allFilters = isset($_GET['allFilters']) ? $_GET['allFilters'] : 1;
$monthFilter = isset($_GET['monthFilter']) ? $_GET['monthFilter'] : 0;
$monthNum = isset($_GET['monthNum']) ? $_GET['monthNum'] : null;
$subjectFilter = isset($_GET['subjectFilter']) ? $_GET['subjectFilter'] : 0;
$subjectName = isset($_GET['subjectName']) ? $_GET['subjectName'] : null;

$query = "SELECT 
    s.subName,
    s.bgColor,
    s.borderColor,
    MONTH(r.reportDate) AS report_month,
    MONTHNAME(r.reportDate) AS month_name,
    AVG(r.totalPer) AS average_percentage
FROM 
    subject s
 JOIN 
    studentReportChart r ON s.subId = r.subId
WHERE r.dlb_u_id =$userid and 
    r.reportDate >= '$one_year_ago'";

// GROUP BY 
// report_month, s.subId order by reportDate asc";

if ($allFilters > 0) {
    $query .= " GROUP BY 
    report_month, s.subName order by reportDate asc";

} else if ($monthFilter > 0) {
    // $query .= " GROUP BY 
    // report_month, month_name order by reportDate asc";
    $query = "SELECT 
    s.subName,
    s.bgColor,
    s.borderColor,
        MONTH(r.reportDate) AS report_month,
    MONTHNAME(r.reportDate) AS month_name,
    AVG(r.totalPer) AS average_percentage
FROM 
    subject s
JOIN 
    studentReportChart r ON s.subId = r.subId where r.dlb_u_id =$userid AND MONTH(r.reportDate) = $monthNum AND r.reportDate >= '$one_year_ago' GROUP by s.subName
order by reportDate asc";

} else if ($subjectFilter > 0) {
    // $query .= " GROUP BY 
    // report_month, month_name order by reportDate asc";
    $query = "SELECT 
    s.subName,
    s.bgColor,
    s.borderColor,
        MONTH(r.reportDate) AS report_month,
    MONTHNAME(r.reportDate) AS month_name,
    AVG(r.totalPer) AS average_percentage
FROM 
    subject s
JOIN 
    studentReportChart r ON s.subId = r.subId where r.dlb_u_id =$userid AND s.subName = '$subjectName' AND r.reportDate >= '$one_year_ago' GROUP by report_month
order by reportDate asc";

}
// report_month,  order by reportDate asc";

$result = mysqli_query($db, $query);

// Fetch data from the result set
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
if (empty($data)) {
    $apiSuccess = 0;
} else {
    $apiSuccess = 1;
}


// Return the data as JSON
header('Content-Type: application/json');
echo json_encode(array("apiSuccess" => $apiSuccess,"responsePacket"=> $data));
?>