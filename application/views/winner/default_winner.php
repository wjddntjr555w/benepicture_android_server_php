<style>
    .input-row > input {
        width: 200px;
        display: inline-block;
        font-size: 30px !important;
        text-align: center;
    }

    .input-row {
        font-size: 30px;
        font-weight: bold;
        background: #E7EAEB;
        padding: 5px 10px;
    }

    .winner-btn {
        font-size: 20px !important;
        height: auto !important;
        padding: 8px 14px 7px;
    }

    .default-color {
        background: #493D55;
        color: white;
    }

    .select2-selection--single {
        margin-top: 17px !important;
        border: none !important;
        border-bottom: 2px solid #493d55 !important;
        padding: 0 5px !important;
        box-sizing: border-box !important;
        font-size: 20px;
        outline: none;
    }
</style>

<div class="portlet light ">
    <div class="portlet-body form">
        <form role="form" class="form-horizontal">
            <div class="form-body">
                <div class="input-row">
                    <span>다음</span>
                    <input type="number" class="form-control" id="b_round" value="<?= $round ?>">
                    <span>회차 1등 당첨자 설정</span>
                </div>
                <div class="margin-top-40">
                    <div class="input-group input-group-sm">
                        <span class="input-group-btn btn-left">
                            <label class="default-color winner-btn">회원검색</label>
                        </span>
                        <div class="input-group-control">
                            <select class="form-control default-select" id="b_default_winner">
                                <option value=""></option>
                                <?php
                                if (count($user_list) > 0) {
                                    foreach ($user_list as $user) {
                                        ?>
                                        <option value="<?= $user->f_idx ?>"><?= $user->f_name ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <span class="input-group-btn btn-right">
                            <button class="btn btn-default winner-btn" type="button"
                                    onclick="cancelDefaultWinner()">취소</button>
                            <button class="btn btn-primary winner-btn" type="button"
                                    onclick="changeDefaultWinner()">저장</button>
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#b_default_winner').select2();
        $('#b_default_winner').val('');
    });

    function changeDefaultWinner() {
        var winner = $('#b_default_winner').val();
        var round = $('#b_round').val();

        if (winner == '') {
            showToast('warning', '회원을 선택해주세요.', '알림');
            return false;
        }

        if (round == '') {
            showToast('warning', '회차수를 입력해주세요.', '알림');
            return false;
        }

        $.ajax({
            url: "<?=base_url('Winner/save_default_winner')?>",
            type: 'post',
            data: {winner: winner, round: round},
            success: function (result) {
                if (result == 'success')
                    showToast('success', '저장되었습니다.', '알림');
                else
                    showToast('error', serverErrMsg(), '알림');
            }
        })
    }

    function cancelDefaultWinner() {
        var round = $('#b_round').val();

        if (round == '') {
            showToast('warning', '회차수를 입력해주세요.', '알림');
            return false;
        }

        $.ajax({
            url: "<?=base_url('Winner/cancel_default_winner')?>",
            type: 'post',
            data: {round: round},
            success: function (result) {
                if (result == 'success') {
                    showToast('success', '취소되었습니다.', '알림');
                    $('#b_default_winner').val('');
                } else
                    showToast('error', serverErrMsg(), '알림');
            }
        })
    }
</script>