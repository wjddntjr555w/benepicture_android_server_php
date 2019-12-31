<style>
    .td-label {
        background: #E7EAEB;
        width: 15%;
    }

    .tbl-main-info td {
        padding: 10px;
    }

    .info-pan {
        background: white;
        padding: 20px;
    }
</style>
<div class="info-pan">
    <div>
        <table class="tbl-main-info table-bordered" width="100%">
            <tbody>
            <tr>
                <td class="td-label" style="width: 15%">광고명</td>
                <td style="width: 35%">
                    <?= isset($advertise_info) ? $advertise_info->f_name : '' ?>
                </td>
                <td colspan="2">광고 유형 설정</td>
            </tr>
            <tr>
                <td class="td-label">노출회수</td>
                <td style="width: 40%">
                    <?= isset($advertise_info) ? $advertise_info->f_visible_cnt : '' ?>
                </td>
                <td class="td-label" style="width: 15%">광고 유형</td>
                <td style="width: 35%;padding-left: 10px">
                    <?php
                    if (isset($advertise_info)) {
                        if ($advertise_info->f_type == ADVERTISE_GAME)
                            echo '게임';
                        else
                            echo '동영상';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="td-label">1회 적립금</td>
                <td style="width: 40%">
                    <?= isset($advertise_info) ? $advertise_info->f_visible_cost : '' ?>
                </td>
                <td class="td-label">게임 유형</td>
                <td>
                    <?php
                    if (isset($advertise_info)) {
                        if ($advertise_info->f_game_type == GAME_SWITCH)
                            echo '스위칭퍼즐';
                        else
                            echo '짝맞추기';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="td-label">관리자적립금</td>
                <td style="width: 40%">
                    <?= isset($advertise_info) ? $advertise_info->f_admin_cost : '' ?>
                </td>
                <td class="td-label">사진 업로드</td>
                <td id="td_upload_photo">
                    <?php
                    if (isset($advertise_info)) {
                        if ($advertise_info->f_type == ADVERTISE_GAME) {
                            ?>
                            <img class="thumbnail" style="display: inline-flex;margin-bottom: 0" width="250px"
                                 height="250px" src="<?= $advertise_info->f_media ?>">
                            <?php
                        }
                    }
                    ?>

                </td>
            </tr>
            <tr>
                <td class="td-label">예상 총 비용</td>
                <td style="width: 40%">
                    <?php
                    if (isset($advertise_info)) {
                        echo ((int)($advertise_info->f_visible_cost) + (int)($advertise_info->f_admin_cost)) * (int)($advertise_info->f_visible_cnt);
                    }
                    ?>
                </td>
                <td class="td-label">영상 업로드</td>
                <td id="td_upload_video">
                    <?php
                    if (isset($advertise_info)) {
                        if ($advertise_info->f_type == ADVERTISE_VIDEO) {
                            ?>
                            동영상파일
<!--                            <img class="thumbnail" style="display: inline-flex;margin-bottom: 0" width="250px"-->
<!--                                 height="250px" src="--><?//= $advertise_info->f_media ?><!--">-->
                            <?php
                        }
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="td-label">링크설정</td>
                <td style="width: 40%">
                    <?= isset($advertise_info) ? $advertise_info->f_link : '' ?>
                </td>
                <td class="td-label">등록기간</td>
                <td>
                    <?php
                    if (isset($advertise_info)) {
                        echo $advertise_info->f_from . '  ~  ' . $advertise_info->f_to;
                    }
                    ?>
                </td>
            </tr>
            </tbody>
        </table>
        <div style="margin-top: 10px;margin-bottom: 10px">타깃 설정</div>
        <table class="tbl-main-info table-bordered" width="100%">
            <tbody>
            <tr>
                <td style="width: 15%" class="td-label">성별</td>
                <td style="padding-left: 10px;width: 40%">
                    <?php
                    if (isset($advertise_info)) {
                        if ($advertise_info->f_target_gender == TARGET_GENDER_MALE)
                            echo '남자';
                        else if ($advertise_info->f_target_gender == TARGET_GENDER_FEMALE)
                            echo '여자';
                        else
                            echo '상관없음';
                    }
                    ?>
                </td>
                <td style="width: 15%" class="td-label">연령</td>
                <td colspan="3">
                    <?php
                    if (isset($advertise_info)) {
                        echo $advertise_info->f_age_from . '  ~  ' . $advertise_info->f_age_to;
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="td-label">키워드 설정</td>
                <td style="width: 40%;">
                    <?= isset($advertise_info) ? $advertise_info->f_keyword : '' ?>
                </td>
                <td style="width: 15%" class="td-label">광고주</td>
                <td>
                    <?php
                    if (isset($advertise_info)) {
                        echo $advertise_info->f_user;
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="td-label">광고상태</td>
                <td colspan="3"><?= isset($advertise_info) ? $advertise_info->f_status : '' ?></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div id="chart_pad">
        <?php
        require_once "chart.php";
        ?>
    </div>


    <div class="row">
        <div class="col offset-m1 offset-l1 s12 m8 l8">
            <p id="my_pagination" style="text-align: center"></p>
        </div>
    </div>
    <div>
        <table class="table table-bordered">
            <tbody>
            <tr>
                <td class="td-label">집행 기간 총 노출수</td>
                <td><?= $all_visit_cnt . '회' ?></td>
            </tr>
            <tr>
                <td class="td-label">집행 기간 URL 총 클릭수</td>
                <td><?= $all_click_cnt . '회' ?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div>
        <table class="table table-bordered">
            <tbody>
            <tr>
                <td class="td-label">연령대</td>
                <td>0~9세</td>
                <td>10~19세</td>
                <td>20~29세</td>
                <td>30~39세</td>
                <td>40~49세</td>
                <td>50~59세</td>
                <td>60세이상</td>
            </tr>
            <tr>
                <td class="td-label">남성</td>
                <?php
                foreach ($age_statistics as $list) {
                    ?>
                    <td><?= $list['ageM'] . '회' ?></td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <td class="td-label">여성</td>
                <?php
                foreach ($age_statistics as $list) {
                    ?>
                    <td><?= $list['ageF'] . '회' ?></td>
                    <?php
                }
                ?>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<script>

    $(document).ready(function () {

    });

    function draw_chart(page_num) {
        $.post('<?=base_url("Advertise/get_page_data")?>', {
            advertise_id: <?=$advertise_info->f_idx?>,
            page_num: page_num
        }, function (result) {
            $('#chart_pad').html(result);
        });
    }

    function visitDetail(visit_day)
    {
        var advertise_id = <?=$advertise_info->f_idx?>;
        location.href = "<?php echo base_url('advertise/visit_detail')?>" + "?id=" + advertise_id + "&day=" + visit_day;
    }
</script>