<div class="row">
    <div class="col-md-12">
        <?php
        echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
        $attributes = array('class' => 'form-horizontal', 'name' => 'frmWrite', 'id' => 'frmWrite');
        echo form_open(base_url('apimng/write'), $attributes);
        ?>
        <input type="hidden" name="<?php echo $primary_key ?>"
               value="<?php echo element($primary_key, $api_data); ?>"/>
        <div class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-1 control-label">API 이름</label>
                <div class="col-sm-11">
                    <input type="text" class="form-control" name="f_name"
                           value="<?php echo set_value('f_name', element('f_name', $api_data)); ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">호출방식</label>
                <div class="col-sm-11">
                    <label class="radio-inline" for="f_method_GET">
                        <input type="radio" name="f_method" id="f_method_GET"
                               value="GET" <?php echo set_radio('f_method', 'GET', (element('f_method', $api_data) == 'GET' || element('f_method', $api_data) == '' ? true : false)); ?> />
                        GET
                    </label>
                    <label class="radio-inline" for="f_method_POST">
                        <input type="radio" name="f_method" id="f_method_POST"
                               value="POST" <?php echo set_radio('f_method', 'POST', (element('f_method', $api_data) == 'POST' ? true : false)); ?> />
                        POST
                    </label>
                    <label class="radio-inline" for="f_method_PUT">
                        <input type="radio" name="f_method" id="f_method_PUT"
                               value="PUT" <?php echo set_radio('f_method', 'PUT', (element('f_method', $api_data) == 'PUT' ? true : false)); ?> />
                        PUT
                    </label>
                    <label class="radio-inline" for="f_method_DELETE">
                        <input type="radio" name="f_method" id="f_method_DELETE"
                               value="DELETE" <?php echo set_radio('f_method', 'DELETE', (element('f_method', $api_data) == 'DELETE' ? true : false)); ?> />
                        DELETE
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">서브 URL</label>
                <div class="col-sm-11">
                    <input type="text" class="form-control" name="f_url"
                           value="<?php echo set_value('f_url', element('f_url', $api_data)); ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">설명</label>
                <div class="col-sm-11">
                    <textarea class="form-control" rows="5"
                              name="f_exp"><?php echo set_value('f_exp', element('f_exp', $api_data)); ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-1 control-label">사용여부</label>
                <div class="col-sm-11">
                    <label class="radio-inline" for="f_use_1">
                        <input type="radio" name="f_use" id="f_use_1"
                               value="1" <?php echo set_radio('f_use', '1', (element('f_use', $api_data) != '0' ? true : false)); ?> />
                        사용
                    </label>
                    <label class="radio-inline" for="f_use_0">
                        <input type="radio" name="f_use" id="f_use_0"
                               value="0" <?php echo set_radio('f_use', '0', (element('f_use', $api_data) == '0' ? true : false)); ?> />
                        사용 안함
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">비고</label>
                <div class="col-sm-11">
                    <textarea class="form-control" rows="5"
                              name="f_bigo"><?php echo set_value('f_bigo', element('f_bigo', $api_data)); ?></textarea>
                </div>
            </div>
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a class="btn btn-default btn-sm btn-history-back" href="<?php echo base_url('apimng')?>">
                    취소하기
                </a>
                <button type="submit" class="btn btn-success btn-sm">저장하기</button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script type="text/javascript">
    //<![CDATA[
    $(function () {
        $('#frmWrite').validate({
            rules: {
                f_name: {
                    required: true
                }
            },
            messages: {
                f_name: {
                    required: "API 이름을 입력해주세요."
                }
            }
        });
    });
    //]]>
</script>