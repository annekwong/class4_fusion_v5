<style type="text/css">
    #editor {
        padding:30px;
    }
</style>

<form action="" method="post" name="myform" id="myform">
    <div id="myModal_massedit" class="modal hide">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">&times;</button>
            <h3><?php __('Mass Edit'); ?></h3>
        </div>
        <div class="modal-body">
            <table class="table table-bordered">
                <tr>
                    <td class="right"><?php __('Routing rule'); ?></td>
                    <td>
                        <select name="routingrule">
                            <option selected="selected" value=""></option>
                            <option value="4"><?php __('Largest ASR')?></option>
                            <option value="5"><?php __('Largest ACD')?></option>
                            <option value="6"><?php __('LCR')?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="right"><?php __('Time Profile'); ?></td>
                    <td>
                        <select name="timeprofile">
                            <option selected="selected" value=""></option>
                            <?php foreach ($user as $k => $v): ?>
                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="right"><?php __('Modify Type'); ?></td>
                    <td>
                        <select name="edit_type" >
                            <option value=""></option>
                            <option value="1"><?php __('Add')?></option>
                            <option value="2"><?php __('Delete')?></option>
                            <option value="3"><?php __('Replace the current trunk')?></option>
                        </select>
                    </td>
                </tr>
            </table>
            <h1><button id="addtrunk" class="input in-button btn"><?php echo __('Add', true); ?></button></h1>
            <table id="tbl" class="mylist footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                <tr>
                    <th width="40%"><?php echo __('Carriers', true); ?></th>
                    <th width="40%"><?php echo __('Trunk Name', true); ?></th>
                    <th width="20%"><?php __('Action')?></th>
                <tr>
                </thead>
                <tbody>
                <tr>
                    <td width="40%">
                        <select  onchange="updatetrunks(this)" name="carriers">
                            <?php foreach ($carriers as $carrier): ?>
                                <option value="<?php echo $carrier[0]['id']; ?>"><?php echo $carrier[0]['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td width="40%">
                        <select name="trunks[]">
                        </select>
                    </td>
                    <td width="20%">
                        <a href="###" onclick="removeitem(this);"><i class='icon-remove'></i></a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <input type="button" id="massbtn" class="btn btn-primary" value="<?php __('Submit'); ?>">
            <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default close_btn"><?php __('Close'); ?></a>
        </div>
    </div>
</form>

<script type="text/javascript">
    jQuery(function($) {
        $("#myModal_massedit").on('shown',function(){
            var selected = $('.routelist tbody input[type=checkbox]:checked').size();
            if (selected == 0){
                jGrowl_to_notyfy("You must select at least one record that you want to modify!", {theme: 'jmsg-error'});
                $(this).find('.close_btn').click();
                return false;
            }
        });

        $('#tbl select').prepend('<option selected="selected" value=""></option>');
        $('#selectall').click(function() {
            if ($(this).attr('checked')) {
                $('.mylist input[type=checkbox]').attr('checked', 'true');
            } else {
                $('.mylist input[type=checkbox]').removeAttr('checked');
            }
        });
        $('#addtrunk').click(function() {
            $('#tbl tbody tr').clone(true).appendTo('#tbl tbody');
            return false;
        });

        $('#massbtn').click(function() {
            var $this = $(this);
            var arr = new Array();
            $('.routelist tbody input[type=checkbox]:checked').each(function() {
                //if($(this).attr('checked')) {
                arr.push($(this).attr('control'));
                //}
            });
            if (arr.length == 0) {
                jGrowl_to_notyfy("You must select at least one record that you want to modify!", {theme: 'jmsg-error'});
                return false;
            }
            $.ajax({
                url: '<?php echo $this->webroot; ?>dynamicroutes/massedit',
                type: "POST",
                dataType: 'text',
                data: $('#myform').serialize() + "&ids=" + arr.join(","),
                success: function(data) {
                    if(data)
                    {
                        jGrowl_to_notyfy(data, {theme: 'jmsg-error'});
                        return;
                    }
                    $this.next().click();
                    jGrowl_to_notyfy('<?php __('Mass Edit successfully'); ?>', {theme: 'jmsg-success'});
                    window.setTimeout(function() {window.location.reload(true)},1000);
                }
            });
            return false;
        });
    });
    function updatetrunks(elem) {
        $.getJSON('<?php echo $this->webroot ?>trunks/ajax_options?filter_id=' + $(elem).val() + '&type=egress', function(data) {
            $brother = $(elem).parent().next().find('select');
            $brother.empty();
            $.each(data, function(idx, item) {
                $('<option value="' + item.resource_id + '">' + item.alias + '</option>')
                    .appendTo($brother);
            });
        })
    }

    function removeitem(elem) {
        if ($('#tbl tbody tr').length == 1) {
            return;
        }
        $(elem).parent().parent().remove();
    }
</script>