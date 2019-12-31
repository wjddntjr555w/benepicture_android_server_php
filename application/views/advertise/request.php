<div class="row">
    <div class="col-md-12">
        <table id="tblRequest" class="table table-striped table-bordered table-responsive">
            <thead>
            <tr class="bg-default">
                <th class="text-center">ID</th>
                <th class="text-center">번호</th>
                <th class="text-center">형식</th>
                <th class="text-center">기간</th>
                <th class="text-center">상품명</th>
                <th class="text-center">URL</th>
                <th class="text-center">횟수</th>
                <th class="text-center">파일</th>
                <th class="text-center">승낙</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="col-md-12">
        <div class="input-group" style="float: right;width: 450px">
            <span class="input-group-btn">
                <button class="btn" style="background: #26C281" type="button">APP 내 광고신청 객단가 설정(단위:원)</button>
            </span>
            <input type="number" class="form-control" id="b_app_cost" value="<?php echo $cost ?>">
            <span class="input-group-btn">
                <button class="btn blue" type="button" onclick="saveCost()">변경</button>
            </span>
        </div>
    </div>
</div>

<script>
    $(function () {

        $('.btn-search').on('click', () => {
            table.draw();
        });

        table = $('#tblRequest').DataTable({
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
                "url": "<?php echo base_url('advertise/request_table')?>",
                "type": "GET",
                "beforeSend": function () {
                    showBlockUI($('#pageContent'));
                },
                "dataSrc": (results) => {
                    $('h4.inline').text('전체 광고수 : ' + results.memberCnt + ' 개');
                    return results.data;
                },
                "data": (data) => {
                    data.column = $('#column').val();
                    data.keyword = $('#keyword').val();
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

                var btnEdit = $('<a class="btn btn-primary">다운로드</a>')
                    .bind('click', data, function (e) {
                        download(e.data);
                    });

                $('td:eq(7)', row).html(btnEdit);
                $('td:eq(8)', row).html('');
                $('td:eq(8)', row).append('<a class="text-danger" onclick="changeStatus(<?=STATUS_DENY?>,' + data['idx'] + ')">거절</a>');
                $('td:eq(8)', row).append('&nbsp;&nbsp;');
                $('td:eq(8)', row).append('<a class="text-primary" onclick="changeStatus(<?=STATUS_ALLOW?>,' + data['idx'] + ')">승인</a>');
            }
        });

    });

    function download(_obj) {
        go_to_url('<?php echo base_url('Advertise/download')?>', {file_name: _obj['file']}, 'GET');
    }

    function changeStatus(status, id) {
        $.ajax({
            url: '<?=base_url("advertise/change_status")?>',
            type: 'post',
            data: {
                id: id,
                status: status
            },
            success: function (result) {
                if (result == 'success') {
                    showToast('success', status ==<?=STATUS_ALLOW?>? '승인' : '거절' + '되었습니다', '알림');
                    table.draw();
                } else {
                    showToast('error', serverErrMsg(), '알림');
                }
            }
        })
    }

    function saveCost() {
        $.ajax({
            url: '<?=base_url("advertise/save_cost")?>',
            type: 'post',
            data: {
                cost: $('#b_app_cost').val()
            },
            success: function (result) {
                if (result == 'success') {
                    showToast('success', '저장되었습니다', '알림');
                } else {
                    showToast('error', serverErrMsg(), '알림');
                }
            }
        })
    }
</script>