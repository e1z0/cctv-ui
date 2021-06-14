<?php
require_once("config.php");
$search_enabled = $_GET["search"];
$type = $_GET["type"];
$date1 = urldecode($_GET["date1"]);
$date2 = urldecode($_GET["date2"]);
$page = $_GET["page"];

if ($type == 1) {
$where ="";
$limit = "limit $page,50";
$order = "order by id desc";
if ($search_enabled > 0) {
$where = "where time_start between '$date1' and '$date2'";
$order = "order by id";
$limit = "limit $page,50";
}

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
$sql = "SELECT id, name, filename, frame,time_start, timediff(time_end, time_start) as laikas FROM security $where $order $limit";
if (!$result = $conn->query($sql)) {
error_log("Error description: " . $mysqli -> error,0);
}
if ($result->num_rows > 0) {
        $string ='';
    while($row = $result->fetch_assoc()) {
    $pradzia = $row["time_start"];
    $time1 = $row["laikas"];
    if (strpos($time1,"-") !== false) {
    $time1 = "unknwon";
    continue; // we skip this file because it's incomplete!
    }
    $new = "";
    $laikas = $row["frame"]." / ".$time1;
    $previewed = 0;
    if ($redis->hexists("previewed_records",$row["id"])) {
    $previewed = $redis->hget("previewed_records", $row["id"]);
    }
    $prew_html = "";
    if ($previewed > 0) {
    $prew_html = " Viewed $previewed times";
    }
    $fav = "";
    if (!$redis->hexists("records_fav",$row["id"])) {
    $fav = "<div class='fav glyphicon glyphicon-plus cursor-pointer' data-id='".$row["id"]."' type='record'></div> ";
    }
    echo "<tr><th scope=\"row\">$fav<a id=\"OpenDialog\" class=\"preview\" data-id=\"".$row["id"]."\" href=\"javascript:void(0)\" video=\"".$row["filename"]."\">".$pradzia." $new</a><br><small id='view".$row["id"]."' counter='".$previewed."'><font color='grey'>$prew_html</font></small></th><td>" . $row["name"]. "</td><td>" . $laikas. "</td><td></tr>";
    }
    echo($string);
}
}
if ($type == 0) {
$where ="";
$limit = "limit $page,50";
$order = "order by id desc";
if ($search_enabled > 0) {
$where = "where timestamp between '$date1' and '$date2'";
$order = "order by id";
$limit = "limit $page,50";
}

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
$sql = "SELECT id, object, camera, filename, timestamp FROM events $where $order $limit";
if (!$result = $conn->query($sql)) {
error_log("Error description: " . $mysqli -> error,0);
}
if ($result->num_rows > 0) {
        $string ='';
    while($row = $result->fetch_assoc()) {
    $new = "";
  $previewed = 0;
    if ($redis->hexists("previewed_events",$row["id"])) {
    $previewed = $redis->hget("previewed_events", $row["id"]);
    }
    $prew_html = "";
    if ($previewed > 0) {
    $prew_html = "Viewed $previewed times";
    }
    $fav = "";
    if (!$redis->hexists("events_fav",$row["id"])) {
    $fav = "<div class='fav glyphicon glyphicon-plus cursor-pointer' data-id='".$row["id"]."' type='event'></div> ";
    }
    echo "<tr><th scope=\"row\">$fav<a id=\"OpenDialogPic\" data-id=\"".$row["id"]."\" ".
    "class=\"preview\" href=\"javascript:void(0)\" image=\"".$row["filename"]."\">" . 
    $row["timestamp"]. " $new</a>".
    "<br><small id='view".$row["id"]."' counter='".$previewed."'><font color='grey'>".$prew_html."</font></small>".
    "</th><td>" . $row["camera"]. "</td><td>".$row["object"]. "</td></tr>";
    }
    echo($string);
}

}
?>
