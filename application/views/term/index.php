<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <tbody>
            <tr>
                <th style="width: 10%;">이용약관</th>
                <td>
                    <textarea class="form-control" id="service" rows="10"><?php echo $service ?></textarea>
                </td>
            </tr>
            <tr>
                <th>개인정보취급방침</th>
                <td>
                    <textarea class="form-control" id="privacy" rows="10"><?php echo $privacy ?></textarea>
                </td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td class="text-center" colspan="2"><button type="button" class="btn btn-success col-md-2">저장</button> </td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<script type="text/javascript">

    $(() => {
        $('button').on('click', () => {
            showConfirm(CONFIRM_SAVE, () => {
                $.post(
                    '<?php echo base_url('term/save')?>',
                    {
                        service: $('#service').val(),
                        privacy: $('#privacy').val()
                    }, (response) => {
                        if (response.trim() === 'success') {
                            showToast('info', '성공적으로 저장되었습니다', '알림');
                        } else {
                            showToast('error', response, '오류');
                        }
                    }
                )
            });
        });
    });

</script>
