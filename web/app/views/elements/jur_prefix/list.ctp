
<div id="noRows" class="msg center">
    <?php if (isset($_GET['search']) && !empty($_GET['search'])) {?>
        <h3>Data for [<?php echo $_GET['search']; ?>] is not found.</h3>
    <?php } else { ?>
        <h3><?php echo __('no_data_found', true); ?></h3>
    <?php } ?>
</div>
<form id="objectForm" method="post" action="<?php echo $this->webroot ?>jurisdictionprefixs/add?page=<?php echo $p->getCurrPage() ?>&size=<?php echo $p->getPageSize() ?>">
    <input type="hidden" id="delete_rate_id" value="" name="delete_rate_id" class="input in-hidden">

    <input type="hidden" value="1" name="page" class="input in-hidden">
    <table style="display: none;" class="list list-form fix-first-col footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="tabid">

        <thead>
            <tr>
                <?php if ($_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_w'])
                {
                    ?>
                    <th style="text-align:center;" data-class="expand"><input type="checkbox" id="selectAll" value=""></input></th><!--		全选    -->
<?php } ?>
            <!--<th style="text-align:center;"><?php echo $appCommon->show_order('id', __('ID', true)) ?></th>-->

                <th style="text-align:center;display: table-cell;" data-hide="phone,tablet" ><?php echo $appCommon->show_order('jurisdiction_country_name', __('Country', true)) ?></th>
                <th style="text-align:center;"><?php echo $appCommon->show_order('jurisdiction_name', __('State', true)) ?></th>

                <th style="text-align:center;display: table-cell;"data-hide="phone,tablet" ><?php echo $appCommon->show_order('prefix', __('Prefix', true)) ?></th>
                <th style="text-align:center;display: table-cell;"data-hide="phone,tablet" ><?php echo $appCommon->show_order('prefix', __('OCN', true)) ?></th>
                <th style="text-align:center;display: table-cell;" data-hide="phone,tablet" ><?php echo $appCommon->show_order('prefix', __('LATA', true)) ?></th>
                <!--th style="text-align:center;" class="value"><?php echo __('Block ID', true) ?></th>
                <th style="text-align:center;display: table-cell;" data-hide="phone,tablet"  class="value"><?php echo $appCommon->show_order('effective_date', __('Effecitve Date', true)) ?></th-->
                <!--<th style="text-align:center;"><?php echo $appCommon->show_order('alias', __('Alias', true)) ?></th>-->
                <?php if ($_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_w'])
                {
                    ?>
                    <th style="text-align:center;display: table-cell;" data-hide="phone,tablet"  class="last"><?php echo __('Action') ?></th><?php } ?>
            </tr>
        </thead>
        <tbody id="rows">
            <tr id="tpl">
                <!--   增加复选框    -->
                <?php if ($_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_w'])
                {
                    ?>
                     <td style="text-align:center;"><input type="checkbox" value="" name="id"></input></td>
<?php } ?>
                <!--<td style="text-align:center;">
                 <small id="tpl-id-text"><?php echo __('code_name', true); ?></small>
                    
                </td>-->
        <input type="hidden" name="id" /> 
        <td class="expand" style="text-align:center;"><input style="max-width:120px;" type="text"  rel="format_name"  name="jurisdiction_country_name" style="text-align:right;" maxlength="256" /></td>
        <td style="text-align:center;"><input style="max-width:120px;" type="text"  rel="format_name"  name="jurisdiction_name" style="text-align:right;" maxlength="256"/></td>
        <td style="text-align:center;"><input style="max-width:120px;" type="text" rel="format_number" name="prefix" style="font-weight:bold;text-align:right;" /></td>
        <td style="text-align:center;display: table-cell;"data-hide="phone,tablet" ><input style="max-width:120px;" type="text" rel="" name="ocn" style="font-weight:bold;text-align:right;" /></td>
        <td style="text-align:center;display: table-cell;"data-hide="phone,tablet" ><input style="max-width:120px;" type="text" rel="format_number" name="lata" style="font-weight:bold;text-align:right;" /></td>
        <!--td style="text-align:center;display: table-cell;" class="value"><input style="max-width:120px;" type="text"  name="block_id" style="font-weight:bold;text-align:right;" /></td>
        <td style="text-align:center;display: table-cell;" class="value"><input style="max-width:120px;" type="text"  name="effective_date" style="font-weight:bold;text-align:right;" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss', lang: 'en'})" /></td-->
                  <!--<td style="text-align:center;"><input type="text"  rel="format_name"  name="alias" style="text-align:right;" /></td>-->
                <?php if ($_SESSION['role_menu']['Switch']['jurisdictionprefixs']['model_w'])
                {
                    ?>
            <td data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;">
                <a href="javascript:void(0)" id="tpl-delete-row" rel="icon_remove" name= 'jurisdiction_name'  ><i class="icon-remove"></i></a></td>
<?php } ?>
        </tr>
        </tbody>
    </table>
</form>
<script language="JavaScript">
    var lastId = 0;
    var eRows = $('#rows');
    var eTpl = $('#tpl').unbind();

    function addItem(row, append)
    {
        lastId++;
        // defaults
        if (!row || !row['id']) {

            row = {
                'effective_date': '',
                'time_profile_id': '',
                'rate': '0.0000',
                'min_time': '1',
                'seconds': '60',
                'interval': '1',
                'grace_time': '0',
                'intra_rate': '0.0000',
                'inter_rate': '0.0000',
                'local_rate': '0.0000',
                'block_id': ''
            };
        }
        // fix row values
        for (k in row) {
            if (row[k] == null)
                row[k] = '';
        }
        // prepare row
        var prefixId = 'row-' + lastId;
        var prefixName = 'rates[' + lastId + ']';
        var tRow = eTpl.clone(true).attr('id', prefixId);//临时准备的行
        jQuery(tRow).find('input[rel=format_number]').xkeyvalidate({type: 'Num'}).attr('maxLength', '16');
        jQuery(tRow).find('input[rel=format_name]').xkeyvalidate({type: 'strNum'}).attr('maxLength', '256');
        // set names / values
        tRow.find('input,select').each(function() {
            var el = $(this);
            var field = el.attr('name');
            el.attr({id: prefixId + '-' + field, name: prefixName + '[' + field + ']'}).val(row[field]);
        });


        // set text labels
        tRow.find('#tpl-id-text').text(row['id'] ? row['id'] : '');

        if (row['id']) {
            tRow.appendTo(eRows);
        } else {
            tRow.prependTo(eRows);
        }

        // styles
        if (!row['id']) {
            initForms(tRow);
            initList();
        }
        $('#noRows').hide();
        $('.list-form').show();
        $('#toppage').show();
        $('#tmppages').show();
        $("#form_footer").show();
    }
    jQuery('input[type=text],input[type=password]').addClass('input in-input in-text');
    jQuery('input[type=button],input[type=submit]').addClass('input in-submit');
    jQuery('select').addClass('select in-select');
    jQuery('textarea').addClass('textarea in-textarea');

    //$('#rows').find('input[rel*=format_number]').live('keyup',function(){filter_chars(this);});
    $("#tpl_remove_row").live('click', function() {
        $(this).closest('tr').remove();
    });

    $('#tpl-delete-row').live('click', function() {
        var del_rate_id = $(this).closest('tr').find('input[name*=id]').val();
        if (!del_rate_id)
        {
            $(this).closest('tr').remove();
            if ($("#rows").children().size() == 0)
            {
                $("#tabid").hide();
                $(".msg").show();
                $("#form_footer").hide();
            }
            return false;
        }
        $(this).attr('href', '<?php echo $this->webroot; ?>jurisdictionprefixs/delete/' + del_rate_id);
        if (myconfirm(" Are you sure to delete prefix " + jQuery(this).closest('tr').find('input[name*=jurisdiction_name]').val() + " ?", this)) {


        }
        if (jQuery('#rows tr').size() == 0) {
            $('#noRows').show();
            $('.list-form').hide();
            $('#toppage').hide();
            $('#tmppages').hide();
        }
        return false;
    });

<?php
$mydata = $p->getDataArray();
foreach ($mydata as $key => $value)
{

    $id = !empty($value[0]['id']) ? $value[0]['id'] : '';
    $prefix = !empty($value[0]['prefix']) ? $value[0]['prefix'] : '';
    $alias = !empty($value[0]['alias']) ? $value[0]['alias'] : '';


    $jurisdiction_country_name = !empty($value[0]['jurisdiction_country_name']) ? $value[0]['jurisdiction_country_name'] : '';
    $jurisdiction_name = !empty($value[0]['jurisdiction_name']) ? $value[0]['jurisdiction_name'] : '';
    $ocn = !empty($value[0]['ocn']) ? $value[0]['ocn'] : '';
    $lata = !empty($value[0]['lata']) ? $value[0]['lata'] : '';
    $block_id = $value[0]['block_id'];
    $effective_date = !empty($value[0]['effective_date']) ? $value[0]['effective_date'] : '';
    echo "addItem({\"id\":\"$id\",\"jurisdiction_country_name\":\"$jurisdiction_country_name\",\"jurisdiction_name\":\"$jurisdiction_name\",\"prefix\":\"$prefix\",\"alias\":\"$alias\", \"ocn\":\"{$ocn}\", \"lata\":\"{$lata}\", \"block_id\":\"{$block_id}\", \"effective_date\":\"{$effective_date}\"}, 1);\n";
}
?>
    eRows.hide();
    eTpl.remove();
    eRows.show();
</script>
<script type="text/javascript">
    <!--
    jQuery(document).ready(function() {
        jQuery('#selectAll').selectAll('input[type=checkbox]');

        if ($("#noRows").length > 0 && $('#noRows').is(':visible')) {
            $(".row-fluid").hide();
        }
        $('.footable-first-column').width('50px');
    });
//-->
</script>

