<style>
    .row {
        margin-left: 0px;
    }

    .search {
        margin: 10px 0px;
        float: right;
    }

    .search span, .search input, .search button {
        vertical-align: middle;
    }
    
    .search input {
        margin: 0px;
    }

</style>
<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>Origination</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>My DIDs</li>
</ul>

<div>
    <hr/>
</div>

<div class="innerLR">
    <!--NEW HTML-->
    <div class="row">
        <div class="search">
            <span>Search: </span>
            <input type="text" id="search">
            <button id="getCsv" class="btn btn-primary">Export</button>
        </div>
        <table id="didTable" class="footable table table-striped tableTools table-bordered  table-white table-primary default floatThead-table">
            <thead>
            <tr>
                <th>DID</th>
                <th>Trunk</th>
                <th>Type</th>
<!--                <th>Country</th>-->
<!--                <th>State</th>-->
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $encodedWord = base64_encode('DID');

            foreach ($data as $item): ?>
                <tr data-id="<?php echo $item['DidBillingRel']['id']; ?>">
                    <td><?php echo $item['DidBillingRel']['did']; ?></td>
                    <td><?php echo explode("_{$encodedWord}_", $item['Resource']['alias'])[0]; ?></td>
                    <td><?php echo $item['DidBillingRel']['type']; ?></td>
<!--                    <td>--><?php //echo $item['Code']['country']; ?><!--</td>-->
<!--                    <td>--><?php //echo $item['Code']['state']; ?><!--</td>-->
                    <td>
                        <a href="javascript:void(0)" title="Change Trunk" class="edit">
                            <i class="icon-edit"></i>
                        </a>
                        <a href="<?php echo $this->webroot ?>did_client/cancel_did/<?php echo base64_encode($item['DidBillingRel']['id']); ?>" title="Cancel" onclick="return myconfirm('Are you sure to cancel this number?', this);">
                            <i class="icon-remove"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(function () {

        jQuery('a.edit').click(function() {
            jQuery(this).parent().parent().trAdd({
                action: '<?php echo $this->webroot ?>did_client/did_edit_panel/' + jQuery(this).parent().parent().data('id'),
                ajax: '<?php echo $this->webroot ?>did_client/did_edit_panel/' + jQuery(this).parent().parent().data('id'),
                saveType: 'edit',
                onsubmit: function() {


                    return true;
                }
            });
        });

        $('#search').on('keyup', function() {
            var search = $(this).val();

            $("#didTable tbody tr").each(function (index, val) {
                var contain = false;
                $(val).find('td').each(function (subIndex, subVal) {
                    if ($(subVal).text().includes(search)) {
                        contain = true;
                    }
                });

                if (contain) {
                    $(val).parent().show();
                } else {
                    $(val).parent().hide();
                }
            });
        });

        $("#getCsv").click(function () {
            $.ajax({
                url: '<?php echo $this->webroot;?>did_client/dids',
                data: {
                },
                method: 'POST',
                success: function (data) {
                    data = $.parseJSON(data);
                    var text = "DID,Trunk,Type\n";
                    data.forEach(function (value, index, data) {
                        let temp = [
                         value.DidBillingRel.did ,
                             (value.Resource.alias).split('_RElE_')[0],
                             value.DidBillingRel.type
                        ];
                        text += temp.join(',') + "\n";
                    });

                    var csvContent = "data:text/csv;charset=utf-8," + text;
                    var encodedUri = encodeURI(csvContent);
                    var link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", "client_did.csv");
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                }
            });
        });
    });
</script>