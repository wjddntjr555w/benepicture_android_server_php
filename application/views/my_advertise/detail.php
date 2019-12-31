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
    <h2 class="bold"><small>광고명 : </small><?php echo $advertise_info->f_name?></h2>
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
    function draw_chart(page_num) {
        $.post('<?=base_url("Myadvertise/get_page_data")?>', {
            advertise_id: <?=$advertise_info->f_idx?>,
            page_num: page_num
        }, function (result) {
            $('#chart_pad').html(result);
        });
    }
</script>