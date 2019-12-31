<style>
    .info-table tr td:first-child {
        padding: 10px 10px;
        background: #E7EAEB;
        width: 40%;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <table id="tblWithdraw" class="table table-striped table-bordered table-responsive">
            <thead>
            <tr class="bg-default">
                <th class="text-center">ID</th>
                <th class="text-center">번호</th>
                <th class="text-center">출금신청금액</th>
                <th class="text-center">출금신청은행</th>
                <th class="text-center">계좌번호</th>
                <th class="text-center">출금처리여부</th>
                <th class="text-center">체크</th>
                <th class="text-center">상세설정</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="col-md-12">
        <div class="btn-group pull-right">
            <button class="btn btn-danger" id="btn_check_all" data-status="<?= STATUS_FALSE ?>">전체 체크</button>
            <button type="button" class="btn yellow" id="btn_change_status">체크회원 일괄 완료처리 변경</button>
        </div>
    </div>
</div>

<div id="modalWithdraw" class="modal fade" tabindex="-1" data-backdrop="static"
     data-keyboard="false">
    <div class="modal-dialog" style="width: 60%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true"></button>
                <h4 class="modal-title">출금신청 정보</h4>
            </div>
            <div class="modal-body">
                <form role="form" class="form-horizontal">
                    <input type="hidden" id="idx" value="0">
                    <div class="form-body padding-tb-20">
                        <div class="col-md-6 col-sm-12">
                            <table class="table table-bordered info-table">
                                <tbody>
                                <tr>
                                    <td>이름</td>
                                    <td id="b_user_name1"></td>
                                </tr>
                                <tr>
                                    <td>응모권</td>
                                    <td id="b_answer"></td>
                                </tr>
                                <tr>
                                    <td>은행</td>
                                    <td id="b_bank"></td>
                                </tr>
                                <tr>
                                    <td>계좌번호</td>
                                    <td id="b_account"></td>
                                </tr>
                                <tr>
                                    <td>출금금액</td>
                                    <td id="b_cost"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <table class="table table-bordered info-table">
                                <tbody>
                                <tr>
                                    <td>이름</td>
                                    <td id="b_user_name2"></td>
                                </tr>
                                <tr>
                                    <td>생년월일</td>
                                    <td id="b_birthday"></td>
                                </tr>
                                <tr>
                                    <td>ID</td>
                                    <td id="b_user_id"></td>
                                </tr>
                                <tr>
                                    <td>휴대폰번호</td>
                                    <td id="b_user_phone"></td>
                                </tr>
                                <tr>
                                    <td>성별</td>
                                    <td>
                                        <div class="md-radio">
                                            <input type="radio" id="radio-gender-1" name="radio_g" disabled
                                                   data-value="<?= TARGET_GENDER_MALE ?>"
                                                   class="md-radiobtn radio_gender">
                                            <label for="radio-gender-1">
                                                <span class="inc"></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 남자 </label>
                                        </div>
                                        <div class="md-radio has-error">
                                            <input type="radio" id="radio-gender-2" name="radio_g" disabled
                                                   data-value="<?= TARGET_GENDER_FEMALE ?>"
                                                   class="md-radiobtn radio_gender">
                                            <label for="radio-gender-2">
                                                <span class="inc"></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 여자 </label>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group form-md-radios text-center">
                            <div class="md-radio-inline">
                                <a class="btn btn-fit-height grey-salt">출금처리여부</a>
                                <div class="md-radio">
                                    <input type="radio" id="radio-1" name="radio_s" class="md-radiobtn radio_status"
                                           data-value="<?= STATUS_FALSE ?>">
                                    <label for="radio-1">
                                        <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span> 대기중 </label>
                                </div>
                                <div class="md-radio has-error">
                                    <input type="radio" id="radio1-2" name="radio_s" class="md-radiobtn radio_status"
                                           data-value="<?= STATUS_TRUE ?>">
                                    <label for="radio1-2">
                                        <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span> 완료 </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark" data-dismiss="modal">취소</button>
                <button type="button" onclick="saveWithdrawStatus()" class="btn green">저장</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('.btn-search').on('click', () => {
            table.draw();
        });

        table = $('#tblWithdraw').DataTable({
            "language": {
                "emptyTable": "데이터가 없습니다.",
                "zeroRecords": "데이터가 없습니다.",
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
                "url": "<?php echo base_url('Withdraw/table')?>",
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
                $('td:eq(5)', row).html(data['status'] == '<?=STATUS_TRUE?>' ? '<span class="text-primary">완료</span>' : '<span class="text-danger">대기중</span>');
                $('td:eq(6)', row).html('');

                if (Number(data['status']) !== 1) {
                    $('td:eq(6)', row).html('<div class="md-checkbox" style="display: table;margin: auto">\n' +
                        '                                                    <input type="checkbox" data-id="' + data['idx'] + '" id="checkbox_' + data['idx'] + '" class="md-check chk_status">\n' +
                        '                                                    <label for="checkbox_' + data['idx'] + '">\n' +
                        '                                                        <span class="inc"></span>\n' +
                        '                                                        <span class="check"></span>\n' +
                        '                                                        <span class="box"></span></label>\n' +
                        '                                                </div>');
                }

                var btnEdit = $('<a style="font-size: 20px"><i class="fa fa-cog"></i></a>')
                    .bind('click', data, function (e) {
                        showWithdrawEdit(e.data);
                    });

                $('td:eq(7)', row).html(btnEdit);
            }
        });
    });

    $('#btn_check_all').on('click', function () {
        var all_status = $(this).attr('data-status');
        if (all_status == '<?=STATUS_FALSE?>') {
            $('.chk_status').prop('checked', true);
            $(this).text('일괄체크 해제').attr('data-status', '<?=STATUS_TRUE?>');
        } else {
            $('.chk_status').prop('checked', false);
            $(this).text('일괄체크').attr('data-status', '<?=STATUS_FALSE?>');
        }
    });

    $('#btn_change_status').on('click', function () {
        var data_arr = [];
        $('.chk_status').each(function () {
            if ($(this).is(':checked')) {
                data_arr.push($(this).attr('data-id'));
            }
        });
        var data_str = data_arr.join(',');

        if (data_str == '') {
            showToast('warning', '회원을 선택하세요.', '알림');
            return false;
        }

        $.ajax({
            url: "<?=base_url('withdraw/change_status_all')?>",
            data: {id_str: data_str},
            type: 'post',
            success: function (result) {
                if (result == 'success') {
                    showToast('success', '완료처리되였습니다.', '알림');
                    table.draw();
                } else
                    showToast('error', serverErrMsg(), '알림');
            }
        })
    });

    function showWithdrawEdit(withdraw) {
        $('#b_user_name1').text(withdraw['name']);
        $('#b_user_name2').text(withdraw['user_name']);
        $('#b_answer').text(withdraw['ad_name'] + '-' + withdraw['answer_no']);
        $('#b_birthday').text(withdraw['user_birthday']);
        $('#b_bank').text(withdraw['bank']);
        $('#b_user_id').text(withdraw['user_id']);
        $('#b_account').text(withdraw['account']);
        $('#b_user_phone').text(withdraw['user_phone']);
        $('#b_cost').text(withdraw['cost']);
        $('#idx').val(withdraw['idx']);

        $('input[data-value=' + withdraw['user_gender'] + ']').prop('checked', true);
        $('input[data-value=' + withdraw['status'] + ']').prop('checked', true);

        $('#modalWithdraw').modal();
    }

    function saveWithdrawStatus() {
        var idx = $('#idx').val();
        var status = 0;

        $('.radio_status').each(function () {
            if ($(this).is(':checked'))
                status = $(this).attr('data-value');
        });

        $.ajax({
            url: "<?=base_url('withdraw/change_status')?>",
            data: {idx: idx, status: status},
            type: 'post',
            success: function (result) {
                if (result == 'success') {
                    showToast('success', '저장되었습니다.', '알림');
                    $('#modalWithdraw').modal('hide');
                    table.draw();
                } else
                    showToast('error', serverErrMsg(), '알림');
            }
        })
    }
</script>