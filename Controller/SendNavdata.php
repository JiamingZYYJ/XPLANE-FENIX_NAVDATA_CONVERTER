<?php
header("Content-Type: application/json");
require_once '../core/Transfer.core.php';
if (isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
    $file = $GLOBALS['HTTP_RAW_POST_DATA'];
} else {
    $file = file_get_contents('php://input');
}
$waypoint=$_REQUEST['Wpt'];
$startpoint=$_REQUEST['startpoint'];
$airport=$_REQUEST['airport'];
$user=$_REQUEST['username'];

$obj=new Transfers();
$obj->source=$file;
$obj->waypoint=$waypoint;
$obj->insertPoint=$startpoint;
$obj->airport=$airport;
$obj->user=$user;

echo $obj->Export();