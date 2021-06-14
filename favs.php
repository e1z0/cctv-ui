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
<h1>Favorites</h1>
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
      <th scope="col">Comment</th>
    </tr>
  </thead>
  <tbody id="recordai">
<?php
$where ="";
$limit = "limit 50";
$order = "order by id";
if ($post > 0) {
$where = "where time_created between '$search_begin' and '$search_end'";
$order = "order by id";
$limit = "limit 50";
}
$sql = "SELECT id, nr, type, comment, time_created FROM favs $where $order $limit";
error_log("SQL: ".$sql);
if (!$result = $conn->query($sql)) {
error_log("Error description: " . $mysqli -> error,0);
}
$current_id = 0;
if ($result->num_rows > 0) {
  if ($post > 0) {
   echo "Found: $result->num_rows";
   }
  // output data of each row
  while($row = $result->fetch_assoc()) {
    if ($row["type"] == "record") {
    // record
    $glyph = "<div class='fav glyphicon glyphicon-film cursor-pointer'></div> ";
    echo '<tr><th scope="row">'.$glyph.'<a id="OpenDialog" class="preview" data-id="'.$row["nr"].'" href="javascript:void(0)" video="">'.$row["time_created"].'</a></th><td>'.$row["comment"].'</td></tr>';
    } else {
    // event
     $glyph = "<div class='fav glyphicon glyphicon-picture cursor-pointer'></div>";
     echo "<tr><th scope=\"row\">".$glyph."<a id=\"OpenDialogPic\" data-id=\"".$row["nr"]."\" "."class=\"preview\" href=\"javascript:void(0)\" image=\"\">".$row["time_created"]."</a>" . 
    "</th><td>" . $row["comment"]. "</td></tr>";
    
    }
  }
} else {
  echo "0 results";
}
$conn->close();
?>
 </tbody>
</table></div><br>
                            <div class="loaderis" id="puslapis_load" style="display:none; position: absolute; z-index: 99999; width: 90%"></div><br><br><br>
<?php
require_once("footer.php");
?>


