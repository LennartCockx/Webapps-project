<!DOCTYPE html>
<html lang="en">
    <head> 
        <title><?= lang('c_title') ?></title>

        <!--Load unique css-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datatables/css/dataTables.bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.css"/>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker3.min.css"/>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/datatables-colvis/1.1.2/css/dataTables.colVis.css"/>

        <!-- Load unique scripts -->
        <script src="<?php echo base_url(); ?>assets/js/statistics/chartCallbacks.js"></script>

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

        {include}

        <!--	HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--	 WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--	[if lt IE 9]>
                          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
                        <![endif]-->
    </head> 
    <body>
        {navbar}
        <br><br>
        <div class="row">
            <div class="col-md-10 col-md-offset-1 col-sm-12 col-sm-offset-0">
                    {content}
                
            </div>
        </div>
    </body>
</html>

<!--Activate material design-->
<script>$.material.init();</script>