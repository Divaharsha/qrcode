<?php
include_once('includes/functions.php');
date_default_timezone_set('Asia/Kolkata');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_GET['id'])) {
    $ID = $db->escapeString($fn->xss_clean($_GET['id']));
} else {
    // $ID = "";
    return false;
    exit(0);
}

if (isset($_POST['btnUpdate'])){
    $error = array();
    $name = $db->escapeString($fn->xss_clean($_POST['name']));
    $mobile = $db->escapeString($fn->xss_clean($_POST['mobile']));
    $parent_mobile = $db->escapeString($fn->xss_clean($_POST['parent_mobile']));
    $branch = $db->escapeString($fn->xss_clean($_POST['branch']));
    $attendence_percentage = $db->escapeString($fn->xss_clean($_POST['attendence_percentage']));

    if ($_FILES['profile']['size'] != 0 && $_FILES['profile']['error'] == 0 && !empty($_FILES['profile'])) {
        //image isn't empty and update the image
        $old_image = $db->escapeString($_POST['old_image']);
        $extension = pathinfo($_FILES["profile"]["name"])['extension'];

        $result = $fn->validate_image($_FILES["profile"]);
        // if (!$result) {
        //     echo " <span class='label label-danger'>Logo image type must jpg, jpeg, gif, or png!</span>";
        //     return false;
        //     exit();
        // }
        $target_path = 'upload/profile/';
        
        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = $target_path . "" . $filename;
        if (!move_uploaded_file($_FILES["profile"]["tmp_name"], $full_path)) {
            echo '<p class="alert alert-danger">Can not upload image.</p>';
            return false;
            exit();
        }
        $upload_image = 'upload/profile/' . $filename;
        $sql = "UPDATE students SET `profile`='" . $upload_image . "' WHERE `id`=" . $ID;
        $db->sql($sql);
    }
    $sql = "UPDATE students SET name='$name',mobile='$mobile',parent_mobile='$parent_mobile',branch='$branch',attendence_percentage='$attendence_percentage' WHERE id=$ID";
        if ($db->sql($sql)) {
            $error['add_menu'] = "<section class='content-header'>
            <span class='label label-success'>Student details Updated Successfully</span>
            <h4><small><a  href='students.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Students</a></small></h4>
             </section>";
    } else {
        $error['add_menu'] = " <span class='label label-danger'>Failed</span>";
    }
    
}
$data = array();
$sql = "SELECT * FROM students WHERE id = '$ID'";
$db->sql($sql);
$res = $db->getResult();
foreach ($res as $row)
    $data = $row;
?>
<section class="content-header">
    <h1>Edit Student</h1>
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
                    <h3 class="box-title">Edit Student</h3>
                </div>
                <div class="box-header">
                    <?php echo isset($error['cancelable']) ? '<span class="label label-danger">Till status is required.</span>' : ''; ?>
                </div>

                <!-- /.box-header -->
                <!-- form start -->
                <form id='edit_student_form' method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" id="old_image" name="old_image"  value="<?= $data['image']; ?>">
                        <div class="row">  
                            <div class="form-group">
                                <div class='col-md-4'>
                                    <label for="exampleInputEmail1">Student Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                    <input type="text" class="form-control" name="name" value="<?php echo $data['name']?>" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="exampleInputEmail1">Mobile</label> <i class="text-danger asterik">*</i><?php echo isset($error['mobile']) ? $error['mobile'] : ''; ?>
                                    <input type="number" class="form-control" name="mobile" value="<?php echo $data['mobile']?>" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="exampleInputEmail1">Parent Mobile</label> <i class="text-danger asterik">*</i><?php echo isset($error['parent_mobile']) ? $error['parent_mobile'] : ''; ?>
                                    <input type="number" class="form-control" name="parent_mobile" value="<?php echo $data['parent_mobile']?>" required>
                                </div>
                            </div>

                        </div>
                        <hr>

                        <div class="row">

                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="">Select Branch</label> <i class="text-danger asterik">*</i> <?php echo isset($error['branch']) ? $error['branch'] : ''; ?><br>
                                   
                                    <select id="branch" name="branch" class="form-control">
                                        <option value="">Select</option>
                                        <option <?=$data['branch'] == 'ECE' ? ' selected="selected"' : '';?> value="ECE">ECE</option>
                                        <option <?=$data['branch'] == 'CSE' ? ' selected="selected"' : '';?> value="CSE">CSE</option>
                                        <option <?=$data['branch'] == 'MECH' ? ' selected="selected"' : '';?> value="MECH">MECH</option>
                                        <option <?=$data['branch'] == 'CIVIL' ? ' selected="selected"' : '';?> value="CIVIL">CIVIL</option>
                                    </select>

                                </div>
                                <div class='col-md-4'>
                                    <label for="exampleInputEmail1">Attendence Percentage</label> <i class="text-danger asterik">*</i><?php echo isset($error['attendence_percentage']) ? $error['attendence_percentage'] : ''; ?>
                                    <input type="number" class="form-control" name="attendence_percentage" value="<?php echo $data['attendence_percentage']?>" required>
                                </div>
                            </div>

                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputFile">Profile</label>
                                    
                                    <input type="file" accept="image/png,  image/jpeg"  name="profile" id="profile">
                                    <p class="help-block"><img src="<?php echo $data['profile']; ?>" style="max-width:100%" /></p>
                                </div>
                            </div>
                        </div>
                    <div class="box-footer">
                        <input type="submit" class="btn-primary btn" value="Update" name="btnUpdate" />&nbsp;
                
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
    $('#edit_student_form').validate({

        ignore: [],
        debug: false,
        rules: {
            name: "required",
            roll_no: "required",
            email: "required",
            mobile: "required",
            password: "required",

        }
    });
    $('#btnClear').on('click', function() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData('');
        }
    });
</script>