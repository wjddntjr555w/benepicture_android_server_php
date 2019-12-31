<style>
    .td-label {
        padding: 0 10px;
        background: #E7EAEB;
    }

    .tbl-main-info td {
        padding: 10px;
    }

    .info-pan {
        background: white;
        padding: 20px;
        display: table;
        width: 100%;
    }

    .advertise-name {
        font-size: 30px;
    }
</style>
<div class="info-pan">
    <div class="col-md-12" style="padding: 0;margin-bottom: 20px">
        <span>광고명:</span><span class="advertise-name"><?= $advertise_name ?></span>
    </div>
    <div class="row">
        <div class="col-md-9">
            <table id="tblAdvertiseDetail" class="table table-striped table-bordered table-responsive">
                <thead>
                <tr class="bg-default">
                    <th class="text-center">응모권 번호</th>
                    <th class="text-center">응모권 받은 회원</th>
                    <th class="text-center">발행 시간</th>
                    <th class="text-center">URL 클릭 여부</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="col-md-3">
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td class="td-label">일자</td>
                    <td>
                        <input type="text" class="form-control" value="<?= $visit_day ?>" id="a_day"/>
                    </td>
                </tr>
                <tr>
                    <td class="td-label">노출회수</td>
                    <td><?= $visit_cnt ?>회</td>
                </tr>
                <tr>
                    <td class="td-label">URL 클릭 수</td>
                    <td><?= $click_cnt ?>회</td>
                </tr>
                </tbody>
            </table>
            <button class="btn btn-info pull-right" onclick="redrawTable()">조회</button>
        </div>
    </div>

</div>

<script>
    var tblStat;

    $(function () {

        $('#a_day').datepicker({
            "autoclose": true,
            "format": 'yy.mm.dd',
            "todayBtn": true,
            "todayHighlight": true
        });

        tblStat = $('#tblAdvertiseDetail').DataTable({
            "language": {
                "emptyTable": "데이터가 없습니다.",
                "info": "표시 _START_ - _END_ 항목중 _TOTAL_ 항목",
                "infoEmpty": "",
                "search": "<span class='info-label'>Search : </span>",
                "lengthMenu": "<span class='info-label'>Show : </span> _MENU_",
                "paginate": {
                    "previous": "이전",
                    "next": "다음",
                    "last": "마지막",
                    "first": "처음"
                }
            },
            "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row flex'<'col-md-12'p>>",
            "pagingType": "bootstrap_full_number",
            "bStateSave": false,

            "bLengthChange": true,
            "aLengthMenu": [10, 50, 100],
            "bFilter": true,
            "bInfo": false,
            "bSort": false,
            "processing": false,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo base_url('advertise/detail_table')?>",
                "type": "GET",
                "beforeSend": function () {
                    showBlockUI($('#pageContent'));
                },
                "dataSrc": (results) => {
                    // $('h4.inline').text('전체 광고수 : ' + results.memberCnt + ' 개');
                    return results.data;
                },
                "data": (data) => {
                    // data.column = $('#column').val();
                    // data.keyword = $('#keyword').val();
                    data.advertise_id = '<?=$id?>';
                    data.visit_day = $('#a_day').val();
                },
                "error": () => {
                    hideBlockUI($('#pageContent'));
                }
            },
            "drawCallback": function () {
                hideBlockUI($('#pageContent'));
            },
            "createdRow": function (row, data, dataIndex) {
                hideBlockUI($('#pageContent'));

            }
        });
    });

    function redrawTable() {
        if (tblStat != null) {
            tblStat.draw();
        }
    }
</script>