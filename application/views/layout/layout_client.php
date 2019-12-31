<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2/8/2018
 * Time: 10:42
 */
?>

<!DOCTYPE html>
<html class="fixed" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?></title>
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="description" content="WithMe Admin"/>

	<?php require_once 'link_admin_header.php'; ?>

    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="<?php echo assets_url() ?>/pages/css/login.min.css" rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL STYLES -->


</head>
<body class=" login page-footer-fixed" style="background: #493D55 !important;">
<!-- BEGIN LOGO -->
<div class="logo">
    <a href="#" class="invisible">
        <img src="" alt="Fx Trainer"/> </a>
</div>
<!-- END LOGO -->

<?php echo $main ?>

<div class="page-footer hidden" style="height: 50px; text-align: center; display: flex;">
    <div class="page-footer-inner" style="margin: auto;">
        BenePicture (01234)서울시 서초구 서초대로 11 사업자등록번호 : 123-12-12345<br>
        TEL : 1000-1111 COPYRIGHT(c)2019 BenePicture. ALL RIGHTS RESERVED
    </div>
</div>

<?php require_once 'link_admin_footer.php'; ?>

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo assets_url() ?>/global/plugins/jquery-validation/js/additional-methods.min.js"
        type="text/javascript"></script>
<script src="<?php echo assets_url() ?>/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->

<!-- END PAGE LEVEL SCRIPTS -->

</body>
</html>