<div class="box">
    <div class="box-table">

        <style>
            table tbody tr th, td {
                font-size: 16px !important;
            }

            .scroll-to-top > i {
                font-size: 50px !important;
            }
        </style>

        <h2><?php echo $page_title; ?> API Document</h2>

        <div class="alert alert-dismissible alert-info">
            <p>문자 입력 및 리턴 값은 모두 UTF-8입니다.</p>
            <p>모든 Api 들에서 Response 데이터타입은 json 입니다.</p>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <tr class="bg-default">
                    <th width="150px">이름</th>
                    <th width="640px">설명</th>
                    <th>URL</th>
                </tr>

                <?php
                if (count($api_list) > 0) {
                    foreach ($api_list as $result) {
                        ?>
                        <tr <?php if (element('f_use', $result) == 0) echo 'class="bg-danger"'; ?>>
                            <td>
                                <?php if (element('f_use', $result) == 1) { ?>
                                    <a href="<?php echo base_url('api_manual/view/' . element('f_idx', $result)); ?>"><?php echo html_escape(element('f_name', $result)); ?></a>
                                <?php } else {
                                    echo html_escape(element('f_name', $result));
                                } ?>
                            </td>
                            <td><?php echo html_escape(element('f_exp', $result)); ?></td>
                            <td>
                                <?php
                                if (element('f_url', $result) === '') {
                                    echo site_url('api/' . element('f_name', $result));
                                } else {
                                    echo site_url('api/' . element('f_url', $result) . '/' . element('f_name', $result));
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
        </div>

        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
</div>
