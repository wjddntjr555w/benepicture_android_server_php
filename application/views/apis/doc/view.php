<div class="box">
    <div class="box-table">
        <div class="no-padding">
            <style>
                table tbody tr th, td {
                    font-size: 16px !important;
                }

                .table {
                    margin-bottom: 0 !important;
                }

                .scroll-to-top > i {
                    font-size: 50px !important;
                }
            </style>
            <table class="table table-bordered table-advance">
                <tbody>
                <tr>
                    <th class="bg-blue-sharp font-white bold" width="10%">API 명</th>
                    <td>
                        <?php echo html_escape(element('f_name', element('data', $view))); ?>
                    </td>
                </tr>
                <tr>
                    <th class="bg-blue-sharp font-white bold">API 설명</th>
                    <td>
                        <?= nl2br(html_escape(element('f_exp', element('data', $view)))) ?>
                    </td>
                </tr>
                <tr>
                    <th class="bg-blue-sharp font-white bold">호출 URL</th>
                    <td>
                        <?php $request_url = (element('f_url', element('data', $view)) === '') ? element('f_name', element('data', $view)) : element('f_url', element('data', $view)) . '/' . element('f_name', element('data', $view)); ?>
                        <?php echo site_url('api/' . $request_url); ?>
                    </td>
                </tr>
                <tr>
                    <th class="bg-blue-sharp font-white bold">호출방식</th>
                    <td><?php echo html_escape(element('f_method', element('data', $view))); ?></td>
                </tr>
                <tr>
                    <th class="bg-blue-sharp font-white bold">요청파라미터</th>
                    <td>
                        <form name="test_form"
                              method="<?php echo html_escape(element('f_method', element('data', $view))); ?>"
                              enctype="multipart/form-data">
                            <input type="hidden" name="__method"
                                   value="<?php echo html_escape(element('f_method', element('data', $view))); ?>"/>
                            <input type="hidden" name="pretty" value="TRUE"/>
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                   value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" name="f_api"
                                   value="<?php echo html_escape(element('f_api', element('data', $view))); ?>">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th width="120">변수명</th>
                                    <th width="100">타입</th>
                                    <th width="80">필수</th>
                                    <th width="400">설명</th>
                                    <th>
                                        <button type="button" class="btn btn-xs btn-default"
                                                onClick="testResult('json');">
                                            결과보기
                                        </button>
                                        <button type="button" class="btn btn-xs btn-default"
                                                onClick="location.href = '<?php echo base_url('api_manual') ?>'">
                                            Api 목록으로..
                                        </button>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <!--<tr>
                                    <td>lang</td>
                                    <td>String(Varchar)</td>
                                    <td>필수</td>
                                    <td>언어 (en, ko,...)</td>
                                    <td><input type="text" class="form-control" name="lang" placeholder="ko" /></td>
                                </tr>-->
                                <?php
                                if (element('input', $view)) {
                                    foreach (element('input', $view) as $result) {
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
                                            <td>
                                                <?php
                                                switch (element('f_type', $result)) {
                                                    case 'String(Varchar)':
                                                    case 'Integer':
                                                    case 'Float':
                                                    case 'Object':
                                                    case 'Object Arr':
                                                    case 'Boolean':
                                                        echo '<input type="text" name="' . element('f_name', $result) . '" class="form-control" />';
                                                        break;
                                                    case 'String(Text)':
                                                        echo '<textarea class="form-control" name="' . element('f_name', $result) . '" rows="4" ></textarea>';
                                                        break;
                                                    case 'File':
                                                        echo '<input type="file" name="' . element('f_name', $result) . '" class="form-control" />';
                                                        break;
                                                    case 'Multi Files':
                                                        echo '<input type="file" multiple name="' . element('f_name', $result) . '[]" class="form-control" />';
                                                        break;
                                                } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </form>
                    </td>
                </tr>
                <tr>
                    <th class="bg-blue-sharp font-white bold">응답파라미터</th>
                    <td>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th width="120">변수명</th>
                                <th width="100">타입</th>
                                <th width="80">필수</th>
                                <th>설명</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>res_code</td>
                                <td>Integer</td>
                                <td>필수</td>
                                <td>요청 결과 코드</td>
                            </tr>
                            <tr>
                                <td>res_msg</td>
                                <td>String</td>
                                <td>필수</td>
                                <td>요청 결과 메시지</td>
                            </tr>
                            <?php
                            if (element('output', $view)) {
                                foreach (element('output', $view) as $result) {
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
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th class="bg-blue-sharp font-white bold">결과코드</th>
                    <td>
                        <table class="table table-bordered table-striped col-md-6 no-margin">
                            <tbody>
                            <tr>
                                <td width="5%">0</td>
                                <td width="30%">성공</td>
                                <td width="5%">4</td>
                                <td width="30%">정보가 틀림</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>파라미터누락오류</td>
                                <td>5</td>
                                <td>중복오류</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>DB 오류</td>
                                <td>6</td>
                                <td>권한 오류</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>정보가 존재하지 않음</td>
                                <td>7</td>
                                <td>파일업로드오류</td>
                            </tr>
                            </tbody>
                        </table>

                    </td>
                </tr>
                <tr>
                    <th class="bg-blue-sharp font-white bold">호출결과</th>
                    <td>
                        <iframe src="<?php echo base_url('apidoc/emptypage') ?>" style="width:100%;height:500px;"
                                name="json"></iframe>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>

        <script>
            function testResult(arg) {
                var f = document.test_form;
                f.target = arg;
                f.action = "<?php echo site_url('api/' . $request_url); ?>";
                f.submit();

                $('html, body').animate({scrollTop: window.innerHeight}, 500);
            }
        </script>
    </div>
</div>