<style>
    .td-label {
        padding: 0 10px;
        background: #E7EAEB;
    }

    .td-input {
        width: 100%;
        outline: none;
        padding: 0 5px;
        text-align: left;
        border: none;
        height: 34px;
        line-height: 34px;
    }

    .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--multiple, .select2-container--default.select2-container--focus .select2-selection--multiple {
        border: none;
    }

    .select2-selection--single {
        height: 34px !important;
        line-height: 34px !important;
    }

    #td_upload_photo, #td_upload_video {
        text-align: center;
        vert-align: middle;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <table id="tblAdvertise" class="table table-striped table-bordered table-responsive">
            <thead>
            <tr class="bg-default">
                <th class="text-center">광고명</th>
                <th class="text-center">노출회수</th>
                <th class="text-center">타깃성별</th>
                <th class="text-center">연령</th>
                <th class="text-center">1회 노출시 적립금</th>
                <th class="text-center">관리자적립금</th>
                <th class="text-center">광고유형</th>
                <th class="text-center">상태</th>
                <th class="text-center"></th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div id="modalAdvertise" class="modal fade" data-backdrop="static"
     data-keyboard="false">
    <div class="modal-dialog" style="width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true"></button>
                <h4 class="modal-title text-center">광고 신규 등록
                </h4>
            </div>
            <form role="form" class="form-horizontal" id="frmAdvertise" method="post"
                  enctype="multipart/form-data"
                  action="<?php echo base_url('Advertise/save') ?>">
                <div class="modal-body">

                    <input type="hidden" id="b_id" name="advertise[f_idx]" value="0"/>
                    <div class="form-body padding-tb-20">
                        <div style="margin-top: 10px;margin-bottom: 10px">등록정보설정</div>
                        <table class="tbl-main-info table-bordered" width="100%">
                            <tbody>
                            <tr>
                                <td class="td-label" style="width: 15%">광고명</td>
                                <td style="width: 35%">
                                    <input type="text" placeholder="광고명을 입력해주세요." class="form-control"
                                           name="advertise[f_name]" id="b_name" required
                                           style="outline: none;border: none">
                                </td>
                                <td colspan="2">광고 유형 설정</td>
                            </tr>
                            <tr>
                                <td class="td-label">노출회수</td>
                                <td style="width: 40%">
                                    <input type="number" placeholder="노출회수를 입력해주세요.(1이상 양수)" class="form-control"
                                           name="advertise[f_visible_cnt]" id="b_visible_cnt" required
                                           style="outline: none;border: none" oninput="calcExpectCost()">
                                </td>
                                <td class="td-label" style="width: 15%">광고 유형</td>
                                <td style="width: 35%;padding-left: 10px">
                                    <div class="md-radio-inline">
                                        <div class="md-radio">
                                            <input type="radio" id="advertise_game" name="advertise[f_type]"
                                                   value="G"
                                                   class="icheck-colors radio-advertise-type">
                                            <label for="advertise_game">
                                                <span class="inc"></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 게임 </label>
                                        </div>
                                        <div class="md-radio">
                                            <input type="radio" id="advertise_movie" name="advertise[f_type]"
                                                   value="V"
                                                   class="md-radiobtn radio-advertise-type">
                                            <label for="advertise_movie">
                                                <span class="inc"></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 동영상 </label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-label">1회 적립금</td>
                                <td style="width: 40%">
                                    <input type="number" placeholder="1회 노출시 상금적립금(숫자)" class="form-control"
                                           id="b_visible_cost" name="advertise[f_visible_cost]" required
                                           step="0.01"
                                           style="outline: none;border: none" oninput="calcExpectCost()">
                                </td>
                                <td class="td-label">게임 유형</td>
                                <td style="padding-left: 10px">
                                    <div class="md-radio-inline">
                                        <div class="md-radio">
                                            <input type="radio" id="puzzle" name="advertise[f_game_type]" value="S"
                                                   class="icheck-colors radio-game-type">
                                            <label for="puzzle">
                                                <span class="inc"></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 스위칭퍼즐 </label>
                                        </div>
                                        <div class="md-radio">
                                            <input type="radio" id="match" name="advertise[f_game_type]" value="P"
                                                   class="md-radiobtn radio-game-type">
                                            <label for="match">
                                                <span class="inc"></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 짝맞추기 </label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-label">관리자적립금</td>
                                <td style="width: 40%">
                                    <input type="number" placeholder="1회 노출시 관리자 적립금(숫자)" class="form-control"
                                           id="b_admin_cost" name="advertise[f_admin_cost]" required step="0.01"
                                           style="outline: none;border: none" oninput="calcExpectCost()">
                                </td>
                                <td class="td-label">사진 업로드</td>
                                <td id="td_upload_photo">
                                    <a class="btn btn-primary" style="width: 100%" id="btn_upload_photo"
                                       onclick="upload_photo()">파일을 올려주세요</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-label">예상 총 비용</td>
                                <td style="width: 40%">
                                    <input type="text" placeholder="노출횟수*(1회적립금+관리자적립금)" class="form-control"
                                           id="b_expect_cost" readonly
                                           style="outline: none;border: none">
                                </td>
                                <td class="td-label">영상 업로드</td>
                                <td id="td_upload_video">
                                    <a class="btn btn-primary" style="width: 100%" id="btn_upload_video"
                                       onclick="upload_video()">파일을 올려주세요</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-label">링크설정</td>
                                <td style="width: 40%">
                                    <input type="url" class="form-control" placeholder="이동 url을 설정해주세요."
                                           name="advertise[f_link]" required
                                           style="outline: none;border: none" id="b_link">
                                </td>
                                <td class="td-label">등록기간</td>
                                <td>
                                    <div class="input-group input-large date-picker input-daterange"
                                         style="width: 100%!important;"
                                         data-date="<?= date('Y-m-d') ?>" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control" name="advertise[f_from]" id="b_from"
                                               required>
                                        <span class="input-group-addon"> ~ </span>
                                        <input type="text" class="form-control" name="advertise[f_to]" id="b_to"
                                               required>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div style="margin-top: 10px;margin-bottom: 10px">타깃 설정</div>
                        <table class="tbl-main-info table-bordered" width="100%">
                            <tbody>
                            <tr>
                                <td style="width: 15%" class="td-label">성별</td>
                                <td colspan="3" style="padding-left: 10px">
                                    <div class="md-radio-inline">
                                        <div class="md-radio">
                                            <input type="radio" id="target_male" name="advertise[f_target_gender]"
                                                   value="M"
                                                   class="icheck-colors radio-gender-type">
                                            <label for="target_male">
                                                <span class="inc"></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 남자 </label>
                                        </div>
                                        <div class="md-radio">
                                            <input type="radio" id="target_female" name="advertise[f_target_gender]"
                                                   value="F"
                                                   class="md-radiobtn radio-gender-type">
                                            <label for="target_female">
                                                <span class="inc"></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 여자 </label>
                                        </div>
                                        <div class="md-radio">
                                            <input type="radio" id="target_all" name="advertise[f_target_gender]"
                                                   value="N"
                                                   class="md-radiobtn radio-gender-type">
                                            <label for="target_all">
                                                <span class="inc"></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 상관없음 </label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 15%" class="td-label">연령</td>
                                <td colspan="3" style="padding-left: 10px">
                                    <div class="md-radio-inline">
                                        <div class="md-radio">
                                            <input type="radio" id="no_setting" name="radio-target-age"
                                                   class="icheck-colors radio-target-age">
                                            <label for="no_setting">
                                                <span class="inc"></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 상관없음 </label>
                                        </div>
                                        <div class="md-radio">
                                            <input type="radio" id="have_setting" name="radio-target-age"
                                                   class="md-radiobtn radio-target-age">
                                            <label for="have_setting">
                                                <span class="inc"></span>
                                                <span class="check"></span>
                                                <span class="box"></span> 설정 </label>
                                        </div>
                                        <div class="input-group input-large"
                                             style="display: inline-flex;">
                                            <select class="form-control" id="b_age_from" name="advertise[f_age_from]">
                                                <option value="">선택</option>
                                                <?php
                                                for ($i = 1920; $i <= date('Y'); $i += 1) {
                                                    ?>
                                                    <option value="<?= $i ?>"><?= $i ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <span class="input-group-addon"> ~ </span>
                                            <select class="form-control" id="b_age_to" name="advertise[f_age_to]">
                                                <option value="">선택</option>
                                                <?php
                                                for ($j = 1920; $j <= date('Y'); $j += 1) {
                                                    ?>
                                                    <option value="<?= $j ?>"><?= $j ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-label">키워드 설정</td>
                                <td colspan="3">
                                    <input type="hidden" name="advertise[f_keyword]" id="b_keyword_val"/>
                                    <select id="b_keyword" class="form-control select2" multiple>
                                        <?php
                                        if ($keyword_list) {
                                            foreach ($keyword_list as $list) {
                                                ?>
                                                <option value="<?= $list->f_idx ?>"><?= $list->f_name ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div style="margin-top: 10px;margin-bottom: 10px">광고주 매칭</div>
                        <div class="col-md-12" style="padding: 0;">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <label class="btn yellow" type="button">광고주 매칭</label>
                                </span>
                                <select class="form-control select2" id="b_user" name="advertise[f_user]">
                                    <option value="0"></option>
                                    <?php
                                    if ($user_list) {
                                        foreach ($user_list as $list) {
                                            ?>
                                            <option value="<?= $list->f_idx ?>"><?= $list->f_name ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="file" id="b_upload_video" accept="video/mp4" name="video"
                           class="col-md-8 form-control hidden">
                    <input type="file" id="b_upload_photo" accept="image/jpeg, image/pjpeg" name="image"
                           class="col-md-8 form-control hidden">
                    <input type="hidden" id="b_media" name="advertise[f_media]" value=""/>


                </div>
                <div class="modal-footer">
                    <div class="pull-left">
                        <div class="input-group input-large">
                            <span class="input-group-btn" id="sp_pause">
                                <button class="btn purple" type="button" onclick="doPause()">일시 정지</button>
                            </span>
                            <span class="input-group-btn sp_status">
                                <button class="btn blue" type="button">광고상태</button>
                            </span>
                            <label class="form-control sp_status text-left" id="b_status">일시정지</label>
                        </div>
                    </div>
                    <button type="button" onclick="" class="btn default" data-dismiss="modal">취소</button>
                    <button type="submit" class="btn green">저장</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {

        // $('.btn-search').on('click', () => {
        //     table.draw();
        // });

        $('#b_keyword').select2();
        $('#b_user').select2();

        $('.page-bar-btn').text('신규 등록').show().bind('click', '', function (e) {
            showAdvertiseEdit(null);
        });

        table = $('#tblAdvertise').DataTable({
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
                "url": "<?php echo base_url('advertise/table')?>",
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

                var btnEdit = $('<a style="font-size: 20px"><i class="fa fa-cog"></i></a>')
                    .bind('click', data, function (e) {
                        showAdvertiseEdit(e.data);
                    });

                $('td:eq(8)', row).html(btnEdit);
                $('td:eq(2)', row).html(data['target_gender'] == 'M' ? '남' : (data['target_gender'] == 'F' ? '여' : '상관없음'));
                $('td:eq(6)', row).html(data['type'] == '<?=ADVERTISE_GAME?>' ? '게임' : '동영상');
                //$('td:eq(7)', row).html(data['account_status'] == '<?//=STATUS_TRUE?>//' ? '인증완료' : '미인증');
            }
        });

    });

    function showAdvertiseEdit(advertise) {
        $('#b_id').val(advertise === null ? 0 : advertise['id']);
        $('#b_name').val(advertise === null ? '' : advertise['name']);
        $('#b_visible_cnt').val(advertise === null ? '' : advertise['visible_cnt']);
        $('input.radio-advertise-type[value="G"]').prop('checked', true);
        $('#b_visible_cost').val(advertise === null ? '' : advertise['visible_cost']);
        $('input.radio-game-type[value="S"]').prop('checked', true);
        $('#b_admin_cost').val(advertise === null ? '' : advertise['admin_cost']);
        $('#b_expect_cost').val(advertise === null ? 0 : (advertise['admin_cost'] * 1 + advertise['visible_cost'] * 1) * advertise['visible_cnt']);
        $('#b_link').val(advertise === null ? '' : advertise['link']);
        $('#b_from').val(advertise === null ? '' : advertise['from']);
        $('#b_to').val(advertise === null ? '' : advertise['to']);
        $('input.radio-gender-type[value="N"]').prop('checked', true);
        $('#no_setting').prop('checked', true);
        $('#b_age_from').val(advertise === null ? '' : advertise['age_from']);
        $('#b_age_to').val(advertise === null ? '' : advertise['age_to']);
        $('#b_user').val(advertise === null ? '' : advertise['user']);
        $('#b_keyword').val(advertise === null ? '' : advertise['keyword']).select2();
        $('#b_keyword_val').val(advertise === null ? '' : advertise['keyword']);

        if (advertise != null) {
            $('input.radio-advertise-type[value="' + advertise['type'] + '"]').prop('checked', true);
            $('input.radio-game-type[value="' + advertise['game_type'] + '"]').prop('checked', true);
            $('input.radio-gender-type[value="' + advertise['target_gender'] + '"]').prop('checked', true);

            if (advertise['media'] !== '') {
                $('#b_media').val(advertise['media']);

                if (advertise['type'] === 'G') {
                    $('#td_upload_photo').html('<img class="thumbnail" style="display: inline-flex;margin-bottom: 0" onclick="upload_photo()" width="250px" height="250px" src="' + advertise['media_url'] + '">');
                } else {
                    $('#td_upload_video').html('<a class="btn btn-primary" style="width: 100%" id="btn_upload_video"\n' +
                        '                                       onclick="upload_video()">파일을 올려주세요</a>');
                }
            }

            if (advertise['age_from'] !== '' && advertise['age_to'] !== '') {
                $('#have_setting').prop('checked', true);
            } else {
                $('#no_setting').prop('checked', true);
            }

            $('.modal-footer >.pull-left').show();

            $('#sp_pause').show();
            if (Number(advertise['f_status']) === <?=ADVERTISE_NORMAL?>)
                $('#sp_pause').find('button').text('일시 정지');
            else
                $('#sp_pause').find('button').text('정지 해제');

            $('#b_status').text('').text(advertise['status']);
        } else {
            $('.modal-footer >.pull-left').hide();
            $('#b_media').val('');
            $('#td_upload_photo').html('<a class="btn btn-primary" style="width: 100%" id="btn_upload_photo"\n' +
                '                                       onclick="upload_photo()">파일을 올려주세요</a>');
            $('#td_upload_video').html('<a class="btn btn-primary" style="width: 100%" id="btn_upload_video"\n' +
                '                                       onclick="upload_video()">파일을 올려주세요</a>');
        }

        $('#modalAdvertise').modal('show');
    }

    function upload_photo() {
        if ($('.radio-advertise-type:checked').val() === "V") {
            showToast('info', '광고유형이 게임일시 사진등록이 가능합니다.', '알림');
            return;
        }
        $('#b_upload_photo').trigger('click');
    }


    $('#b_upload_photo').on('change', function (e) {
        var file = e.target.files[0];

        if (file) {
            var reader = new FileReader();
            var image = new Image();

            reader.readAsDataURL(file);
            reader.onload = function (_file) {
                image.src = _file.target.result;
                image.onload = function () {
                    $('#td_upload_photo').html('<img class="thumbnail" style="display: inline-flex;margin-bottom: 0" onclick="upload_photo()" width="250px" height="250px" src="' + image.src + '">');
                };
                image.onerror = function () {
                    showToast('error', '파일형식이 올바르지 않습니다 : ' + file.type, '알림');
                    $(e).val("");
                };
            };
        }
    });

    function upload_video() {
        if ($('.radio-advertise-type:checked').val() === "G") {
            showToast('info', '광고유형이 동영상일시 영상등록이 가능합니다.', '알림');
            return;
        }

        $('#b_upload_video').trigger('click');
    }

    // $('#b_upload_video').on('change', function (e) {
    //     var file = e.target.files[0];
    //
    //     if (file) {
    //         var reader = new FileReader();
    //         var image = new Image();
    //
    //         reader.readAsDataURL(file);
    //         reader.onload = function (_file) {
    //             image.src = _file.target.result;
    //             image.onload = function () {
    //                 $('#td_upload_video').html('<img class="thumbnail" style="display: inline-flex;margin-bottom: 0" onclick="upload_video()" width="250px" height="250px" src="' + image.src + '">');
    //             };
    //             image.onerror = function () {
    //                 alert('Invalid file type: ' + file.type);
    //                 $(e).val("");
    //             };
    //         };
    //     }
    // });

    function saveAdvertise() {
        $('#frmAdvertise').submit();
    }

    $('#frmAdvertise').on('submit', function (e) {
        e.preventDefault();

        if ($('#b_from').val() > $('#b_to').val()) {
            showToast('error', '등록기간을 정확히 선택해 주세요.', '알림');
            return false;
        }

        if ($('#b_upload_video').val() === '' && $('#b_upload_photo').val() === '' && $('#b_media').val() === '') {
            showToast('error', '사진 또는 영상파일을 선택해 주세요.', '알림');
            return false;
        }

        if ($('#have_setting').is(':checked')) {
            if ($('#b_age_from').val() === '' || $('#b_age_to').val() === '' || ($('#b_age_from').val() > $('#b_age_to').val())) {
                showToast('error', '타깃 연령을 정확히 선택해 주세요.', '알림');
                return false;
            }
        }

        if ($('#b_keyword').val() !== null) {
            $('#b_keyword_val').val($('#b_keyword').val().toString());
        }

        // showBlockUI($('#modalAdvertise > .modal-dialog'));
        App.blockUI({
            animate: !0,
            target: $('#modalAdvertise > .modal-dialog'),
            centerY: true
        });

        $('#frmAdvertise').ajaxSubmit({
            resetForm: false,
            clearForm: false,
            success: saveCallback
        });

        return false;
    });

    function saveCallback(response) {
        hideBlockUI($('#modalAdvertise > .modal-dialog'));

        if ($.trim(response) === 'success') {
            showToast('success', '저장되었습니다', '알림');
            table.draw();
            $('#modalAdvertise').modal('hide');
        } else {
            showToast('error', response, '알림');
        }
    }

    function calcExpectCost() {
        var visible_cnt = $('#b_visible_cnt').val() * 1;
        var visible_cost = $('#b_visible_cost').val() * 1;
        var admin_cost = $('#b_admin_cost').val() * 1;

        var expect_cost = visible_cnt * (visible_cost + admin_cost);

        $('#b_expect_cost').val(expect_cost.toFixed(2));
    }

    function doPause() {
        var advertise_id = $('#b_id').val();
        showConfirm(CONFIRM_PAUSE, function () {
            $.get(
                '<?php echo base_url('Advertise/pause')?>',
                {
                    id: advertise_id
                }, function (response) {
                    showToast('success', response, '알림');
                    table.draw();
                    $('#modalAdvertise').modal('hide');
                }
            )
        });
    }
</script>