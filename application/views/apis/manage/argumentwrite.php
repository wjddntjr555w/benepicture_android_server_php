<div class="row">
    <div class="col-md-12 margin-top-10">
        <?php if ($this->session->flashdata('message')) { ?>
            <div class="custom-alerts alert alert-success fade in">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('message'); ?>
            </div>
        <?php } ?>

        <?php
        echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
        $attributes = array('class' => 'form-horizontal', 'name' => 'frmWrite', 'id' => 'frmWrite');
        echo form_open($write_url, $attributes);
        ?>
        <input type="hidden" name="<?php echo $primary_key; ?>"
               value="<?php echo element($primary_key, $data); ?>"/>
        <input type="hidden" name="f_api" value="<?php echo element('f_idx', $apidata); ?>"/>
        <div class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-1 control-label">변수명</label>
                <div class="col-sm-11">
                    <input type="text" class="form-control" name="f_name"
                           value="<?php echo set_value('f_name', element('f_name', $data)); ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">타입</label>
                <div class="col-sm-11">
                    <label class="radio-inline" for="f_type_1">
                        <input type="radio" name="f_type" id="f_type_1"
                               value="String(Varchar)" <?php echo set_radio('f_type', 'String(Varchar)', (element('f_type', $data) == 'String(Varchar)' || element('f_type', $data) == '' ? true : false)); ?> />
                        String(Varchar)
                    </label>
                    <label class="radio-inline" for="f_type_2">
                        <input type="radio" name="f_type" id="f_type_2"
                               value="String(Text)" <?php echo set_radio('f_type', 'String(Text)', (element('f_type', $data) == 'String(Text)' ? true : false)); ?> />
                        String(Text)
                    </label>
                    <label class="radio-inline" for="f_type_3">
                        <input type="radio" name="f_type" id="f_type_3"
                               value="Integer" <?php echo set_radio('f_type', 'Integer', (element('f_type', $data) == 'Integer' ? true : false)); ?> />
                        Integer
                    </label>
                    <label class="radio-inline" for="f_type_4">
                        <input type="radio" name="f_type" id="f_type_4"
                               value="Float" <?php echo set_radio('f_type', 'Float', (element('f_type', $data) == 'Float' ? true : false)); ?> />
                        Float
                    </label>
                    <label class="radio-inline" for="f_type_5">
                        <input type="radio" name="f_type" id="f_type_5"
                               value="Object" <?php echo set_radio('f_type', 'Object', (element('f_type', $data) == 'Object' ? true : false)); ?> />
                        Object
                    </label>
                    <label class="radio-inline" for="f_type_6">
                        <input type="radio" name="f_type" id="f_type_6"
                               value="Object Arr" <?php echo set_radio('f_type', 'Object Arr', (element('f_type', $data) == 'Object Arr' ? true : false)); ?> />
                        Object Arr
                    </label>
                    <label class="radio-inline" for="f_type_7">
                        <input type="radio" name="f_type" id="f_type_7"
                               value="File" <?php echo set_radio('f_type', 'File', (element('f_type', $data) == 'File' ? true : false)); ?> />
                        File
                    </label>
                    <label class="radio-inline" for="f_type_8">
                        <input type="radio" name="f_type" id="f_type_8"
                               value="Multi Files" <?php echo set_radio('f_type', 'Multi Files', (element('f_type', $data) == 'Multi Files' ? true : false)); ?> />
                        Multi Files
                    </label>
                    <label class="radio-inline" for="f_type_9">
                        <input type="radio" name="f_type" id="f_type_9"
                               value="Boolean" <?php echo set_radio('f_type', 'Boolean', (element('f_type', $data) == 'Boolean' ? true : false)); ?> />
                        Boolean
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">종류</label>
                <div class="col-sm-11">
                    <label class="radio-inline" for="f_ness_4">
                        <input type="radio" name="f_ness" id="f_ness_4"
                               value="" <?php echo set_radio('f_ness', '', (element('f_ness', $data) == '' ? true : false)); ?> />
                        빈값
                    </label>
                    <label class="radio-inline" for="f_ness_1">
                        <input type="radio" name="f_ness" id="f_ness_1"
                               value="N" <?php echo set_radio('f_ness', 'N', (element('f_ness', $data) == 'N' ? true : false)); ?> />
                        필수
                    </label>
                    <label class="radio-inline" for="f_ness_3">
                        <input type="radio" name="f_ness" id="f_ness_3"
                               value="D" <?php echo set_radio('f_ness', 'D', (element('f_ness', $data) == 'D' ? true : false)); ?> />
                        개발시
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">설명</label>
                <div class="col-sm-11">
                    <textarea class="form-control" rows="10"
                              name="f_exp"><?php echo set_value('f_exp', element('f_exp', $data)); ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">순서</label>
                <div class="col-sm-11 form-inline">
                    <input type="text" class="form-control" name="f_sort"
                           value="<?php echo set_value('f_sort', element('f_sort', $data)); ?>"/>
                </div>
            </div>
            <div class="btn-group pull-right" role="group" aria-label="...">
                <?php if (!element($primary_key, $data)) { ?>
                    <label class="checkbox-inline" style="padding:10px 10px 10px 120px;" for="reinput">
                        <input type="checkbox" name="reinput" id="reinput" value="1" checked="checked"/> 다시 등록 페이지로
                    </label>
                    <br/>
                <?php } ?>
                <a href="<?php echo base_url('apimng/index'); ?>" class="btn btn-default btn-sm">API 목록</a>
                <a href="<?php echo $input_list_url; ?>" class="btn btn-default btn-sm">Input 목록</a>
                <a href="<?php echo $output_list_url; ?>" class="btn btn-default btn-sm">Output 목록</a>
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
                api_name: {required: true}
            }
        });

        $(document).ready(function () {
            $("h1.page-title").append(" > <?php echo html_escape(element('f_name', $apidata)); ?> : <?php echo $type ?> 등록");
        });
    });
    //]]>
</script>
