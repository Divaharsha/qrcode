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

if (isset($_GET['id'])) {
    $ID = $db->escapeString($fn->xss_clean($_GET['id']));
} else {
    // $ID = "";
    return false;
    exit(0);
}
?>
<section class="content-header">
    <h1>QR Code</h1>
    <?php echo isset($error['add_menu']) ? $error['add_menu'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>

</section>
<section class="content">
    <div class="row">
        <div class="form-group">
            <input type="hidden" class="dataBox" name="data" placeholder="Paste your content" id="dataBox" value="<?php echo $ID ?>"
                    autocomplete="false">
                <button class="btn btn-primary" id="generateBtn">Generate</button>
                <div class="qrdiv hide" id="qrdiv">
                    <div class="qrcode" id="qrcode"></div>
                    <button class="btn btn-primary" id="downloadBtn">Download</button>
                </div>

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