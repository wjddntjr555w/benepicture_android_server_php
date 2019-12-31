<style>
    .pagination {
        float: right !important;
    }

    table tr td {
        text-align: left !important;
    }

    .page-bar-btn {
        display: block !important;
    }
</style>

<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat dashboard-stat-v2 red">
            <div class="dashboard-stat-head font-white">회원수</div>
            <div class="dashboard-stat-body">
                <div class="stat-body-row">
                    <span>전체 회원수 :</span>
                    <span><?= number_format($all_user_cnt) ?>명</span>
                </div>
                <div class="stat-body-row">
                    <span>어제 가입자 :</span>
                    <span><?= number_format($yesterday_login_cnt) ?>명</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat dashboard-stat-v2 blue">
            <div class="dashboard-stat-head font-white">출금비용(최근60일)</div>
            <div class="dashboard-stat-body">
                <div class="stat-body-row">
                    <span>미처리비용 :</span>
                    <span>(<?= number_format($non_deal_cnt) ?>건)<?= number_format($non_deal_cost, 2) ?>원</span>
                </div>
                <div class="stat-body-row">
                    <span>처리완료 :</span>
                    <span><?= number_format($done_deal_cost, 2) ?>원</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat dashboard-stat-v2" style="background: #493D55">
            <div class="dashboard-stat-head font-white">광고수익</div>
            <div class="dashboard-stat-body">
                <div class="stat-body-row">
                    <span>이번달 수익 :</span>
                    <span><?php echo number_format($this_month_reward, 2) ?>원</span>
                </div>
                <div class="stat-body-row">
                    <span>지난 6달 수익 :</span>
                    <span><?php echo number_format($half_year_reward, 2) ?>원</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat-v2 yellow-pan">
            <div class="yellow-pan-row">실시간 상금 누적</div>
            <div class="yellow-pan-row"><?php echo number_format($total_reward, 2) ?>원</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat-v2 green-pan">
            <div class="green-pan-row">Today</div>
            <div class="green-pan-row pan-body">
                <span id="today"></span>
                <span id="now"></span>
            </div>
            <div class="green-pan-row">
                <div class="col-md-6">
                    <span>진행중인 광고 :</span>
                    <span><?= number_format($doing_advertise_cnt) ?>개</span>
                </div>
                <div class="col-md-6">
                    <span>완료된 광고 :</span>
                    <span><?= number_format($complete_advertise_cnt) ?>개</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat dashboard-stat-v2" style="background: #493D55">
            <div class="dashboard-stat-head font-white">App에서<br>신청된 광고</div>
            <div class="dashboard-stat-body padding-t-38">
                <div class="stat-body-row padding-b-10">
                    <span>신청 대기 중인 광고 :</span>
                    <span><?= number_format($request_advertise_cnt) ?>개</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-bar" style="color: white">
    <div class="page-breadcrumb">
        <span>공지사항</span>
    </div>
    <a class="page-bar-btn" id="btnRegNotice" onclick="showBanner(null)">신규등록</a>
</div>

<div class="table-content">
    <table class="table table-striped table-hover" id="tblNotice" style="background: white">
        <thead>
        <tr>
            <th width="20%">등록일시</th>
            <th>제목</th>
            <th width="15%"></th>
        </tr>
        </thead>
    </table>
</div>


<div id="modalNotice" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true"></button>
                <h4 class="modal-title">공지사항 등록/편집</h4>
            </div>
            <div class="modal-body">
                <form role="form" class="form-horizontal" id="frmBanner" method="post"
                      enctype="multipart/form-data"
                      action="<?php echo base_url('Main/save') ?>">
                    <input type="hidden" id="b_id" name="Notice[f_idx]" value="0"/>
                    <div class="form-body padding-tb-20">
                        <div class="form-group">
                            <label class="col-md-2 control-label">제목</label>
                            <div class="col-md-10">
                                <input type="text" id="b_title" name="Notice[f_title]" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">내용</label>
                            <div class="col-md-10">
                                 <textarea id="b_content" name="Notice[f_content]" class="form-control"
                                           rows="8"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="saveNotice()" class="btn btn-primary">저장</button>
                <button type="button" data-dismiss="modal" class="btn dark">취소</button>
            </div>
        </div>
    </div>
</div>

<script>
    var table;

    $(function () {

        setInterval(() => {
            let today = moment().format('YYYY년 MM월 DD일');
            let now = moment().format('hh:mm:ss a');

            now = now.replace('am', '오전');
            now = now.replace('pm', '오후');

            $('#today').html(today);
            $('#now').html(now);
        }, 1000);

        table = $('#tblNotice').DataTable({
            "language": {
                "emptyTable": "데이터가 없습니다.",
                "info": "공지 목록&nbsp;",
                "infoEmpty": "공지 목록&nbsp;",
                "search": "Search : "
            },
            "dom": "t<'table-pagination'p>",
            "bStateSave": false,
            "pageLength": 5,
            "bFilter": false,
            "bInfo": false,
            "columns": [
                {"orderable": true},
                {"orderable": true},
                {"orderable": false}
            ],
            "order": [[0, 'desc']],
            "processing": false,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo base_url('Main/table')?>",
                "type": "GET",
                "beforeSend": function () {
                    showBlockUI($('#pageContent'));
                },
                "data": function (data) {
                },
                "error": function () {
                    hideBlockUI($('#pageContent'));
                }
            },
            "drawCallback": function () {
                hideBlockUI($('#pageContent'));
            },
            "createdRow": function (row, data, dataIndex) {
                hideBlockUI($('#pageContent'));

                $('td:eq(0)', row).html(data['reg_time'].substr(0, 10));
                $('td:eq(1)', row).html(data['title']);

                var btnEdit = $('<button class="btn btn-info">수정하기</button>')
                    .bind('click', data, function (e) {
                        showBanner(e.data);
                    });
                var btnDel = $('<button class="btn btn-default">삭제하기</button>')
                    .bind('click', function () {
                        delBanner(data['id']);
                    });
                $('td:eq(2)', row).empty().append(btnEdit).append(btnDel);

            }
        });

    });

    function showBanner(banner) {
        $('#b_id').val(banner === null ? 0 : banner['id']);
        $('#b_title').val(banner === null ? '' : banner['title']);
        $('#b_content').val(banner === null ? '' : banner['content']);

        $('#modalNotice').modal();
    }

    function delBanner(id) {
        showConfirm(CONFIRM_DELETE, function () {
            $.get(
                '<?php echo base_url('main/delete')?>',
                {
                    id: id
                }, function (response) {
                    if ($.trim(response) === 'success') {
                        showToast('success', '삭제되었습니다', '알림');
                        table.draw();
                    } else {
                        showToast('error', serverErrMsg(), '알림');
                    }
                }
            )
        });
    }

    function saveNotice() {
        if ($.trim($('#b_title').val()) === '') {
            showToast('error', '제목을 입력해주세요.', '알림');
            return;
        }
        if ($.trim($('#b_content').val()) === '') {
            showToast('error', '내용을 입력해주세요.', '알림');
            return;
        }

        $('#frmBanner').ajaxSubmit({
            resetForm: true,
            clearForm: true,
            success: saveCallback
        });
    }

    function saveCallback(response) {
        if ($.trim(response) === 'success') {
            showToast('success', '저장되었습니다', '알림');
            table.draw();
            $('#modalNotice').modal('hide');
        } else {
            showToast('error', serverErrMsg(), '알림');
        }
    }

    $('a.btn-refresh').on('click', function () {
        location.reload();
    });
</script>