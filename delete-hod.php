<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('includes/crud.php');
include('includes/custom-functions.php');
include('includes/variables.php');
$db = new Database();
$db->connect();
$fn = new custom_functions;

if (isset($_GET['id'])) {
    $ID = $db->escapeString($_GET['id']);
} else {
    // $ID = "";
    return false;
    exit(0);
}



$sql = "DELETE FROM hods WHERE id = '$ID'";
$db->sql($sql);
$res = $db->getResult();              
$num = $db->numRows($res);

header("location:hods.php");
?>