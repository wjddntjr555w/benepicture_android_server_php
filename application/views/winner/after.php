<div class="row">
    <div class="col-md-12">
        <table id="tblAfter" class="table table-striped table-bordered table-responsive">
            <thead>
            <tr class="bg-default">
                <th class="text-center">ID</th>
                <th class="text-center">번호</th>
                <th class="text-center">상품명</th>
                <th class="text-center">응모권번호</th>
                <th class="text-center">회차</th>
                <th class="text-center">내용확인</th>
                <th class="text-center">공개여부</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div id="modalContentView" class="modal fade" tabindex="-1" data-backdrop="static"
     data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true"></button>
                <h4 class="modal-title">내용확인</h4>
            </div>
            <div class="modal-body">
                <form role="form" class="form-horizontal">
                    <div class="form-body padding-tb-20">
                        <textarea id="winner_content" readonly style="border: none;resize: none;">

                        </textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">확인</button>
            </div>
        </div>
    </div>
</div>

<script>
    var after_table;
    $(function () {
        // $('.btn-search').on('click', () => {
        //     after_table.draw();
        // });

        after_table = $('#tblAfter').DataTable({
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
                "url": "<?php echo base_url('Winner/after_table')?>",
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

                $('td:eq(6)', row).html('');
                var a_public = '';
                var a_no_public = '';
                if (data['public'] == <?=STATUS_TRUE?>) {
                    a_public = 'checked';
                    a_no_public = '';
                } else {
                    a_public = '';
                    a_no_public = 'checked';
                }
                var content = data[5];
                $('td:eq(5)', row).html('').append(content.substr(0, 10)).append('...&nbsp;&nbsp;');
                $('td:eq(5)', row).append('<a class="text-primary" href="javascript:;">자세히 보기</a>')
                    .find('a')
                    .bind('click', content, function (e) {
                        showContent(e.data);
                    });
                $('td:eq(6)', row).append('<div class="md-radio-inline">\n' +
                    '                                                <div class="md-radio">\n' +
                    '                                                    <input type="radio" id="public" name="radio-public" onclick="changePublic(this)" data-id="' + data['idx'] + '" data-value="<?=STATUS_TRUE?>" class="radio-public" ' + a_public + '>\n' +
                    '                                                    <label for="public">\n' +
                    '                                                        <span class="inc"></span>\n' +
                    '                                                        <span class="check"></span>\n' +
                    '                                                        <span class="box"></span> 공개 </label>\n' +
                    '                                                </div>\n' +
                    '                                                <div class="md-radio has-error">\n' +
                    '                                                    <input type="radio" id="no_public" name="radio-public" onclick="changePublic(this)" data-id="' + data['idx'] + '" data-value="<?=STATUS_FALSE?>" class="radio-public" ' + a_no_public + '>\n' +
                    '                                                    <label for="no_public">\n' +
                    '                                                        <span class="inc"></span>\n' +
                    '                                                        <span class="check"></span>\n' +
                    '                                                        <span class="box"></span> 비공개 </label>\n' +
                    '                                                </div>\n' +
                    '                                            </div>');
            }
        });
    });


    function changePublic(_obj) {
        var public_status = $(_obj).attr('data-value');
        var idx = $(_obj).attr('data-id');

        $.ajax({
            url: "<?=base_url('Winner/change_public')?>",
            type: 'post',
            data: {status: public_status, idx: idx},
            success: function (result) {
                if (result.trim() === 'success') {
                    showToast('success', (Number(public_status) === 1) ? '공개설정되었습니다' : '비공개설정되었습니다', '알림');
                } else {
                    showToast('error', serverErrMsg(), '알림');
                }
            }
        })
    }

    function showContent(content) {
        $('#winner_content')
            .css('width', '100%')
            .css('height', '350px')
            .val(content);
        $('#modalContentView').modal();
    }
</script>