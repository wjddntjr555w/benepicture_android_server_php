<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2/8/2018
 * Time: 10:38
 */
?>
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <!--<div class="page-logo">
            <a href="<?php /*echo base_url('dashboard')*/ ?>">
                <img src="<?php /*echo assets_url('/global/img/ic_logo.png')*/ ?>" alt="logo" class="logo-default" style="height: 50px;">
            </a>
            <div class="menu-toggler sidebar-toggler invisible">
                <span></span>
            </div>
        </div>-->
        <div class="page-logo" style="width: auto;">
            <span style="font-size: 20px; color: white; line-height: 68px">
                <?php
                switch ($_SESSION[ADMIN_PRIVILEGE]) {
                    case  ADMIN_ADVERTISE:
                        echo '[광고주]님 환영합니다.';
                        break;
                    case  ADMIN_COMMON:
                        echo '[관리자]님 환영합니다.';
                        break;
                    case  ADMIN_SUPER:
                        echo '[super 관리자]님 환영합니다.';
                        break;
                }
                ?>
            </span>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
           data-target=".navbar-collapse">
            <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-user">
                    <a href="<?php echo base_url('login/logout') ?>" class="dropdown-toggle"
                       style="padding-right: 8px;">
                        <span class="username username-hide-on-mobile"> 로그아웃 </span>
                        <i class="icon-login hidden"></i>
                    </a>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->