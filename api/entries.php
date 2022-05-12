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
if (empty($_POST['description'])) {
    $response['success'] = false;
    $response['message'] = "Description is Empty";
    print_r(json_encode($response));
    return false;
}

$student_id = $db->escapeString($_POST['student_id']);
$description = $db->escapeString($_POST['description']);
$sql = "SELECT * FROM students WHERE id = '$student_id'";
$db->sql($sql);
$res_stu = $db->getResult();              
$num = $db->numRows($res_stu);
if ($num == 1) {
    $currenttime = date('H:i');
    if(strtotime($currenttime)<=strtotime('09:00')) {
        $late = 'false';
    } else {
        $late = 'true';
    }
    if($late == 'true'){
        $sql = "INSERT INTO fine_late(`student_id`)VALUES('$student_id')";
        $db->sql($sql);

    }

    $sql = "SELECT * FROM fine_late WHERE student_id = '$student_id'";
    $db->sql($sql);
    $reslate = $db->getResult();              
    $num = $db->numRows($reslate);
    $branch = $res_stu[0]['branch'];
    $sql = "SELECT * FROM hods WHERE branch = '$branch'";
    $db->sql($sql);
    $reshod = $db->getResult(); 
    $reshodnum = $db->numRows($reslate);
    if($reshodnum > 0){
        $email = $reshod[0]['email'];

    }
    else{
        $email = '';

    }
    if($num == 4){
        $attendence_percentage = $res_stu[0]['attendence_percentage'] - 1;

        $sql = "UPDATE students SET attendence_percentage='$attendence_percentage' WHERE id = $student_id ";
        $db->sql($sql);
        $sql = "DELETE FROM fine_late WHERE student_id = $student_id ";
        $db->sql($sql);
 

    
    }
    $sql = "SELECT * FROM students WHERE id = '$student_id'";
    $db->sql($sql);
    $res_stu = $db->getResult();   
    $stu_name = $res_stu[0]['name'];
    $attendence_percentage = $res_stu[0]['attendence_percentage'];
    if($num == 4){
        $student_msg = 'Dear Student,you are late to campus / not in proper Dress code. So, 1% of attendence deducated  - Aditiya Educational Institutions';


    }
    else{
        $sql = "SELECT * FROM fine_late WHERE student_id = '$student_id'";
        $db->sql($sql);
        $fine_res = $db->getResult(); 
        $fine_res_num = $db->numRows($fine_res);  
        $chances = 4 - $fine_res_num;
        $student_msg = 'Dear Student,you are late to campus / not in proper Dress code. So You are having still '.$chances.' for more chances for deducation of 1% of attendence  - Aditiya Educational Institutions';

    }

    $sql = "INSERT INTO entries(`student_id`,`late`,`description`,`attendence`)VALUES('$student_id','$late','$description','$attendence_percentage')";
    $db->sql($sql);
    $response['success'] = true;
    $response['message'] = "Checked In Successfully";
    $response['parent_mobile'] = $res_stu[0]['parent_mobile'];
    $response['parent_message'] = 'Dear Parent,'.$stu_name.' arrived late to campus / not in proper Dress code. Please advice your ward to obey the collge rules - Aditiya Educational Institutions';
    $response['student_message'] = $student_msg;
    $response['staff_email'] = $email;
    $response['late'] = $late;
    print_r(json_encode($response));

}
else{
    $response['success'] = false;
    $response['message'] = "Student Not Found";
    print_r(json_encode($response));

}

?>