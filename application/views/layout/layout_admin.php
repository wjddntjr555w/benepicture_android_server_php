<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2/8/2018
 * Time: 10:57
 */
?>

<!DOCTYPE html>
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8"/>
    <title><?php echo '베네픽쳐 | ' . (is_array($page_title) ? $page_title[0] : $page_title); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="BenePicture Admin Page" name="description"/>
    <meta content="Gambler" name="author"/>
    <!--header_link-->
    <?php require_once 'link_admin_header.php'; ?>
</head>

<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-md">

<!--<div class="page-wrapper">-->
<!-- BEGIN HEADER -->
<?php echo $header ?>
<!-- END HEADER -->
<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"></div>
<!-- END HEADER & CONTENT DIVIDER -->
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <?php echo $menu ?>
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper" id="pageContent">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">
            <!-- BEGIN PAGE BAR -->
            <div class="page-bar" style="color: white">
                <div class="page-breadcrumb">
                    <?php
                    for ($nInd = 0; $nInd < count($page_title); $nInd++) {
                        $class = $nInd == 0 ? 'fa fa-circle' : 'fa fa-angle-right';
                        echo '<span>' .
//                            "<i class='{$class}'></i>" .
                            "<span>{$page_title[$nInd]}</span>" .
                            '</span>';
                    }
                    ?>
                </div>
                <a class="page-bar-btn btn-refresh" style="display: none">새로 고침</a>
                <label style="float: right;margin-bottom: 0;display: none" id="winner_round">
                    <span class="info-label" style="height: 38px;line-height: 38px">회차설정 : </span>
                    <select id="b_winner_round" class="form-control input-sm input-xsmall input-inline" style="height: 38px;line-height: 38px;margin-top: -3px" onchange="winner_table.draw()">
                        <option value="1">1회차</option>
                        <option value="2">2회차</option>
                        <option value="3">3회차</option>
                    </select>
                </label>
            </div>
            <!-- END PAGE BAR -->
            <!-- BEGIN PAGE TITLE-->
            <h1 class="title page-title margin-top-20 hidden"> <?php echo $page_title[count($page_title) - 1] ?> </h1>

            <!-- END PAGE TITLE-->
            <!-- END PAGE HEADER-->

            <?php echo $main ?>

        </div>

        <!-- END CONTENT BODY -->
    </div>

    <!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer hide">
    <div class="page-footer-inner invisible"> 2018 &copy; BenePicture By
        <a target="_blank" href="http://keenthemes.com">Gambler</a>
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- END FOOTER -->

<?php require_once 'link_admin_footer.php'; ?>

<!--</div>-->

</body>

</html>