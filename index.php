<?php
require_once("config.php");
require_once("header.php");

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
<center>
<h1>New Events</h1>
</center>
<?php require_once("navbar.php"); ?>
<?php
$post = 0;
$search_begin = "";
$search_end = "";
if($_SERVER['REQUEST_METHOD'] == 'POST') {
$post = 1;
$search_begin = $_POST["date1"];
$search_end = $_POST["date2"];
echo "<h3>Displaying results between: $search_begin and $search_end</h3>";
}
?>
<div id="data">
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Time</th>
      <th scope="col">Camera</th>
      <th scope="col">Object</th>
    </tr>
  </thead>
  <tbody id="recordai">

<?php
$where ="";
$limit = "limit 50";
$order = "order by id desc";
if ($post > 0) {
$where = "where timestamp between '$search_begin' and '$search_end'";
$order = "order by id";
$limit = "limit 50";
}
$sql = "SELECT id, object, camera, filename, timestamp FROM events $where $order $limit";
if (!$result = $conn->query($sql)) {
error_log("Error description: " . $mysqli -> error,0);
}
$current_id = 0;
$last_id = $redis->get("cameras_events_last_id");
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    if ($row["id"] > $current_id) $current_id = $row["id"];
    $new = "";
    if ($post == 0) {
    if ($row["id"] > $last_id) $new = "<font color='red'>NEW!</font>";
    }
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
} else {
  echo "0 results";
}
$conn->close();
if ($post == 0) {
$redis->set("cameras_events_last_id",$current_id);
}
?>
</tbody>
</table></div>
<br>
<div class="loaderis" id="puslapis_load" style="display:none; position: absolute; z-index: 99999; width: 90%"></div><br><br><br>
<script>
$(window).bind('scroll', function() {
    if($(window).scrollTop() >= $('#data').offset().top + $('#data').outerHeight() - window.innerHeight) {
                                        datatype = "html";
                                        url = "showmore.php?page=" + jQuery('#recordai tr').length+"&type=0&search=<?=$post?>&date1=<?=urlencode($search_begin)?>&date2=<?=urlencode($search_end)?>";
                                        jQuery('#puslapis_load').show();
                                        jQuery.ajax
                                        ({
                                            type: "GET",
                                            url: url,
                                            dataType: datatype,
                                            success: function (html) {
                                              jQuery('#recordai').append(html);
                                            },
                                            error: function () {
                                                console.log("request no. 1 have failed " + url);
                                            }
                                        }).then(function () {
                                            jQuery('#puslapis_load').hide();
                                        });


                                    } // end of
                                });
</script>
<?php
require_once("footer.php");
?>
