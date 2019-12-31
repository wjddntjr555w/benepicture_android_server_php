<?php

?>
    <!--[if lt IE 9]>
    <script src="<?php echo assets_url() ?>global/plugins/respond.min.js"></script>
    <script src="<?php echo assets_url() ?>global/plugins/excanvas.min.js"></script>
    <script src="<?php echo assets_url() ?>global/plugins/ie8.fix.min.js"></script>
    <![endif]-->

    <!-- BEGIN CORE PLUGINS -->
    <script src="<?php echo assets_url() ?>global/plugins/bootstrap/js/bootstrap.min.js"
            type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>global/plugins/jquery-slimscroll/jquery.slimscroll.min.js"
            type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>global/plugins/bootstrap-switch/js/bootstrap-switch.min.js"
            type="text/javascript"></script>
    <!-- END CORE PLUGINS -->

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="<?php echo assets_url() ?>global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="<?php echo assets_url('global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') ?>"
            type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"
            type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>global/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.kr.min.js"
            type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>global/plugins/bootstrap-toastr/toastr.min.js"
            type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>global/plugins/jquery-validation/js/jquery.validate.min.js"
            type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>global/plugins/datatables/datatables.min.js"
            type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"
            type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>global/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>global/plugins/typeahead/handlebars.min.js" type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>global/plugins/typeahead/typeahead.bundle.min.js"
            type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="<?php echo assets_url() ?>global/scripts/app.min.js" type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>global/scripts/jquery.form.js" type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>global/scripts/ajaxupload.3.6.js" type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>global/scripts/custom.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->

    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="<?php echo assets_url() ?>layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>layouts/global/scripts/quick-sidebar.min.js"
            type="text/javascript"></script>
    <script src="<?php echo assets_url() ?>layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
    <!-- END THEME LAYOUT SCRIPTS -->


    <script src="<?php echo assets_url('pages/scripts/components-date-time-pickers.min.js') ?>"
            type="text/javascript"></script>
    <script src="<?=assets_url('global/plugins/select2/js/select2.full.min.js')?>" type="text/javascript"></script>



    <script src="<?=assets_url('global/plugins/highcharts/js/highcharts.js')?>" type="text/javascript"></script>
    <script src="<?=assets_url('global/plugins/highcharts/js/highcharts-3d.js')?>" type="text/javascript"></script>
    <script src="<?=assets_url('global/plugins/highcharts/js/highcharts-more.js')?>" type="text/javascript"></script>

    <script src="<?=assets_url('global/plugins/jquery-bootpag/jquery.bootpag.min.js')?>" type="text/javascript"></script>
<!--    <script src="--><?//=assets_url('global/plugins/jquery.pulsate.min.js')?><!--" type="text/javascript"></script>-->


<?php if (isset($error) && $error['error_flag']) { ?>
    <script type="text/javascript">
        showToast(
            '<?php echo $error['error_class'] ?>',
            '<?php echo $error['error_msg'] ?>',
            '<?php echo $error['error_title'] ?>');
    </script>
<?php } ?>