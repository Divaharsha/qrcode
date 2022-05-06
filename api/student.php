<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');
include('../includes/custom-functions.php');
include('../includes/variables.php');
$db = new Database();
$db->connect();
$fn = new custom_functions;


if (empty($_POST['student_id'])) {
    $response['success'] = false;
    $response['message'] = "Student ID is Empty";
    print_r(json_encode($response));
    return false;
}
$student_id = $db->escapeString($_POST['student_id']);

$sql = "SELECT * FROM students WHERE id = '$student_id'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
          

if ($num == 1) {
    foreach ($res as $row) {
        $temp['id'] = $row['id'];
        $temp['name'] = $row['name'];
        $temp['branch'] = $row['branch'];
        $temp['profile'] = DOMAIN_URL.$row['profile'];
        $temp['mobile'] = $row['mobile'];
        $temp['parent_mobile'] = $row['parent_mobile'];
        $temp['attendence_percentage'] = $row['attendence_percentage'];
        $rows[] = $temp;
        
    }    
    $response['success'] = true;
    $response['message'] = "Students Retrived Successfully";
    $response['data'] = $rows;
    print_r(json_encode($response));

}
else{
    $response['success'] = false;
    $response['message'] = "No Student Found";
    print_r(json_encode($response));

}

?>