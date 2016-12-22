<?php if ($display_login_notification == true) { ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $.snackbar({content: '<?= lang('common_welcome_snackbar') ?>'});
        });
    </script>
<?php } ?>

<div class=" panel container-fluid">
    </br>
    <p class="tlScale"><?= lang('c_home_title') ?></p>

    <p class="txScale"><?= lang('c_home_info_residents') ?></p>

    <ul>
        <?php foreach ($recent_residents as $resident) {
            ?><li> <?php echo $resident->first_name; ?> <?php echo $resident->last_name;?>: <?php echo date("Y-m-d", strtotime($resident->last_completed));
            ?></li> 
        <?php } ?>
    </ul>
    <hr>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div id="chart1_div" style="width:100%"></div>
            </div>
            <div class="col-xs-6">
                </br>

            </div>
        </div>
    </div>
    </br>
</div>


<script type="text/javascript">


    google.charts.load('current', {'packages': ['corechart']});

    chart1function();

    //formChart1.addEventListener('submit', function(e) {
    function chart1function() {
        console.log('chart 1 submitted');
        //e.preventDefault();



        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>index.php/caregiver/load_avarage_score_per_category_chart",
            data: {},
            dataType: "text",
            cache: false,

            success: function (data) {
                var Yaxis = [];
                var Xaxis = [];
                console.log(data);
                var response = JSON.parse(data);
                console.log(response);

                Xaxis = response[0];
                Yaxis = response[1];

                //google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);




                function drawChart() {

                    var data = new google.visualization.DataTable();
                    columnChart(Yaxis, Xaxis, data, "chart1_div", 'average scores of all categories');
                }


            }
        });
        return false;
    }







</script>