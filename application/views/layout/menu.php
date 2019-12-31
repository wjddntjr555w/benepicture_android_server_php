<?php

?>
<!-- BEGIN SIDEBAR -->
<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
<div class="page-sidebar navbar-collapse collapse">
    <!-- BEGIN SIDEBAR MENU -->
    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-hover-submenu " data-keep-expanded="false"
        data-auto-scroll="true" data-slide-speed="200">
        <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        <li class="sidebar-toggler-wrapper hide">
            <div class="sidebar-toggler">
                <span></span>
            </div>
        </li>
        <!-- END SIDEBAR TOGGLER BUTTON -->
        <?php if (DEV_MODE) { ?>
            <li class="heading hidden">
                <h3 class="uppercase">기본메뉴</h3>
            </li>
        <?php } ?>
        <?php
        if ($_SESSION[ADMIN_PRIVILEGE] == ADMIN_ADVERTISE) {
            ?>
            <li class="nav-item  ">
                <a href="<?php echo base_url('Mymain') ?>" class="nav-link nav-toggle">
                    <i class="fa fa-home"></i>
                    <span class="title">메인</span>
                </a>
            </li>
            <?php
        }
        if ($_SESSION[ADMIN_PRIVILEGE] != ADMIN_ADVERTISE) {
            ?>
            <li class="nav-item  ">
                <a href="<?php echo base_url('main') ?>" class="nav-link nav-toggle">
                    <i class="fa fa-home"></i>
                    <span class="title">메인</span>
                </a>
            </li>
            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-users"></i>
                    <span class="title">회원</span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="<?php echo base_url('Member/advertise_manager') ?>" class="nav-link ">
                            <span class="title">광고주</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="<?php echo base_url('Member/index') ?>" class="nav-link ">
                            <span class="title">일반회원</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="<?php echo base_url('Member/exit_member') ?>" class="nav-link ">
                            <span class="title">탈퇴회원</span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php
        }
        if ($_SESSION[ADMIN_PRIVILEGE] == ADMIN_ADVERTISE) {
            ?>
            <li class="nav-item  ">
                <a href="<?php echo base_url('Myadvertise') ?>" class="nav-link nav-toggle">
                    <i class="fa fa-rocket"></i>
                    <span class="title">광고</span>
                </a>
            </li>
            <?php
        }
        if ($_SESSION[ADMIN_PRIVILEGE] != ADMIN_ADVERTISE) {
            ?>
            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-rocket"></i>
                    <span class="title">광고</span>

                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="<?php echo base_url('Advertise/index') ?>" class="nav-link ">
                            <span class="title">광고등록</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="<?php echo base_url('Advertise/view') ?>" class="nav-link ">
                            <span class="title">광고조회</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="<?php echo base_url('Advertise/request') ?>" class="nav-link ">
                            <span class="title">신청목록</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item  ">
                <a href="<?php echo base_url('Keyword') ?>" class="nav-link nav-toggle">
                    <i class="fa fa-key"></i>
                    <span class="title">키워드</span>
                </a>
            </li>

            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-money"></i>
                    <span class="title">복권</span>

                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="<?php echo base_url('Winner/index') ?>" class="nav-link ">
                            <span class="title">당첨조회</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="<?php echo base_url('Winner/after') ?>" class="nav-link ">
                            <span class="title">후기관리</span>
                        </a>
                    </li>
                    <?php
                    if ($_SESSION[ADMIN_PRIVILEGE] == ADMIN_SUPER) {
                        ?>
                        <li class="nav-item  ">
                            <a href="<?php echo base_url('Winner/default_winner') ?>" class="nav-link ">
                                <span class="title">당첨설정</span>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </li>
            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-dollar"></i>
                    <span class="title">출금</span>

                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="<?php echo base_url('Withdraw/index') ?>" class="nav-link ">
                            <span class="title">출금요청</span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php if (true) { ?>
                <li class="heading hidden">
                    <h3 class="uppercase">Api 관련메뉴</h3>
                </li>
                <li class="nav-item  ">
                    <a href="<?php echo base_url('apimng') ?>" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">Api 관리</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="<?php echo base_url('api_manual') ?>" class="nav-link nav-toggle" target="_blank">
                        <i class="icon-doc"></i>
                        <span class="title">Api 문서보기</span>
                    </a>
                </li>
            <?php } ?>
            <?php
        }
        ?>
    </ul>
    <!-- END SIDEBAR MENU -->
    <!-- END SIDEBAR MENU -->
</div>
<script>
    $("ul.page-sidebar-menu li.nav-item").each(function () {
        $(this).removeClass('active').removeClass('open');
    });
    $("ul.page-sidebar-menu > li.nav-item:eq(<?php echo $page_index; ?>)").addClass('active');
    if ($("ul.page-sidebar-menu > li.nav-item:eq(<?php echo $page_index; ?>) > ul.sub-menu").length > 0) {
        $("ul.page-sidebar-menu > li.nav-item:eq(<?php echo $page_index; ?>)").addClass('open');
        $("ul.page-sidebar-menu > li.nav-item:eq(<?php echo $page_index; ?>) a.nav-link span.arrow").addClass('open');
        $("ul.page-sidebar-menu > li.nav-item:eq(<?php echo $page_index; ?>) > ul.sub-menu li.nav-item:eq(<?php echo $page_sub_index?>)").addClass('active open');
    } else {
        $("ul.page-sidebar-menu > li.nav-item:eq(<?php echo $page_index; ?>)").removeClass('open');
    }
</script>