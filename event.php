<?php
require_once("config.php");
$file = $_GET["file"];
// security in mind
if(!preg_match("/".fix_slashes($FILE_PATH)."\/Events\/(.*)\/(.*).jpg/",$file)) {
error_log("No supported file ! $file", 0);
return;
}
header('Content-Type: image/jpeg');
readfile($file);
