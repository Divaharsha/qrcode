<?php session_start();

include_once('includes/custom-functions.php');
include_once('includes/functions.php');
$function = new custom_functions;

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
$function = new custom_functions;
include "header.php";
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?= $settings['app_name'] ?> - Dashboard</title>
</head>

<body>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Home</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="home.php"> <i class="fa fa-home"></i> Home</a>
                </li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>34</h3>
                            <p>Students</p>
                        </div>
                        <div class="icon"><i class="fa fa-shopping-cart"></i></div>
                        <a href="orders.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>34</h3>
                            <p>Staffs</p>
                        </div>
                        <div class="icon"><i class="fa fa-cubes"></i></div>
                        <a href="products.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>34</h3>
                            <p>Exams</p>
                        </div>
                        <div class="icon"><i class="fa fa-users"></i></div>
                        <a href="customers.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        $('#filter_order').on('change', function() {
            $('#orders_table').bootstrapTable('refresh');
        });
        $('#seller_id').on('change', function() {
            $('#orders_table').bootstrapTable('refresh');
        });
    </script>
    <script>
        function queryParams(p) {
            return {
                "filter_order": $('#filter_order').val(),
                "seller_id": $('#seller_id').val(),
                limit: p.limit,
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                search: p.search
            };
        }

        function queryParams_top_seller(p) {
            return {
                limit: p.limit,
                sort: p.sort,
                order: p.order,
                offset: p.offset
            };
        }

        function queryParams_top_cat(p) {
            return {
                limit: p.limit,
                sort: p.sort,
                order: p.order,
                offset: p.offset
            };
        }
    </script>
    <?php include "footer.php"; ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawPieChart);

        function drawPieChart() {

            var data1 = google.visualization.arrayToDataTable([
                ['Product', 'Count'],
                <?php
                foreach ($result_products as $row) {
                    echo "['" . $db->escapeString($row['name']) . "'," . $row['product_count'] . "],";
                }
                ?>
            ]);

            var options1 = {
                title: 'Product Category Count',
                is3D: true
            };

            var chart1 = new google.visualization.PieChart(document.getElementById('piechart'));

            chart1.draw(data1, options1);
        }
    </script>

    <script>
        google.charts.load('current', {
            'packages': ['bar']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Date', 'Total Sale In <?= $settings['currency'] ?>'],
                <?php foreach ($result_order as $row) {
                    $date = date('d-M', strtotime($row['order_date']));
                    echo "['" . $date . "'," . $row['total_sale'] . "],";
                } ?>
            ]);
            var options = {
                chart: {
                    title: 'Weekly Sales',
                    subtitle: 'Total Sale In Last Week (Month: <?php echo date("M"); ?>)',
                }
            };
            var chart = new google.charts.Bar(document.getElementById('earning_chart'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }
    </script>
</body>

</html>