<div class="">
    <div id="highchart" style="height:500px;"></div>
</div>
<div class="">
    <table class="table table-bordered" width="100%">
        <tbody>
        <tr>
            <td class="td-label">일자</td>
            <?php
            if (count($statistics_info) > 0) {
                foreach ($statistics_info as $info) {
                    ?>
                    <td onclick="visitDetail('<?= $info['day'] ?>')" style="cursor: pointer"><?= $info['day'] ?></td>
                    <?php
                }
            }
            ?>
        </tr>
        <tr>
            <td class="td-label">노출회수</td>
            <?php
            if (count($statistics_info) > 0) {
                $i = 0;
                foreach ($statistics_info as $info) {
                    ?>
                    <td onclick="visitDetail('<?= $statistics_info[$i]['day'] ?>')" style="cursor: pointer"><?= $info['total_visit_cnt'] . '회' ?></td>
                    <?php
                    $i++;
                }
            }
            ?>
        </tr>
        <tr>
            <td class="td-label">URL클릭수</td>
            <?php
            if (count($statistics_info) > 0) {
                $j = 0;
                foreach ($statistics_info as $info) {
                    ?>
                    <td onclick="visitDetail('<?= $statistics_info[$j]['day'] ?>')" style="cursor: pointer"><?= $info['url_click_cnt'] . '회' ?></td>
                    <?php
                    $j++;
                }
            }
            ?>
        </tr>
        </tbody>
    </table>
</div>

<script>

    $(document).ready(function () {

        $("#my_pagination").bootpag({
            paginationClass: "pagination pagination-sm",
            next: '<i class="fa fa-angle-right"></i>',
            prev: '<i class="fa fa-angle-left"></i>',
            total: <?=$total_page?>,
            page: <?=$page_num?>,
            maxVisible: 7
        }).on("page", function (event, num) {
            draw_chart(num);
        });

        $('#highchart').highcharts({
            chart: {
                style: {
                    fontFamily: 'Open Sans'
                }
            },
            title: {
                text: '',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                // categories: ['df', 'df', 'Mar', 'Apr', 'May', 'Jun','Jul']
                categories: <?=json_encode($category)?>
            },
            yAxis: {
                title: {
                    text: '회수'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '회'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: '노출회수',
                data: <?=json_encode($total_visit_info)?>
            }, {
                name: 'URL 클릭수',
                data: <?=json_encode($total_click_info)?>
            }]
        });

    });


</script>