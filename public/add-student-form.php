<?php
include_once('includes/functions.php');
date_default_timezone_set('Asia/Kolkata');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

$sql_query = "SELECT id, name FROM category ORDER BY id ASC";
$db->sql($sql_query);

$res = $db->getResult();
$sql_query = "SELECT value FROM settings WHERE variable = 'Currency'";
$pincode_ids_exc = "";
$db->sql($sql_query);

$res_cur = $db->getResult();
if (isset($_POST['btnAdd'])) {
        $error = array();
        $name = $db->escapeString($fn->xss_clean($_POST['name']));
        $branch = $db->escapeString($fn->xss_clean($_POST['branch']));
        $attendence_percentage = $db->escapeString($fn->xss_clean($_POST['attendence_percentage']));
        $mobile = $db->escapeString($fn->xss_clean($_POST['mobile']));
        $parent_mobile = $db->escapeString($fn->xss_clean($_POST['parent_mobile']));

        $image_error = $db->escapeString($_FILES['profile']['error']);
        if (empty($name)) {
            $error['name'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($branch)) {
            $error['branch'] = " <span class='label label-danger'>Required!</span>";
        }

        if (empty($attendence_percentage)) {
            $error['attendence_percentage'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($mobile)) {
            $error['mobile'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($parent_mobile)) {
            $error['parent_mobile'] = " <span class='label label-danger'>Required!</span>";
        }
        if ($image_error > 0) {
            $error['profile'] = " <span class='label label-danger'>Required!</span>";

        }
        if (!empty($name) && !empty($branch) && !empty($attendence_percentage) && !empty($mobile) && !empty($parent_mobile))
        {
            error_reporting(E_ERROR | E_PARSE);
            $extension = end(explode(".", $_FILES["profile"]["name"]));
            $string = '0123456789';
            $file = preg_replace("/\s+/", "_", $_FILES['profile']['name']);
            $menu_image = $function->get_random_string($string, 4) . "-" . date("Y-m-d") . "." . $extension;
    
            // upload new image
            $upload = move_uploaded_file($_FILES['profile']['tmp_name'], 'upload/profile/' . $menu_image);
    
            // insert new data to menu table
            $upload_image = 'upload/profile/' . $menu_image;


            $sql = "INSERT INTO students (name,branch,attendence_percentage,mobile,parent_mobile,profile) VALUES('$name','$branch','$attendence_percentage','$mobile','$parent_mobile','$upload_image')";
            $db->sql($sql);
            $student_result = $db->getResult();
            if (!empty($student_result)) {
                $student_result = 0;
            } else {
                $student_result = 1;
            }
            if ($student_result == 1) {
                $error['add_menu'] = "<section class='content-header'>
                                                <span class='label label-success'>Student Added Successfully</span>
                                                <h4><small><a  href='students.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Products</a></small></h4>
                                                 </section>";
            } else {
                $error['add_menu'] = " <span class='label label-danger'>Failed</span>";
            }

        }
    }
?>
<section class="content-header">
    <h1>Add Student</h1>
    <?php echo isset($error['add_menu']) ? $error['add_menu'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>

</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Student</h3>
                </div>
                <div class="box-header">
                    <?php echo isset($error['cancelable']) ? '<span class="label label-danger">Till status is required.</span>' : ''; ?>
                </div>

                <!-- /.box-header -->
                <!-- form start -->
                <form id='add_student_form' method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-4'>
                                    <label for="exampleInputEmail1">Student Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class='form-group col-md-4'>
                                    <label for="">Select Branch</label> <i class="text-danger asterik">*</i> <?php echo isset($error['branch']) ? $error['branch'] : ''; ?><br>
                                    <select id="branch" name="branch" class="form-control">
                                        <option value="">Select</option>
                                        <option value="ECE">ECE</option>
                                        <option value="CSE">CSE</option>
                                        <option value="MECH">MECH</option>
                                        <option value="CIVIL">CIVIL</option>
                                    </select>
                                </div>
                                <div class='col-md-4'>
                                    <label for="exampleInputEmail1">Attendence Percentage</label> <i class="text-danger asterik">*</i><?php echo isset($error['attendence_percentage']) ? $error['attendence_percentage'] : ''; ?>
                                    <input type="number" class="form-control" name="attendence_percentage" required>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-4'>
                                    <label for="exampleInputEmail1">Mobile</label> <i class="text-danger asterik">*</i><?php echo isset($error['mobile']) ? $error['mobile'] : ''; ?>
                                    <input type="number" class="form-control" name="mobile" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="exampleInputEmail1">Parent Mobile No.</label> <i class="text-danger asterik">*</i><?php echo isset($error['parent_mobile']) ? $error['parent_mobile'] : ''; ?>
                                    <input type="number" class="form-control" name="parent_mobile" required>
                                </div>

                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="exampleInputFile">Profile</label><i class="text-danger asterik">*</i><?php echo isset($error['profile']) ? $error['profile'] : ''; ?>
                            <input type="file" name="profile" accept="image/png,  image/jpeg" id="profile" />
                        </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <input type="submit" class="btn-primary btn" value="Add Student" name="btnAdd" />&nbsp;

                        <!--<div  id="res"></div>-->
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>
<div class="separator"> </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#add_student_form').validate({

        ignore: [],
        debug: false,
        rules: {
            name: "required",
            roll_no: "required",
            email: "required",
            mobile: "required",
            password: "required",
            department: "required",
            gender: "required",
            caste: "required",

        }
    });
    $('#btnClear').on('click', function() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData('');
        }
    });
</script>