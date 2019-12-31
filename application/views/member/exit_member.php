<style>
    .pagination {
        float: right !important;
    }

    .info-label {
        height: 28px;
        line-height: 27px;
        display: inline-block;
        padding: 0 10px;
        background: #493D55;
        color: white;
        margin-right: -6px;
    }

    .color-bene:hover, .color-bene {
        color: #fff;
        background-color: #16A085;
        /*border-color: #16896d;*/
    }

    .border-r-0 {
        border-radius: 0 !important;
    }

    .input-bene {
        height: 33px;
        padding: 6px 12px;
        background-color: #fff;
        border: 1px solid #16A085;
        outline: none;
    }

    .tbl-main-info tr td:first-child, .tbl-main-info tr td:nth-child(2n+3) {
        padding: 0 20px;
        background: #E7EAEB;
    }

    .tbl-main-info {
        margin: auto;
        width: 100%;
    }

    .tbl-main-info td {
        vertical-align: middle;
    }

    .tbl-main-info td > input, .tbl-main-info td > label {
        width: 100%;
        outline: none;
        padding: 0 5px;
        text-align: left;
        border: none;
        height: 34px;
        line-height: 34px;
    }

    .td-btn-change {
        padding: 0 10px;
        background: #999999 !important;
        cursor: pointer;
        color: black;
        text-align: center
    }

    .td-textarea {
        padding: 0 !important;
        background: transparent !important;
    }

    .td-textarea > textarea {
        outline: none;
        border: none;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <table id="tblMember" class="table table-striped table-bordered table-responsive">
            <thead>
            <tr class="bg-default">
                <th class="text-center">유저번호</th>
                <th class="text-center">ID</th>
                <th class="text-center">이름</th>
                <th class="text-center">성별</th>
                <th class="text-center">생년월일</th>
                <th class="text-center">연락처</th>
                <th class="text-center">마케팅동의</th>
                <th class="text-center">계좌인증</th>
                <th class="text-center"></th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div id="modalMember" class="modal fade" tabindex="-1" data-backdrop="static"
     data-keyboard="false">
    <div class="modal-dialog" style="width: 850px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true"></button>
                <h4 class="modal-title"><span class="col-md-2">기본정보</span><span
                            class="col-md-offset-4 col-md-2">유저번호</span><span id="b_userNO">123</span>
                </h4>
            </div>
            <div class="modal-body">
                <form role="form" class="form-horizontal" id="frmMemberInfo" method="post"
                      enctype="multipart/form-data"
                      action="<?php echo base_url('Member/save_info') ?>">

                    <input type="hidden" id="b_idx" name="Member[f_idx]" value="0"/>
                    <div class="form-body padding-tb-20">
                        <table class="tbl-main-info table-bordered">
                            <tbody>
                            <tr>
                                <td>ID</td>
                                <td><input type="text" name="Member[f_id]" id="b_id" disabled></td>
                                <td style="background: white" colspan="2">계좌정보</td>
                            </tr>
                            <tr>
                                <td>이름</td>
                                <td><input type="text" name="Member[f_name]" id="b_name" disabled></td>
                                <td>계좌인증</td>
                                <td>
                                    <select class="form-control" id="b_account_status" name="Member[f_account_status]" disabled>
                                        <option value="<?= STATUS_TRUE ?>">인증</option>
                                        <option value="<?= STATUS_FALSE ?>">미인증</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>성별</td>
                                <td>
                                    <select class="form-control" id="b_gender" name="Member[f_gender]" disabled>
                                        <option value="M">남</option>
                                        <option value="F">여</option>
                                    </select>
                                </td>
                                <td>이름</td>
                                <td><input type="text" name="Member[f_depositor]" id="b_depositor" disabled></td>
                            </tr>
                            <tr>
                                <td>생년월일</td>
                                <td><input type="text" name="Member[f_birthday]" id="b_birthday" disabled></td>
                                <td>은행명</td>
                                <td><input type="text" name="Member[f_bank]" id="b_bank" disabled></td>
                            </tr>
                            <tr>
                                <td>연락처</td>
                                <td><input type="text" name="Member[f_phone]" id="b_phone" disabled></td>
                                <td>계좌번호</td>
                                <td><input type="text" name="Member[f_account]" id="b_account" disabled></td>
                            </tr>
                            <tr>
                                <td>비밀번호</td>
                                <td><input type="password" name="Member[f_pwd]" autocomplete="false" id="b_pwd" disabled></td>

                                <td>연락처</td>
                                <td><input type="text" name="Member[f_depositor_phone]" id="b_depositor_phone" disabled></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="" class="btn default" data-dismiss="modal">확인</button>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    let table;

    $(function () {

        $('.btn-search').on('click', () => {
            table.draw();
        });

        $('#b_birthday').datepicker({
            "autoclose": true,
            "format": 'yyyy.mm.dd',
            "todayBtn": true,
            "todayHighlight": true,
        });

        table = $('#tblMember').DataTable({
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
                "url": "<?php echo base_url('member/table')?>",
                "type": "GET",
                "beforeSend": function () {
                    showBlockUI($('#pageContent'));
                },
                "dataSrc": (results) => {
                    // $('h4.inline').text('전체 회원수 : ' + results.memberCnt + ' 명');
                    return results.data;
                },
                "data": (data) => {
                    // data.column = $('#column').val();
                    // data.keyword = $('#keyword').val();
                    data.user_status = <?=USER_EXIT?>;
                    data.user_type = 0;
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

                var btnEdit = $('<a style="font-size: 20px"><i class="fa fa-cog"></i></a>')
                    .bind('click', data, function (e) {
                        showDetail(e.data);
                    });

                $('td:eq(8)', row).html(btnEdit);
                $('td:eq(3)', row).html(data['gender'] == 'M' ? '남' : '여');
                $('td:eq(6)', row).html(data['market_status'] == '<?=STATUS_TRUE?>' ? '동의' : '미동의');
                $('td:eq(7)', row).html(data['account_status'] == '<?=STATUS_TRUE?>' ? '인증완료' : '미인증');
            }
        });

    });

    // function showDetail(member) {
    //     $('#b_member_id').val(member === null ? '' : member['id']);
    //     $('#b_member_name').val(member === null ? '' : member['name']);
    //     $('#b_member_gender').val(member === null ? '' : member['gender']);
    //     $('#b_member_depositor_phone').val(member === null ? '' : member['depositor_phone']);
    //     $('#b_member_pwd').val(member === null ? '' : member['pwd']);
    //     $('#b_member_birthday').val(member === null ? '' : member['birthday']);
    //     $('#b_userNO').text(member === null ? '' : member['no']);
    //     $('#modalMember').modal();
    // }

    function showDetail(member) {
        $('#b_userNO').text(member === null ? '' : member['idx']);

        $('#b_id').val(member === null ? '' : member['id']);
        $('#b_name').val(member === null ? '' : member['name']);
        $('#b_gender').val(member === null ? '' : member['gender']);
        $('#b_depositor_phone').val(member === null ? '' : member['depositor_phone']);
        $('#b_pwd').val('');
        $('#b_birthday').val(member === null ? '' : member['birthday']);
        $('#b_phone').val(member === null ? '' : member['phone']);
        $('#b_account_status').val(member === null ? '' : member['account_status']);
        $('#b_bank').val(member === null ? '' : member['bank']);
        $('#b_depositor').val(member === null ? '' : member['depositor']);
        $('#b_account').val(member === null ? '' : member['account']);
        $('#b_idx').val(member === null ? '' : member['idx']);

        $('#modalMember').modal();
    }

    function saveMemberInfo() {
        $('#frmMemberInfo').ajaxSubmit({
            resetForm: true,
            clearForm: true,
            success: saveCallback
        });
    }

    function saveCallback(response) {
        if ($.trim(response) === 'success') {
            showToast('success', '저장되었습니다', '알림');
            table.draw();
            $('button[data-dismiss=modal]').trigger('click');
        } else {
            showToast('error', serverErrMsg(), '알림');
        }
    }
</script>
