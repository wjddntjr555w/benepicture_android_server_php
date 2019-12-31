<style>
    div.img-tile {
        width: 100%;
        height: auto;
        text-align: center;
        border: 1px solid #DDD;
    }

    div.img-tile div.tile-header {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    div.img-tile div.tile-body {

    }

    div.img-tile div.tile-body img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    div.img-tile div.tile-footer {

    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="tabbable-line tabbable-custom-profile nav-justified">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active">
                        <a href="#tab_info" data-toggle="tab">기본정보</a>
                    </li>
                    <li class="">
                        <a href="<?php echo base_url('member/detail_category/' . $member_id) ?>"
                           data-toggle="">카테고리</a>
                    </li>
                    <li class="">
                        <a href="<?php echo base_url('member/detail_board/' . $member_id) ?>" data-toggle="">게시물</a>
                    </li>
                    <li class="">
                        <a href="<?php echo base_url('member/detail_comment/' . $member_id) ?>" data-toggle="">댓글</a>
                    </li>
                    <li class="">
                        <a href="<?php echo base_url('member/detail_like/' . $member_id) ?>" data-toggle="">좋아요글</a>
                    </li>
                    <li class="">
                        <a href="<?php echo base_url('member/detail_temp/' . $member_id) ?>" data-toggle="">임시저장</a>
                    </li>
                    <li class="">
                        <a href="<?php echo base_url('member/detail_favor/' . $member_id) ?>" data-toggle="">즐겨찾기</a>
                    </li>
                    <li class="">
                        <a href="<?php echo base_url('member/detail_follow/' . $member_id) ?>" data-toggle="">프렌즈</a>
                    </li>
                    <li class="">
                        <a href="<?php echo base_url('member/detail_sns/' . $member_id) ?>" data-toggle="">SNS 설정</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_info">
                        <form id="frmUser" role="form" class="form-horizontal" method="post"
                              enctype="multipart/form-data"
                              action="<?php echo base_url('member/save') ?>">
                            <input type="hidden" name="User[f_idx]" value="<?php echo $member->f_idx ?>"/>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="col-md-6">
                                        <div class="img-tile">
                                            <div class="tile-header">
                                                프로필
                                            </div>
                                            <div class="tile-body">
                                                <img src="<?php echo $member->f_profile ?>" class="img"
                                                     id="img_profile"/>
                                                <input type="file" id="profile" name="profile" accept="image/*"
                                                       class="hidden"/>
                                            </div>
                                            <div class="tile-footer">
                                                <button type="button" class="btn bg-dark btn-block font-white btn-profile">찾아보기
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="img-tile">
                                            <div class="tile-header">
                                                배경이미지
                                            </div>
                                            <div class="tile-body">
                                                <img src="<?php echo $member->f_img ?>" class="img" id="img_img"/>
                                                <input type="file" id="img" name="img" accept="image/*" class="hidden"/>
                                            </div>
                                            <div class="tile-footer">
                                                <button type="button" class="btn bg-dark btn-block font-white btn-img">찾아보기</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-body padding-tb-10">
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-2 control-label" for="name">도메인아이디</label>
                                            <div class="col-md-10">
                                                <label class="control-label"><?php echo $member->f_us_id ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-body padding-tb-10">
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-2 control-label" for="name">닉네임</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" id="name"
                                                       name="User[f_nickname]"
                                                       required
                                                       placeholder="닉네임을 입력해주세요"
                                                       value="<?php echo $member->f_nickname ?>">
                                                <div class="form-control-focus"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-body padding-tb-10">
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-2 control-label" for="name">성별</label>
                                            <div class="col-md-10">
                                                <div class="btn-group btn-gender" data-toggle="buttons">
                                                    <label class="btn btn-default">
                                                        <input type="radio" class="toggle" value="1"
                                                               name="User[f_gender]">
                                                        <i class="fa fa-check"></i>남성
                                                    </label>
                                                    <label class="btn btn-default">
                                                        <input type="radio" class="toggle" value="2"
                                                               name="User[f_gender]">
                                                        <i class="fa fa-check"></i>여성
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-body padding-tb-10">
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-2 control-label" for="name">나이</label>
                                            <div class="col-md-10">
                                                <select class="form-control" id="age" name="User[f_age]">
                                                    <option value="0">0~9</option>
                                                    <option value="1">10~19</option>
                                                    <option value="2">20~29</option>
                                                    <option value="3">30~39</option>
                                                    <option value="4">40~49</option>
                                                    <option value="5">50~59</option>
                                                    <option value="6">60~69</option>
                                                    <option value="7">70~79</option>
                                                    <option value="8">80~89</option>
                                                    <option value="9">90~99</option>
                                                </select>
                                                <div class="form-control-focus"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-body padding-tb-10">
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-2 control-label" for="name">가입일시</label>
                                            <div class="col-md-10">
                                                <label class="control-label"><?php echo $member->f_reg_time ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-body padding-tb-10">
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-2 control-label" for="name">최종접속일시</label>
                                            <div class="col-md-10">
                                                <label class="control-label"><?php echo $member->f_login_time ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-2 col-md-10">
                                                <button type="submit" id="btnSave" class="btn btn-lg btn-success">저장
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        $(function () {

            $(document).ready(function () {

                $('button.btn-profile').on('click', function () {
                    $('#profile').trigger('click');
                });

                $('button.btn-img').on('click', function () {
                    $('#img').trigger('click');
                });

                $('div.tile-body').css('height', $('div.tile-body').width() + 'px');

                $('div.btn-gender').find('label:eq(0)').addClass('<?php echo $member->f_gender == 1 ? 'active' : ''?>')
                    .bind('click', function () {
                        $('label.btn.active').removeClass('active')
                            .find('i').addClass('invisible');
                        $(this).addClass('active')
                            .find('i').removeClass('invisible');
                    })
                    .find('i')
                    .addClass('<?php echo $member->f_gender == 1 ? '' : 'invisible'?>');
                $('div.btn-gender').find('label:eq(1)').addClass('<?php echo $member->f_gender == 1 ? '' : 'active'?>')
                    .bind('click', function () {
                        $('label.btn.active').removeClass('active')
                            .find('i').addClass('invisible');
                        $(this).addClass('active')
                            .find('i').removeClass('invisible');
                    })
                    .find('i')
                    .addClass('<?php echo $member->f_gender == 1 ? 'invisible' : ''?>');
                $('#age').val(<?php echo $member->f_age % 10?>);
            });

            $('#img').change(function (e) {
                var file = e.target.files[0];

                if (file) {
                    var reader = new FileReader();
                    var image = new Image();

                    reader.readAsDataURL(file);
                    reader.onload = function (_file) {
                        image.src = _file.target.result;              // url.createObjectURL(file);
                        image.onload = function () {
//                        var w = this.width,
//                            h = this.height,
//                            s = Math.floor(file.size / 1024 / 1024);
//
//                        if (w != 275 || h != 275) {
//                            alert('이미지권장사이즈를 확인해주세요');
//                            $(e).val("");
//                            return;
//                        }
//
//                        if (s > 10) {
//                            alert('10MB 이하의 파일들만 업로드할수 있습니다.');
//                            $(e).val("");
//                            return;
//                        }
                            $('#img_img').attr('src', image.src);
                        };
                        image.onerror = function () {
                            alert('Invalid file type: ' + file.type);
                            $(e).val("");
                        };
                    };
                }
            });

            $('#profile').change(function (e) {
                var file = e.target.files[0];

                if (file) {
                    var reader = new FileReader();
                    var image = new Image();

                    reader.readAsDataURL(file);
                    reader.onload = function (_file) {
                        image.src = _file.target.result;              // url.createObjectURL(file);
                        image.onload = function () {
//                        var w = this.width,
//                            h = this.height,
//                            s = Math.floor(file.size / 1024 / 1024);
//
//                        if (w != 275 || h != 275) {
//                            alert('이미지권장사이즈를 확인해주세요');
//                            $(e).val("");
//                            return;
//                        }
//
//                        if (s > 10) {
//                            alert('10MB 이하의 파일들만 업로드할수 있습니다.');
//                            $(e).val("");
//                            return;
//                        }
                            $('#img_profile').attr('src', image.src);
                        };
                        image.onerror = function () {
                            alert('Invalid file type: ' + file.type);
                            $(e).val("");
                        };
                    };
                }
            });

        });

    </script>
</div>
