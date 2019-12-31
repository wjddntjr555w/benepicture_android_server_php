<div class="row">
    <div class="col-md-12">
        <table id="tblAdvertise" class="table table-striped table-bordered table-responsive">
            <thead>
            <tr class="bg-default">
                <th class="text-center">광고명</th>
                <th class="text-center">노출회수</th>
                <th class="text-center">타깃성별</th>
                <th class="text-center">연령</th>
                <th class="text-center">1회 노출시 적립금</th>
                <th class="text-center">관리자적립금</th>
                <th class="text-center">광고유형</th>
                <th class="text-center">상태</th>
                <th class="text-center"></th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
    $(function () {

        $('.btn-search').on('click', () => {
            table.draw();
        });

        $('#b_keyword').select2();
        $('#f_user').select2();

        table = $('#tblAdvertise').DataTable({
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
                "url": "<?php echo base_url('Myadvertise/table')?>",
                "type": "GET",
                "beforeSend": function () {
                    showBlockUI($('#pageContent'));
                },
                "dataSrc": (results) => {
                    // $('h4.inline').text('전체 광고수 : ' + results.memberCnt + ' 개');
                    return results.data;
                },
                "data": (data) => {
                    // data.column = $('#column').val();
                    // data.keyword = $('#keyword').val();
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

                var btnEdit = $('<a class="text-primary">자세히보기</a>')
                    .bind('click', data, function (e) {
                        goDetail(e.data);
                    });

                $('td:eq(8)', row).html(btnEdit);
                $('td:eq(2)', row).html(data['target_gender'] == 'M' ? '남' : (data['target_gender'] == 'F'?'여':'상관없음'));
                $('td:eq(6)', row).html(data['type'] == '<?=ADVERTISE_GAME?>' ? '게임' : '동영상');

            }
        });
    });

    function goDetail(advertise) {
        location.href="<?=base_url('Myadvertise/detail')?>" + '?id=' + advertise['id'];
    }
</script>