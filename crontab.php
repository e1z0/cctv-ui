<?php
require_once("config.php");

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


function getFolderSize($directory){
        $totalSize = 0;
        $directoryArray = scandir($directory);

        foreach($directoryArray as $key => $fileName){
            if($fileName != ".." && $fileName != "."){
                if(is_dir($directory . "/" . $fileName)){
                    $totalSize = $totalSize + getFolderSize($directory . "/" . $fileName);
                } else if(is_file($directory . "/". $fileName)){
                    $totalSize = $totalSize + filesize($directory. "/". $fileName);
                }
            }
        }
        return $totalSize;
    }


    function getFormattedSize($sizeInBytes) {

        if($sizeInBytes < 1024) {
            return $sizeInBytes . " bytes";
        } else if($sizeInBytes < 1024*1024) {
            return $sizeInBytes/1024 . " KB";
        } else if($sizeInBytes < 1024*1024*1024) {
            return round($sizeInBytes/(1024*1024),2) . " MB";
        } else if($sizeInBytes < 1024*1024*1024*1024) {
            return round($sizeInBytes/(1024*1024*1024),2) . " GB";
        } else if($sizeInBytes < 1024*1024*1024*1024*1024) {
            return round($sizeInBytes/(1024*1024*1024*1024),2) . " TB";
        } else {
            return "Greater than 1024 TB";
        }

    }

function ReturnActivityLog() {
global $conn, $redis, $FILE_PATH;
//$sql_min = "SELECT min(timestamp) as min,max(timestamp)as max FROM `events` limit 1";
//$curr = $conn->query($sql_min)->fetch_assoc();
$sql = "SELECT DATE(timestamp) as data,count(id) as kiekis,object FROM `events` group by DATE(timestamp), object";
if (!$result = $conn->query($sql)) {
error_log("Error description: " . $conn -> error,0);
}
$cars = array();
$persons = array();
$trucks = array();
$count = 0;
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
  if ($row["object"] == "car") $cars[$row["data"]] = $row["kiekis"];
  if ($row["object"] == "person") $persons[$row["data"]] = $row["kiekis"];
  if ($row["object"] == "truck") $trucks[$row["data"]] = $row["kiekis"];
  $count++;
  }
}
reset($persons);
$start = new DateTime(key($persons));
end($persons);
$end   = new DateTime(key($persons));

// get start and end out of array
foreach (new DatePeriod($start, new DateInterval('P1D'), $end) as $date) {
    $dateKey = $date->format('Y-m-d'); // get properly formatted date out of DateTime object
    if (!isset($cars[$dateKey])) {
        $cars[$dateKey] = 0;
    }
}
ksort($cars);
$redis->hset("cameras_activity_graph","cars",json_encode($cars));
// get start and end out of array
foreach (new DatePeriod($start, new DateInterval('P1D'), $end) as $date) {
    $dateKey = $date->format('Y-m-d'); // get properly formatted date out of DateTime object
    if (!isset($persons[$dateKey])) {
        $personss[$dateKey] = 0;
    }
}
ksort($persons);
$redis->hset("cameras_activity_graph","persons",json_encode($persons));
// get start and end out of array
foreach (new DatePeriod($start, new DateInterval('P1D'), $end) as $date) {
    $dateKey = $date->format('Y-m-d'); // get properly formatted date out of DateTime object
    if (!isset($trucks[$dateKey])) {
        $trucks[$dateKey] = 0;
    }
}
ksort($trucks);
$redis->hset("cameras_activity_graph","trucks",json_encode($trucks));
}

if (isset($argv[1])) {
if ($argv[1] == "--regular") {
$sizeInBytes = getFolderSize($FILE_PATH);
$redis->set("cameras_consumption",getFormattedSize($sizeInBytes));
ReturnActivityLog();
echo "ok";
exit(0);
}

if ($argv[1] == "--hour") {
$redis->hset("cameras_events_stats","per_hour",0);
exit(0);
}

if ($argv[1] == "--day") {
$redis->hset("cameras_events_stats","per_day",0);
exit(0);
}


if ($argv[1] == "--week") {
$redis->hset("cameras_events_stats","per_week",0);
exit(0);
}

if ($argv[1] == "--month") {
$redis->hset("cameras_events_stats","per_month",0);
exit(0);
}


if ($argv[1] == "--year") {
$redis->hset("cameras_events_stats","per_year",0);
exit(0);
}

} else {
echo "No parameters at all, are you dumb?\n";
}

echo "No parameters\n";
?>
