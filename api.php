<?php
require_once("config.php");

if (isset($_GET["stats"])) {
$stats=new stdClass();
$stats->per_hour = $redis->hget("cameras_events_stats","per_hour");
$stats->per_day = $redis->hget("cameras_events_stats","per_day");
$stats->per_week = $redis->hget("cameras_events_stats","per_week");
$stats->per_month = $redis->hget("cameras_events_stats","per_month");
$stats->per_year = $redis->hget("cameras_events_stats","per_year");
$newestid = $redis->get("cameras_events_last_id");
$insertedid = $redis->get("cameras_last_insertid");
$stats->new_events = ($insertedid - $newestid); // kuriu nemate niekas
$stats->disk_usage = $redis->get("cameras_consumption");
header('Content-type: application/json');
echo json_encode($stats);
}

/// image data for image preview
if (isset($_GET["getimgdata"])&&$_GET["getimgdata"] > 0) {
$stats=new stdClass();
$stats->comment = "";
$idas = (int)$_GET["getimgdata"];
if ($idas > 0) {
$redis->hincrby("previewed_events",$idas,1);
}
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
$sql_curr = "SELECT id, object,camera,filename,timestamp from events where id = $idas LIMIT 1";
$curr = $conn->query($sql_curr)->fetch_assoc();

// next
$sql_next = "SELECT id, object,camera,filename,timestamp from events where id > $idas ORDER BY ID ASC LIMIT 1";
$next = $conn->query($sql_next)->fetch_assoc();
// previous
$sql_prev = "SELECT id, object,camera,filename,timestamp from events where id < $idas ORDER BY ID DESC LIMIT 1";
$prev = $conn->query($sql_prev)->fetch_assoc();

$stats->imgur = $curr["filename"];
$stats->camera = $curr["camera"];
$stats->object = $curr["object"];
$stats->time = $curr["timestamp"];
$stats->next_id = $next["id"];
$stats->previous_id = $prev["id"];
if ($redis->hexists("events_fav",$idas)) {
$stats->comment = $redis->hget("events_fav",$idas);
}
header('Content-type: application/json');
echo json_encode($stats);
}
/// movie data for movie preview
if (isset($_GET["getmoviedata"])&&$_GET["getmoviedata"] > 0) {
$stats=new stdClass();
$stats->comment = "";
$idas = (int)$_GET["getmoviedata"];
if ($idas > 0) {
$redis->hincrby("previewed_records",$idas,1);
}

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
$sql_curr = "SELECT id, name, filename, time_start from security where id = $idas LIMIT 1";
$curr = $conn->query($sql_curr)->fetch_assoc();

// next
$sql_next = "SELECT id, name, filename, time_start from security where id > $idas ORDER BY ID ASC LIMIT 1";
$next = $conn->query($sql_next)->fetch_assoc();
// previous
$sql_prev = "SELECT id, name, filename, time_start from security where id < $idas ORDER BY ID DESC LIMIT 1";
$prev = $conn->query($sql_prev)->fetch_assoc();
$stats->videourl = $curr["filename"];
$stats->camera = $curr["name"];
$stats->time = $curr["time_start"];
$stats->next_id = $next["id"];
$stats->previous_id = $prev["id"];
if ($redis->hexists("records_fav",$idas)) {
$stats->comment = $redis->hget("records_fav",$idas);
}

header('Content-type: application/json');
echo json_encode($stats);
}
// favorite
if (isset($_GET["addfave"])&&$_GET["addfave"] > 0) {
$idas = $_GET["addfave"];
$type = $_GET["type"];
$data = $_POST["data"];
if ($type == "event") {
$redis->hset("events_fav",$idas,$data);
// sql insert
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
$sql = "INSERT INTO favs (nr,type,comment) VALUES('$idas','$type','$data')";
$conn->query($sql);
}
if ($type == "record") {
$redis->hset("records_fav",$idas,$data);
// sql insert
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
$sql = "INSERT INTO favs (nr,type,comment) VALUES('$idas','$type','$data')";
$conn->query($sql);
}

}

?>
