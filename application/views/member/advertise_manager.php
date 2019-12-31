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

    .select2-container {
        width: 100% !important;
        margin-bottom: 10px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <table id="tblManager" class="table table-striped table-bordered table-responsive">
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

<div id="modalManager" class="modal fade" tabindex="-1" data-backdrop="static"
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
                                <td><input type="text" name="Member[f_id]" id="b_id"></td>
                                <td style="background: white" colspan="2">계좌정보</td>
                            </tr>
                            <tr>
                                <td>이름</td>
                                <td><input type="text" name="Member[f_name]" id="b_name"></td>
                                <td>계좌인증</td>
                                <td>
                                    <select class="form-control" id="b_account_status" name="Member[f_account_status]">
                                        <option value="<?= STATUS_TRUE ?>">인증</option>
                                        <option value="<?= STATUS_FALSE ?>">미인증</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>성별</td>
                                <td>
                                    <select class="form-control" id="b_gender" name="Member[f_gender]">
                                        <option value="M">남</option>
                                        <option value="F">여</option>
                                    </select>
                                </td>
                                <td>이름</td>
                                <td><input type="text" name="Member[f_depositor]" id="b_depositor"></td>
                            </tr>
                            <tr>
                                <td>생년월일</td>
                                <td><input type="text" name="Member[f_birthday]" id="b_birthday"></td>
                                <td>은행명</td>
                                <td><input type="text" name="Member[f_bank]" id="b_bank"></td>
                            </tr>
                            <tr>
                                <td>연락처</td>
                                <td><input type="text" name="Member[f_phone]" id="b_phone"></td>
                                <td>계좌번호</td>
                                <td><input type="text" name="Member[f_account]" id="b_account"></td>
                            </tr>
                            <tr>
                                <td>비밀번호</td>
                                <td><input type="password" name="Member[f_pwd]" autocomplete="false" id="b_pwd"></td>

                                <td>연락처</td>
                                <td><input type="text" name="Member[f_depositor_phone]" id="b_depositor_phone"></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="td-textarea">
                                    <textarea class="form-control" id="b_message" placeholder="메시지를 작성해주세요."
                                              rows="3"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" class="td-textarea">
                                    <a class="col-md-offset-8 btn purple" onclick="changeToCommon()">일반회원으로</a>
                                    <a class="btn btn-primary pull-right" onclick="sendMessage()">메시지 보내기</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
                <div class="form-body">
                    <div class="col-md-12" style="padding: 0;">
                        <div class="margin-bottom-10">광고 매칭 시키기</div>

                        <div class="input-group select2-bootstrap-prepend">
                                <span class="input-group-btn">
                                    <button class="btn yellow" type="button"
                                            data-select2-open="multi-prepend"> 광고매칭 </button>
                                </span>
                            <select id="b_matching_advertise" name="Member[matched_advertise]"
                                    class="form-control select2" multiple>
                                <?php
                                if (!is_null($advertise_list) && _is_array($advertise_list)) {
                                    foreach ($advertise_list as $list) {
                                        ?>
                                        <option value="<?= $list->f_idx ?>"><?= $list->f_name ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                            <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary"
                                            onclick="matching_advertise()">매칭하기</button>
                                </span>
                        </div>
                        <div class="col-md-12" style="padding: 0;margin:10px 0;">
                            <span style="padding-left: 10px;color: red;height: 34px;line-height: 34px">회원정보 수정을 위해서는 반드시 변경 버튼을 눌러 수정한 후,저장을 눌러 업데이트해야합니다.</span>
                            <div class="pull-right">
                                <button class="btn " type="button" data-dismiss="modal">취소</button>
                                <a class="btn btn-primary" onclick="saveMemberInfo()">저장</a>
                            </div>

                        </div>
                    </div>
                    <form role="form" class="form-horizontal" id="frmMemberCompany" method="post"
                          enctype="multipart/form-data"
                          action="<?php echo base_url('Member/save_info') ?>">
                        <input type="hidden" id="b_idx_" name="Member[f_idx]" value="0"/>
                        <table class="tbl-main-info table-bordered margin-top-10">
                            <tbody>
                            <tr>
                                <td colspan="2" style="background: white">사업자 정보</td>
                                <td style="padding: 0 20px;background: #E7EAEB;">주소</td>
                                <td style="padding: 0"><input type="text" name="Member[f_addr]" id="b_addr"></td>
                            </tr>
                            <tr>
                                <td>상호명</td>
                                <td><input type="text" name="Member[f_company_name]" id="b_company_name"></td>

                                <td>업종</td>
                                <td><input type="text" name="Member[f_business]" id="b_business"></td>

                            </tr>
                            <tr>
                                <td>대표자</td>
                                <td><input type="text" name="Member[f_company_owner]" id="b_company_owner"></td>

                                <td>업태</td>
                                <td><input type="text" name="Member[f_business_type]" id="b_business_type"></td>

                            </tr>
                            <tr>
                                <td>사업자번호</td>
                                <td><input type="text" name="Member[f_owner_phone]" id="b_owner_phone"></td>

                                <td>이메일</td>
                                <td><input type="text" name="Member[f_email]" id="b_email"></td>

                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="saveMemberCompany()" class="btn btn-primary">저장</button>
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

        $('#b_matching_advertise').select2();

        $('#b_birthday').datepicker({
            "autoclose": true,
            "format": 'yyyy.mm.dd',
            "todayBtn": true,
            "todayHighlight": true,
        });

        table = $('#tblManager').DataTable({
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
                    data.user_type = <?=USER_ADVERTISE?>;
                    data.user_status = <?=USER_NORMAL?>;
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

    function showDetail(member) {
        $('#b_userNO').text(member === null ? '' : member['no']);

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

        $('#b_idx_').val(member === null ? '' : member['idx']);
        $('#b_company_name').val(member === null ? '' : member['company_name']);
        $('#b_company_owner').val(member === null ? '' : member['company_owner']);
        $('#b_owner_phone').val(member === null ? '' : member['owner_phone']);
        $('#b_addr').val(member === null ? '' : member['addr']);
        $('#b_business').val(member === null ? '' : member['business']);
        $('#b_business_type').val(member === null ? '' : member['business_type']);
        $('#b_email').val(member === null ? '' : member['email']);

        $('#b_matching_advertise').val('').select2();
        $('#modalManager').modal();
    }

    function saveMemberInfo() {
        if ($('#b_id').val() === '') {
            showToast('info', '아이디를 입력해주세요', '알림');
            return;
        }
        if ($('#b_name').val() === '') {
            showToast('info', '이름을 입력해주세요', '알림');
            return;
        }
        if ($('#b_birthday').val() === '') {
            showToast('info', '생년월일을 입력해주세요', '알림');
            return;
        }
        if ($('#b_phone').val() === '') {
            showToast('info', '연락처를 입력해주세요', '알림');
            return;
        }

        $('#frmMemberInfo').ajaxSubmit({
            resetForm: false,
            clearForm: false,
            success: saveCallback
        });
    }

    function saveCallback(response) {
        if ($.trim(response) === 'success') {
            showToast('success', '저장되었습니다', '알림');
            table.draw();
            $('#modalManager').modal('hide');
        } else {
            showToast('error', serverErrMsg(), '알림');
        }
    }

    function saveMemberCompany() {
        $('#frmMemberCompany').ajaxSubmit({
            resetForm: false,
            clearForm: false,
            success: saveCallback
        });
    }

    function changeToCommon() {

        var id = $('#b_idx').val();
        showConfirm(CONFIRM_TO_COMMON_USER, function () {
            $.ajax({
                url: "<?=base_url('Member/change_type')?>",
                type: 'post',
                data: {id: id, type:<?=USER_COMMON?>},
                success: function (result) {
                    if (result.trim() === 'success') {
                        showToast('success', '일반회원으로 되었습니다.', '알림');
                        table.draw();
                        $('#modalManager').modal('hide');
                    } else
                        showToast('error', serverErrMsg(), '알림');
                }
            })
        })
    }

    function sendMessage() {
        var message = $.trim($('#b_message').val());
        var id = $('#b_idx').val();

        if (message === '') {
            showToast('warning', '메시지를 자성해주세요.', '알림');
            return false;
        }

        $.ajax({
            url: "<?=base_url('Member/send_message')?>",
            type: 'post',
            data: {id: id, message: message},
            success: function (result) {
                if (result.trim() === 'success') {
                    showToast('success', '메시지를 전송하였습니다.', '알림');
                } else
                    showToast('error', serverErrMsg(), '알림');
            }
        })
    }

    function matching_advertise() {
        var advertise = $('#b_matching_advertise').val();
        var id = $('#b_idx').val();

        if (typeof advertise === 'undefined' || advertise == null || advertise.length == 0) {
            showToast('warning', '광고를 선택해 주세요.', '알림');
            return false;
        }

        $.ajax({
            url: "<?=base_url('Member/matching_advertise')?>",
            type: 'post',
            data: {id: id, advertise: advertise},
            success: function (result) {
                if (result.trim() === 'success') {
                    showToast('success', '광고매칭하였습니다.', '알림');
                    setTimeout('location.reload()', 1000);
                } else
                    showToast('error', serverErrMsg(), '알림');
            }
        })

    }

</script>
