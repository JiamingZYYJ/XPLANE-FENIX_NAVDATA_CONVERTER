<?php
header("Content-Type: application/json");
require_once '../core/Transfer.core.php';

$user=$_REQUEST['username'];
$file=scandir('../output/'.$user.DIRECTORY_SEPARATOR);
$file=array_diff($file,array('.','..'));
$jsonarr=[];
foreach ($file as $k => $v){
    $jsonarr[] = [$k-2 => $v, "url" => "https://api.skylineflyleague.cn/efb/NavExchanger/output/$user/$v"];
}
$jsonarr["total"]=sizeof($jsonarr);
echo json_encode($jsonarr);
//var_dump($jsonarr);