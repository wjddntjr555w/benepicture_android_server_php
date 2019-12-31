<style>
    .lb-welcome {
        font-size: 25px;
        font-weight: bold;
        margin-top: 30px;
    }
</style>
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat-v2 yellow-pan">
            <div class="yellow-pan-row">실시간 상금 누적</div>
            <div class="yellow-pan-row"><?= number_format($reward) ?>원</div>
        </div>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
        <label class="lb-welcome">
            안녕하세요.[광고주]님!<br>
            오늘도 배너픽쳐와 함께<br>
            성공적인 기업홍보로 대박나시길 바랍니다.</label>
    </div>
</div>

<div class="page-bar" style="color: white">
    <div class="page-breadcrumb">
        <span>공지사항</span>
    </div>
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

<div id="modalNotice" class="modal fade" tabindex="-1" data-backdrop="static"
     data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true"></button>
                <h4 class="modal-title">공지사항</h4>
            </div>
            <div class="modal-body">
                <form role="form" class="form-horizontal" id="frmBanner">
                    <div class="form-body padding-tb-20 col-md-offset-1">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label col-md-2" for="name">제목</label>
                                <div class="col-md-10">
                                    <input type="text" id="b_title" name="Notice[f_title]" class="form-control"
                                           disabled>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="col-md-2 control-label" for="name">내용</label>
                                <div class="col-md-10">
                                <textarea id="b_content" name="Notice[f_content]" class="form-control" disabled
                                          rows="8"></textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary">확인</button>
            </div>
        </div>
    </div>
</div>

<script>
    var table;

    $(function () {

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

                var btnEdit = $('<a><i class="fa fa-cog" style="font-size: 20px"></i></a>')
                    .bind('click', data, function (e) {
                        showBanner(e.data);
                    });
                $('td:eq(2)', row).empty().append(btnEdit);

            }
        });

    });

    function showBanner(banner) {
        $('#b_id').val(banner === null ? 0 : banner['id']);
        $('#b_title').val(banner === null ? '' : banner['title']);
        $('#b_content').val(banner === null ? '' : banner['content']);

        $('#modalNotice').modal();
    }

</script>