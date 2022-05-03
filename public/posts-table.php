<section class="content-header">
    <h1>
        Posts /
        <small><a href="home.php"><i class="fa fa-home"></i> Home</a></small>
</h1>
    
</section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive">
                        <table id='posts_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=posts" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="true" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "students-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
                                    <th data-field="id" data-sortable="true">ID</th>
                                    <th data-field="user_id" data-sortable="true">User Id</th>
                                    <th data-field="caption" data-sortable="true">caption</th>
                                    <th data-field="image" data-sortable="true" >Image</th>
                                    <th data-field="operate" data-events="actionEvents">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <div class="separator"> </div>
        </div>
        <!-- /.row (main row) -->
    </section>

<script>
    $('#seller_id').on('change', function() {
        $('#products_table').bootstrapTable('refresh');
    });
    $('#community').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });

    function queryParams(p) {
        return {
            "category_id": $('#category_id').val(),
            "seller_id": $('#seller_id').val(),
            "community": $('#community').val(),
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        };
    }
</script>
<script>
    $('#category_id').on('change', function() {
        id = $('#category_id').val();
        $('#products_table').bootstrapTable('refresh');
    });

    window.actionEvents = {
        'click .set-product-deactive': function(e, value, rows, index) {
            var p_id = $(this).data("id");
            $.ajax({
                url: 'public/db-operation.php',
                type: "get",
                data: 'id=' + p_id + '&product_status=1&type=deactive',
                success: function(result) {
                    if (result == 1)
                        $('#products_table').bootstrapTable('refresh');
                    else
                        alert('Error! Product could not be deactivated.');
                }
            });

        },
        'click .set-product-active': function(e, value, rows, index) {
            var p_id = $(this).data("id");
            $.ajax({
                url: 'public/db-operation.php',
                type: "get",
                data: 'id=' + p_id + '&product_status=1&type=active',
                success: function(result) {
                    if (result == 1)
                        $('#products_table').bootstrapTable('refresh');
                    else
                        alert('Error! Product could not be deactivated.');
                }
            });
        }


    };
</script>