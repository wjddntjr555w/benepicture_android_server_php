<?php if ($this->session->flashdata('message')) { ?>
    <div class="custom-alerts alert alert-success fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $this->session->flashdata('message'); ?>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-12">
        <form method="get"
              action="<?php echo base_url('apimng/param_list/') . $type . '/' . element('f_idx', $api_data) ?>">
            <label class="control-label">전체 : <strong><?php echo count($data); ?></strong> 건</label>
            <div class="form-inline pull-right">
                <select id="col" class="form-control col-md-3" name="column">
                    <option value="f_name">변수명</option>
                    <option value="f_type">타입</option>
                    <option value="f_ness">종류</option>
                    <option value="f_exp">설명</option>
                    <option value="f_sort">순서</option>
                </select>
                <input type="text" class="form-control margin-right-10" name="keyword"
                       value="<?php echo $keyword; ?>" style="min-width: 300px;"
                       placeholder="키워드를 입력해주세요."/>
                <div class="btn-group" role="group">
                    <button class="btn btn-default" type="submit">검색</button>
                    <a href="<?php echo base_url('apimng/index'); ?>" class="btn btn-default">API 목록으로</a>
                    <a href="<?php echo base_url('apimng/param_list/input/' . element('f_idx', $api_data)); ?>"
                       class="btn btn-<?php echo $type == 'input' ? 'success' : 'default'; ?>">Input
                        목록</a>
                    <a href="<?php echo base_url('apimng/param_list/output/' . element('f_idx', $api_data)); ?>"
                       class="btn btn-<?php echo $type == 'output' ? 'success' : 'default'; ?>">Output
                        목록</a>
                    <button type="button" id="btnDel" class="btn btn-default">선택삭제</button>
                    <a href="<?php echo base_url('apimng/argumentwrite/' . $type . '/' . element('f_idx', $api_data)); ?>"
                       class="btn btn-danger"><?php echo $type ?> 변수 추가</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row margin-top-15">
    <div class="col-md-12">
    </div>
    <form id="frmDelete" method="post"
          action="<?php echo base_url('apimng/argumentdelete/') . $type . '/' . element('f_idx', $api_data) ?>">
        <table class="table table-bordered table-striped table-hover">
            <thead class="bg-default">
            <tr>
                <th width="10%">파라미터명</th>
                <th width="7%">타입</th>
                <th width="5%">종류</th>
                <th>설명</th>
                <th width="5%" class="text-center">순서</th>
                <th width="5%" class="text-center">수정</th>
                <th width="7%" class="text-center"><input type="checkbox" id="chkAll"/></th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (count($data) > 0) {
                foreach ($data as $result) {
                    ?>
                    <tr>
                        <td><?php echo html_escape(element('f_name', $result)); ?></td>
                        <td><?php echo html_escape(element('f_type', $result)); ?></td>
                        <td>
                            <?php
                            switch (html_escape(element('f_ness', $result))) {
                                case 'N' :
                                    echo '필수';
                                    break;
                                case '' :
                                    echo '';
                                    break;
                                case 'D' :
                                    echo '개발시';
                                    break;
                                default:
                                    echo '';
                            }
                            ?>
                        </td>
                        <td><?php echo nl2br(html_escape(element('f_exp', $result))); ?></td>
                        <td class="text-center"><?php echo number_format(element('f_sort', $result)); ?></td>
                        <td class="text-center">
                            <a href="<?php echo base_url('apimng/argumentwrite/' . $type . '/' . element('f_idx', $api_data) . '/' . element($primary_key, $result) . '?' . $this->input->server('QUERY_STRING', null, '')); ?>"
                               class="btn btn-default btn-xs">수정</a></td>
                        <td class="text-center">
                            <input type="checkbox" name="chk[]"
                                   value="<?php echo element($primary_key, $result); ?>"/>
                        </td>
                    </tr>
                    <?php
                }
            } else { ?>
                <tr>
                    <td colspan="7">등록된 파라미터가 없습니다.</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </form>
</div>

<script type="text/javascript">
    $(function () {
        $(document).ready(function () {
            $("#col").val('<?php echo $column?>');

            $("h1.page-title").append(" &gt; " + "<?php echo html_escape(element('f_url', $api_data)) . '/' . html_escape(element('f_name', $api_data)); ?>" + " : " + "<?php echo $type ?> 목록");
        });

        $("#chkAll").click(function () {
            var isChecked = $(this).is(':checked');
            $("table tbody tr td input[type='checkbox']").each(function () {
                $(this).prop("checked", isChecked);
            });
        });

        $("#btnDel").click(function () {
            if ($("input[type='checkbox']:checked").length < 1) {
                toastr['error']('삭제하려는 파라미터를 선택해주세요.', '알림');
                return;
            }

            $("#frmDelete").submit();
        });
    });
</script>