<div class="row">
    <div class="col-md-12">
        <table id="tblWinner" class="table table-striped table-bordered table-responsive">
            <thead>
            <tr class="bg-default">
                <th class="text-center">ID</th>
                <th class="text-center">번호</th>
                <th class="text-center">상품명</th>
                <th class="text-center">응모권번호</th>
                <th class="text-center">등수</th>
                <th class="text-center">당첨금액</th>
                <th class="text-center">출금신청여부</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="col-md-12">
        <div class="input-group" style="float: right;width: 450px">
            <span class="input-group-btn">
                <button class="btn" style="background: #26C281" type="button">전체 당첨금액</button>
            </span>
            <label class="form-control" id="total_winner_cost"></label>
            <span class="input-group-btn">
                <span class="btn blue" type="button">원</span>
            </span>
        </div>
    </div>
</div>


<script>

    var winner_table;

    $(function () {

        let round = <?=$max_round?>;

        $(document).ready(function () {

            $('#winner_round').show();
            $('#b_winner_round').empty();

            let ind;
            for (ind = 1; ind <= round; ind++) {
                $('#b_winner_round').append('<option value="' + ind + '">' + ind + '회차</option>');
            }

            $('#b_winner_round').on('change', () => {
                winner_table.draw();
            });

            winner_table = $('#tblWinner').DataTable({
                "language": {
                    "emptyTable": "데이터가 없습니다.",
                    "info": "표시 _START_ - _END_ 항목중 _TOTAL_ 항목",
                    "infoEmpty": "",
                    "search": "<span class='info-label'>Search : </span>",
                    "lengthMenu": "<span class='info-label'>Show : </span> _MENU_",
                    "paginate": {
                        "previous": "이전",
                        "next": "다음",
                        "last": "마지막",
                        "first": "처음"
                    }
                },
                "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row flex'<'col-md-12'p>>",
                "pagingType": "bootstrap_full_number",
                "bStateSave": false,

                "bLengthChange": true,
                "aLengthMenu": [10, 50, 100],
                "bFilter": true,
                "bInfo": false,
                "bSort": false,
                "processing": false,
                "serverSide": true,
                "ajax": {
                    "url": "<?php echo base_url('Winner/winner_table')?>",
                    "type": "GET",
                    "beforeSend": function () {
                        showBlockUI($('#pageContent'));
                    },
                    "dataSrc": (results) => {
                        $('#total_winner_cost').text(results.memberCnt);
                        return results.data;
                    },
                    "data": (data) => {
                        // data.column = $('#column').val();
                        // data.keyword = $('#keyword').val();
                        data.round = $('#b_winner_round').val();
                    },
                    "error": () => {
                        hideBlockUI($('#pageContent'));
                    }
                },
                "drawCallback": function () {
                    hideBlockUI($('#pageContent'));
                },
                "createdRow": function (row, data, dataIndex) {
                    hideBlockUI($('#pageContent'));

                    if (Number(data['status']) === 1)
                        $('td:eq(6)', row).html('<span class="text-primary">신청</span>');
                    else
                        $('td:eq(6)', row).html('<span class="text-danger">미신청</span>');
                }
            });

        });


    });

</script>