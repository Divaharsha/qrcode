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
        $email = $db->escapeString($fn->xss_clean($_POST['email']));
        $password = $db->escapeString($fn->xss_clean($_POST['password']));

        if (empty($name)) {
            $error['name'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($branch)) {
            $error['branch'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($email)) {
            $error['email'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($password)) {
            $error['password'] = " <span class='label label-danger'>Required!</span>";
        }
        if (!empty($name) && !empty($branch) && !empty($email)  && !empty($password))
        {
            $sql = "SELECT * FROM hods WHERE branch = '$branch'";
            $db->sql($sql);
            $res = $db->getResult();              
            $num = $db->numRows($res);
            if($num == 1){
                $error['add_menu'] = " <span class='label label-danger'>HOD already Exist for this branch</span>";

            }
            else{
                $sql = "INSERT INTO hods (name,branch,email,password) VALUES('$name','$branch','$email','$password')";
                $db->sql($sql);
                $staff_result = $db->getResult();
                if (!empty($staff_result)) {
                    $staff_result = 0;
                } else {
                    $staff_result = 1;
                }
                if ($staff_result == 1) {
                    $error['add_menu'] = "<section class='content-header'>
                                                    <span class='label label-success'>HOD Added Successfully</span>
                                                    <h4><small><a  href='hods.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to HODs</a></small></h4>
                                                     </section>";
                } else {
                    $error['add_menu'] = " <span class='label label-danger'>Failed</span>";
                }

            }


        }
    }
?>
<section class="content-header">
    <h1>Add HOD</h1>
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
                    <h3 class="box-title">Add HOD</h3>
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
                                    <label for="exampleInputEmail1">HOD Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
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
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-4'>
                                    <label for="exampleInputEmail1">Email</label> <i class="text-danger asterik">*</i><?php echo isset($error['email']) ? $error['email'] : ''; ?>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="exampleInputEmail1">Password</label> <i class="text-danger asterik">*</i><?php echo isset($error['password']) ? $error['password'] : ''; ?>
                                    <input type="password" class="form-control" name="password" required>
                                </div>

                            </div>
                        </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <input type="submit" class="btn-primary btn" value="Add HOD" name="btnAdd" />&nbsp;

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