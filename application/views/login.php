<!-- BEGIN LOGIN -->
<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <form class="login-form" action="<?php echo base_url('login/login') ?>" method="post">
        <h4 class="form-title margin-bottom-40 margin-top-40"><strong>베네픽쳐</strong> 관리자 아이디와 비밀번호를 입력해주세요.</h4>
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            <span></span>
        </div>
        <div class="form-group margin-top-40">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <!--<label class="control-label visible-ie8 visible-ie9">아이디</label>-->
            <label class="control-label">아이디</label>
            <input class="form-control input-lg" type="text" autocomplete="off"
                   placeholder="아이디" name="username"/></div>
        <div class="form-group">
            <!--<label class="control-label visible-ie8 visible-ie9">비밀번호</label>-->
            <label class="control-label">비밀번호</label>
            <input class="form-control input-lg" type="password" autocomplete="off"
                   placeholder="비밀번호" name="password"/></div>
        <div class="form-actions">
            <button type="submit" class="btn green uppercase btn-block btn-lg">로그인</button>
        </div>

    </form>
    <!-- END LOGIN FORM -->
    <!-- END REGISTRATION FORM -->
</div>

<script type="text/javascript">
    jQuery(document).ready(function () {
        $('input[type=text]').focus();

        $('input[type=password]').keypress(function (e) {
            if (e.keyCode === 13) {
                // 엔터건이 눌렸을면 바로 로그인처리
                $('button[type=submit]').click();
            }
        });

        $('.login-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'font-red', // default input error message class
            focusInvalid: true, // focus the last invalid input
            rules: {
                username: {
                    required: true
                },
                password: {
                    required: true
                }
            },
            messages: {
                username: {
                    required: "아이디를 입력하세요."
                },
                password: {
                    required: "비밀번호를 입력하세요."
                }
            }
        });
    });

</script>