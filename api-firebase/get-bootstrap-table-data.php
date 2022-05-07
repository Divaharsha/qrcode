<?php
session_start();

// set time for session timeout
$currentTime = time() + 25200;
$expired = 3600;

// if session not set go to login page
if (!isset($_SESSION['username'])) {
    header("location:index.php");
}

// if current time is more than session timeout back to login page
if ($currentTime > $_SESSION['timeout']) {
    session_destroy();
    header("location:index.php");
}

// destroy previous session timeout and create new one
unset($_SESSION['timeout']);
$_SESSION['timeout'] = $currentTime + $expired;

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


include_once('../includes/custom-functions.php');
$fn = new custom_functions;
include_once('../includes/crud.php');
include_once('../includes/variables.php');
$db = new Database();
$db->connect();
$config = $fn->get_configurations();
if (isset($config['system_timezone']) && isset($config['system_timezone_gmt'])) {
    date_default_timezone_set($config['system_timezone']);
    $db->sql("SET `time_zone` = '" . $config['system_timezone_gmt'] . "'");
} else {
    date_default_timezone_set('Asia/Kolkata');
    $db->sql("SET `time_zone` = '+05:30'");
}
if (isset($_GET['table']) && $_GET['table'] == 'users') {

    $sql = "SELECT * FROM users ";
    $db->sql($sql);
    $res = $db->getResult();
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

        $operate = '<a href="userposts.php?id=' . $row['id'] . '" title="View">View post</a>';

        
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['email'] = $row['email'];
        $tempRow['role'] = $row['role'];
        $tempRow['description'] = $row['description'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
        }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'students') {

    $sql = "SELECT COUNT(`id`) as total FROM `students` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT * FROM students ";
    $db->sql($sql);
    $res = $db->getResult();
    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

        $operate = '<a href="edit-student.php?id=' . $row['id'] . '" title="Edit"><i class="fa fa-edit"></i></a>';
        $qrcode = '<a href="qrcode.php?id=' . $row['id'] . '" title="Edit"><p class="text-primary">QR Code</p></a>';
        
        
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['branch'] = $row['branch'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['profile'] = "<a data-lightbox='category' href='". $row['profile'] . "' data-caption='" . $row['name'] . "'><img src='". $row['profile'] . "' title='" . $row['name'] . "' height='50' /></a>";
        $tempRow['parent_mobile'] = $row['parent_mobile'];
        $tempRow['attendence_percentage'] = $row['attendence_percentage'];
        $tempRow['operate'] = $operate;
        $tempRow['qrcode'] = $qrcode;
        $rows[] = $tempRow;
        }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'hods') {

    $sql = "SELECT COUNT(`id`) as total FROM `hods` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];


    $sql = "SELECT * FROM hods ";
    $db->sql($sql);
    $res = $db->getResult();
    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

        $operate = '<a href="delete-hod.php?id=' . $row['id'] . '" title="Delete"><i class="fa fa-trash"></i></a>';
    
    
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['branch'] = $row['branch'];
        $tempRow['email'] = $row['email'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
        }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'checkin') {

    $sql = "SELECT COUNT(`id`) as total FROM `hods` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];


    $sql = "SELECT *,entries.id AS id FROM entries,students WHERE students.id = entries.student_id ";
    $db->sql($sql);
    $res = $db->getResult();
    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['late'] = $row['late'];
        $tempRow['description'] = $row['description'];
        $tempRow['time'] = $row['date_created'];
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'posts') {
    $where = '';
    if (isset($_GET['community']) && $_GET['community'] != '') {
        $community = $db->escapeString($fn->xss_clean($_GET['community']));
        $where .= " WHERE community = '$community' ";
    }
    $sql = "SELECT * FROM posts $where";
    $db->sql($sql);
    $res = $db->getResult();
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

        $operate = '<a href="view-product-variants.php?id=' . $row['id'] . '" title="View"><i class="fa fa-folder-open"></i></a>';
        
        $tempRow['id'] = $row['id'];
        $tempRow['user_id'] = $row['user_id'];
        $tempRow['caption'] = $row['caption'];
        $tempRow['image'] = "<a data-lightbox='product' href='" .'upload/post/'. $row['image'] . "' data-caption='" . $row['caption'] . "'><img src='" .'upload/post/'. $row['image'] . "' title='" . $row['caption'] . "' height='50' /></a>";
        
        //$temp['image'] = DOMAIN_URL  .'upload/post/'. $row['image'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'userposts') {
    $userid = $_GET['userid'];

    $sql = "SELECT * FROM posts WHERE user_id = '$userid'";
    $db->sql($sql);
    $res = $db->getResult();
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

        $operate = '<a href="view-product-variants.php?id=' . $row['id'] . '" title="View"><i class="fa fa-folder-open"></i></a>';
        
        $tempRow['id'] = $row['id'];
        $tempRow['user_id'] = $row['user_id'];
        $tempRow['caption'] = $row['caption'];
        $tempRow['image'] = "<a data-lightbox='product' href='" .'upload/post/'. $row['image'] . "' data-caption='" . $row['caption'] . "'><img src='" .'upload/post/'. $row['image'] . "' title='" . $row['caption'] . "' height='50' /></a>";
        
        //$temp['image'] = DOMAIN_URL  .'upload/post/'. $row['image'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}



$db->disconnect();
