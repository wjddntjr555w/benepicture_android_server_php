<style>
    .btn-keyword-del {
        opacity: 0;
        font-size: 14px !important;
    }

    table tbody tr td:hover .btn-keyword-del {
        opacity: 1;
    }
</style>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="portlet light portlet-fit portlet-datatable ">
            <div class="portlet-title">
                <div class="caption">
                    <i class=" icon-layers font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase">대분류</span>
                </div>
            </div>
            <div class="portlet-body" style="display: table;width: 100%">
                <div class="input-group" style="width: 100%">
                    <input type="text" class="form-control" id="parent_keyword" placeholder="항목명을 작성해주세요.">
                    <span class="input-group-btn">
                        <button class="btn blue" type="button"
                                onclick="save_parent(<?= STATUS_FALSE ?>)">대분류 항목 추가</button>
                    </span>
                </div>
                <div class="row margin-top-10">
                    <div class="col-md-6 col-sm-12">
                        <div class="dataTables_length">
                            <label>
                                <span class="info-label">Show : </span>
                                <select id="tblParent_length" class="form-control input-sm input-xsmall input-inline"
                                        onchange="draw_tblParent()">
                                    <option value="10">10</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <div class="dataTables_filter">
                            <label>
                                <span class="info-label">Search : </span>
                                <input type="search" id="tblParent_search"
                                       class="form-control input-sm input-small input-inline" placeholder=""
                                       oninput="draw_tblParent()">
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12" id="tblParent_wrapper">
                        <table class="table table-bordered">
                            <tbody>

                            <?php
                            if (count($keyword_list) > 0) {
                                foreach ($keyword_list as $list) {
                                    ?>
                                    <tr>
                                        <td width="85%" class="p_keyword" data-id="<?= $list->f_idx ?>"
                                            onclick="select_parent(this)">
                                            <?= $list->f_name ?>
                                            <span class="btn btn-link btn-keyword-del pull-right"
                                                  data-class="parent">삭제</span>
                                        </td>
                                        <td width="15%" data-id="<?= $list->f_idx ?>"
                                            style="background: #bfcad1;cursor: pointer" class="text-center btn-p-save"
                                            onclick="update_parent_keyword(this)">변경
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>

                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 text-center">
                        <div class="col offset-m1 offset-l1 s12 m8 l8">
                            <p id="parent_pagination" style="text-align: center"></p>
                        </div>
                        <input type="hidden" id="tblParentPageNum" value="<?= $page_num ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="portlet light portlet-fit portlet-datatable">
            <div class="portlet-title">
                <div class="caption">
                    <i class=" icon-layers font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase" id="ch_caption">/중분류</span>
                </div>
            </div>
            <div class="portlet-body" style="display: table;width: 100%">
                <div class="input-group" style="width: 100%">
                    <input type="text" class="form-control" id="child_keyword" placeholder="항목명을 작성해주세요.">
                    <span class="input-group-btn">
                        <button class="btn blue" type="button"
                                onclick="save_child(<?= STATUS_FALSE ?>)">중분류 항목 추가</button>
                    </span>
                </div>
                <div class="row margin-top-10">
                    <div class="col-md-6 col-sm-12">
                        <div class="dataTables_length">
                            <label>
                                <span class="info-label">Show : </span>
                                <select id="tblChild_length" class="form-control input-sm input-xsmall input-inline"
                                        onchange="draw_tblChild()">
                                    <option value="10">10</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <div class="dataTables_filter">
                            <label>
                                <span class="info-label">Search : </span>
                                <input type="search" id="tblChild_search"
                                       class="form-control input-sm input-small input-inline" placeholder=""
                                       oninput="draw_tblChild()">
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12" id="tblChild_wrapper">
                        <center>등록된 항목이 없습니다.</center>
                    </div>
                    <div class="col-md-12 text-center">
                        <div class="col offset-m1 offset-l1 s12 m8 l8">
                            <p id="child_pagination" style="text-align: center"></p>
                        </div>
                        <input type="hidden" id="tblChildPageNum" value="<?= $page_num ?>">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function () {
        $("#parent_pagination").bootpag({
            paginationClass: "pagination pagination-sm",
            next: '<i class="fa fa-angle-right"></i>',
            prev: '<i class="fa fa-angle-left"></i>',
            total: <?=$total_page?>,
            page: <?=$page_num?>,
            maxVisible: 10
        }).on("page", function (event, num) {
            $('#tblParentPageNum').val(num);
            draw_tblParent();
        });

        connectEvent();
    });


    function connectEvent() {
        $('.btn-keyword-del').each((ind, btn) => {
            $(btn).unbind('click').bind('click', function (e) {
                e.stopPropagation();

                let keyword_id = $(this).parent('td').attr('data-id');
                let which = $(this).attr('data-class');

                if (!confirm('선택하신 키워드를 삭제하시겠습니까?')) {
                    return;
                }

                $.ajax({
                    url: "<?=base_url('Keyword/delete')?>",
                    type: 'GET',
                    data: {
                        id: keyword_id
                    },
                    success: (res) => {
                        if (res.trim() === 'success') {
                            showToast('success', '삭제되었습니다', '알림');

                            if (which === 'parent') {
                                draw_tblParent();
                            }
                            draw_tblChild();
                        } else {
                            showToast('error', serverErrMsg(), '오류');
                        }
                    }
                });

            });
        });
    }


    function draw_tblParent() {
        $.ajax({
            url: "<?=base_url('Keyword/table')?>",
            type: 'post',
            data: {
                search_keyword: $.trim($('#tblParent_search').val()),
                length: $('#tblParent_length').val(),
                page_num: $('#tblParentPageNum').val(),
                parent: 0
            },
            success: function (result) {
                let data = JSON.parse(result);

                $("#parent_pagination").bootpag({
                    paginationClass: "pagination pagination-sm",
                    next: '<i class="fa fa-angle-right"></i>',
                    prev: '<i class="fa fa-angle-left"></i>',
                    total: data.total_page,
                    page: data.page_num,
                    maxVisible: 10
                }).on("page", function (event, num) {
                    $('#tblParentPageNum').val(num);
                    draw_tblParent();
                });

                var table_data = data.data;
                var parent_table = '';

                if (table_data.length > 0) {
                    parent_table += '<table class="table table-bordered">' +
                        '<tbody>';
                    for (var index = 0; index < table_data.length; index++) {
                        parent_table += '<tr>';
                        parent_table += '<td width="85%" class="p_keyword" data-id="' + table_data[index]['idx'] + '" onclick="select_parent(this)">' + table_data[index]['name'] + '<span class="btn btn-link btn-keyword-del pull-right" data-class="parent">삭제</span></td>';
                        parent_table += '<td width="15%" data-id="' + table_data[index]['idx'] + '" style="background: #bfcad1;cursor: pointer" class="text-center btn-p-save" onclick="update_parent_keyword(this)">변경</td>';
                        parent_table += '</tr>';
                    }
                    parent_table += '</tbody>' +
                        '</table>';
                } else {
                    parent_table += '<center>등록된 항목이 없습니다.</center>'
                }

                $('#tblParent_wrapper').html(parent_table);
                connectEvent();
            }
        })
    }

    function save_parent(type, old) {
        var data = {};
        if (type == <?=STATUS_TRUE?>) {
            data = {
                name: $.trim($('.b_pKeyword').val()),
                id: $('.b_pKeyword').attr('data-id'),
                parent: 0
            };
        } else {
            data = {
                name: $.trim($('#parent_keyword').val()),
                id: 0,
                parent: 0
            };
        }

        if (data['name'] === '') {
            showToast('warning', '항목명을 작성해주세요.', '알림');
            return false;
        }

        if (old == data['name']) {
            init_parent();
            return false;
        }

        $.ajax({
            url: "<?=base_url('Keyword/save')?>",
            type: 'post',
            data: data,
            success: function (result) {
                if (result === 'success') {
                    showToast('success', '저장되었습니다', '알림');
                    init_parent();
                    draw_tblParent();
                } else if (result === 'duplicate') {
                    showToast('warning', '이미 등록된 항목입니다', '알림');
                } else {
                    showToast('error', serverErrMsg(), '알림');
                }
            }
        })
    }

    function update_parent_keyword(_obj) {
        init_parent();
        var old_keyword = $(_obj).closest('tr').find('.p_keyword').text().replace('삭제', '').trim();
        $(_obj).closest('tr').find('td:first-child').html('<input type="text" class="form-control b_pKeyword" data-id="' + $(_obj).attr('data-id') + '" value="' + old_keyword + '"/>');
        $(_obj).text('보관').unbind().attr('style', 'background:blue;color:white;cursor:pointer');
        $(_obj).attr('onclick', 'save_parent(' + '<?=STATUS_TRUE?>' + ',' + "'" + old_keyword + "'" + ')');
    }

    function init_parent() {
        $('#parent_keyword').val('');
        $('.btn-p-save').text('변경').unbind().attr('onclick', 'update_parent_keyword(this)').attr('style', 'background:#bfcad1;color:black');
        $('.p_keyword > input').each(function () {
            var keyword = $(this).val();
            $(this).parents('td').html('').html(keyword + '<span class="btn btn-link btn-keyword-del pull-right" data-class="parent">삭제</span>');
        });
        connectEvent();
    }

    function select_parent(_obj) {
        $('.p_keyword').attr('style', 'background:white');
        var p_name = $(_obj).text();
        var p_id = $(_obj).attr('data-id');
        $('#ch_caption').text(p_name + '/중분류').attr('data-id', p_id);
        $(_obj).attr('style', 'background:#E5E5E5');
        draw_tblChild();
    }


    //for child

    function draw_tblChild() {
        if ($('#ch_caption').attr('data-id') <= 0) {
            showToast('warning', '대분류를 선택해주세요.', '알림');
            return false;
        }

        $.ajax({
            url: "<?=base_url('Keyword/table')?>",
            type: 'post',
            data: {
                search_keyword: $.trim($('#tblChild_search').val()),
                length: $('#tblChild_length').val(),
                page_num: $('#tblChildPageNum').val(),
                parent: $('#ch_caption').attr('data-id')
            },
            success: function (result) {
                let data = JSON.parse(result);

                $("#child_pagination").bootpag({
                    paginationClass: "pagination pagination-sm",
                    next: '<i class="fa fa-angle-right"></i>',
                    prev: '<i class="fa fa-angle-left"></i>',
                    total: data.total_page,
                    page: data.page_num,
                    maxVisible: 10
                }).on("page", function (event, num) {
                    $('#tblChildPageNum').val(num);
                    draw_tblChild();
                });

                var table_data = data.data;
                var child_table = '';
                if (table_data.length > 0) {
                    child_table += '<table class="table table-bordered">' +
                        '<tbody>';
                    for (var index = 0; index < table_data.length; index++) {
                        child_table += '<tr>';
                        child_table += '<td width="85%" class="ch_keyword" data-id="' + table_data[index]['idx'] + '">' + table_data[index]['name'] + '<span class="btn btn-link btn-keyword-del pull-right" data-class="child">삭제</span></td>';
                        child_table += '<td width="15%" data-id="' + table_data[index]['idx'] + '" style="background: #bfcad1;cursor: pointer" class="text-center btn-ch-save" onclick="update_child_keyword(this)">변경</td>';
                        child_table += '</td>';
                    }
                    child_table += '</tbody>' +
                        '</table>';
                } else {
                    child_table += '<center>등록된 항목이 없습니다.</center>'
                }

                $('#tblChild_wrapper').html(child_table);
                connectEvent();
            }
        })
    }

    function save_child(type, old) {

        var data = {};
        if (type == <?=STATUS_TRUE?>) {
            data = {
                name: $.trim($('.b_chKeyword').val()),
                id: $('.b_chKeyword').attr('data-id'),
                parent: $('#ch_caption').attr('data-id')
            };
        } else {
            data = {
                name: $.trim($('#child_keyword').val()),
                id: 0,
                parent: $('#ch_caption').attr('data-id')
            };
        }

        if ($('#ch_caption').attr('data-id') <= 0 || $('#ch_caption').attr('data-id') == null) {
            showToast('warning', '대분류를 선택해주세요.', '알림');
            return false;
        }

        if (data['name'] === '') {
            showToast('warning', '항목명을 작성해주세요.', '알림');
            return false;
        }

        if (old == data['name']) {
            init_child();
            return false;
        }

        $.ajax({
            url: "<?=base_url('Keyword/save')?>",
            type: 'post',
            data: data,
            success: function (result) {
                if (result === 'success') {
                    showToast('success', '저장되었습니다', '알림');
                    init_child();
                    draw_tblChild();
                } else if (result === 'duplicate') {
                    showToast('warning', '이미 등록된 항목입니다', '알림');
                } else {
                    showToast('error', serverErrMsg(), '알림');
                }
            }
        })
    }

    function update_child_keyword(_obj) {
        init_child();
        var old_keyword = $(_obj).closest('tr').find('.ch_keyword').text().replace('삭제', '').trim();
        $(_obj).closest('tr').find('td:first-child').html('<input type="text" class="form-control b_chKeyword" data-id="' + $(_obj).attr('data-id') + '" value="' + old_keyword + '"/>');
        $(_obj).text('보관').unbind().attr('style', 'background:blue;color:white;cursor:pointer');
        $(_obj).attr('onclick', 'save_child(' + '<?=STATUS_TRUE?>' + ',' + "'" + old_keyword + "'" + ')');
    }

    function init_child() {
        $('#child_keyword').val('');
        $('.btn-ch-save').text('변경').unbind().attr('onclick', 'update_child_keyword(this)').attr('style', 'background:#bfcad1;color:black');
        $('.ch_keyword > input').each(function () {
            var keyword = $(this).val();
            $(this).parents('td').html('').html(keyword + '<span class="btn btn-link btn-keyword-del pull-right" data-class="child">삭제</span>');
        });
        connectEvent();
    }
</script>