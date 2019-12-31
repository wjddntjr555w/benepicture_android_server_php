<?php if ($this->session->flashdata('message')) { ?>
    <div class="custom-alerts alert alert-success fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $this->session->flashdata('message'); ?>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-12">
        <form method="get" action="<?php echo base_url('apimng/index') ?>">
            <label class="control-label">전체 : <strong><?php echo count($api_list); ?></strong> 건</label>
            <div class="form-inline pull-right">
                <select id="col" class="form-control col-md-3" name="col">
                    <option value="name">이름</option>
                    <option value="method">호출방식</option>
                    <option value="url">서브 URL</option>
                    <option value="exp">설명</option>
                </select>
                <input type="text" class="form-control margin-right-10" name="keyword"
                       value="<?php echo $keyword; ?>" style="min-width: 300px;"
                       placeholder="키워드를 입력해주세요."/>
                <div class="btn-group" role="group">
                    <button class="btn btn-default" type="submit">검색</button>
                    <button id="btnDel" type="button" class="btn btn-default">선택삭제</button>
                    <a href="<?php echo base_url('apimng/write'); ?>" class="btn btn-danger">API 추가</a>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row margin-top-15">
    <div class="col-md-12">
        <form id="frmDelete" method="post" action="<?php echo base_url('apimng/delete') ?>">
            <table class="table table-bordered table-striped table-hover">
                <thead class="bg-default">
                <tr>
                    <th width="13%">이름</th>
                    <th width="10%">호출방식</th>
                    <th width="15%">서브 URL</th>
                    <th width="*">설명</th>
                    <th width="24%">기능</th>
                    <th width="7%" class="text-center"><input type="checkbox" id="chkAll"/></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (count($api_list) > 0) {
                    foreach ($api_list as $api) {
                        ?>
                        <tr <?php if (element('f_use', $api) == 0) echo 'class="bg-danger"'; ?>>
                            <td><?php echo html_escape(element('f_name', $api)) ?></td>
                            <td><?php echo html_escape(element('f_method', $api)); ?></td>
                            <td><?php echo nl2br(html_escape(element('f_url', $api))); ?></td>
                            <td><?php echo nl2br(html_escape(element('f_exp', $api))); ?></td>
                            <td>
                                <a href="<?php echo base_url('apimng/write/' . element('f_idx', $api)); ?>"
                                   class="btn btn-default btn-sm">수정</a>
                                <a href="<?php echo base_url('apimng/param_list/input/' . element('f_idx', $api)); ?>"
                                   class="btn btn-default btn-sm">INPUT 변수</a>
                                <a href="<?php echo base_url('apimng/param_list/output/' . element('f_idx', $api)); ?>"
                                   class="btn btn-default btn-sm">OUTPUT 변수</a>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" name="chk[]" class=""
                                       value="<?php echo element('f_idx', $api); ?>"/>
                            </td>
                        </tr>
                        <?php
                    }
                } else { ?>
                    <tr>
                        <td colspan="6">현시할 데이터가 없습니다.</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $(document).ready(function () {
            $("#col").val('<?php echo $column?>');
        });

        $("#chkAll").click(function () {
            var isChecked = $(this).is(':checked');
            $("table tbody tr td input[type='checkbox']").each(function () {
                $(this).prop("checked", isChecked);
            });
        });

        $("#btnDel").click(function () {
            if ($("input[type='checkbox']:checked").length < 1) {
                toastr['error']('삭제하려는 api를 선택해주세요.', '알림');
                return;
            }

            $("#frmDelete").submit();
        });
    });
</script>