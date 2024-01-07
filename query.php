<?php
$db = mysqli_connect('localhost', 'root', '', 'ReportDB');
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    die;
}

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
            return $dec[1];
        } else {
            return array($dec[0], $dec[1]);
        }
    }
}


if (isset($_GET['searchInput']) and $_GET['searchInput'] !== "") {
    $query = "SELECT * FROM wifi_users where dlb_u_phone = '" . $_GET['mobileNo'] . "' LIMIT 1";
    $result = mysqli_query($db, $query);
    // Fetch data from the result set
    $data = array();
    $userid = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $userid = $row["dlb_u_id"];
    }
    $data["userid"] = $userid;
    $data["useridencode"] = enc_dec($userid, "enc", "");

    // Return the data as JSON
    header('Content-Type: application/json');
    echo json_encode($data);
    // echo json_encode([$searchInput);

} else {
    echo json_encode("No search input");
}
// if($searchInput != undefined)

?>