<style>
    input.checkbox-left {
        display: block;
        margin:0 auto;
    }
</style>
        <script type="text/javascript">
    function add() {
        if (jQuery('.add').html() != null) {
            return;
        }
        jQuery('table.list').show();
        jQuery('.msg').hide();
        jQuery('table.list tbody').prepend(
                jQuery('<tr/>').append(
                jQuery('<td/>').html('<input style="display: none;">')
                ).append(
                jQuery('<td/>').append(jQuery('<input class="marginTop9 width90 input in-text" maxLength="256">').xkeyvalidate({type: 'strName'}))
                ).append(
                jQuery('<td/>').html('<input style="display: none;">')
                ).append(jQuery('<td />')).append(jQuery('<td />')).append(
                jQuery('<td/>').html('<a onclick="save_code(this.parentNode.parentNode);" href="javascript:void(0)" style="" class="marginTop9"><i class="icon-save"></i> </a><a onclick="jQuery(&quot;#rec_strategy&quot;).removeAttr(&quot;add&quot;);this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)" href="javascript:void(0)" style=" margin-left: 10px;" class="marginTop9"><i class="icon-remove"></i></a>')
                ).addClass('add')
                );
        jQuery('table.list tr:nth-child(2n+1)').addClass('row-1').removeClass('row-2');
        jQuery('table.list tr:nth-child(2n)').addClass('row-2').removeClass('row-1');
        return;
    }
    function save_code(tr) {
        var params = {
            name: tr.cells[1].getElementsByTagName('input')[0].value
        };
        if (!/^[0-9a-zA-Z_][0-9a-zA-Z_ \|\.\=\-]+[0-9a-zA-Z_]$/.test(params['name'])) {
            jQuery(tr.cells[1].getElementsByTagName('input')[0]).addClass('invalid');
            jGrowl_to_notyfy('Name,allowed characters: a-z,A-Z,0-9,-,_,space!', {theme: 'jmsg-error'});
            return false;
        }
        jQuery.post('<?php echo $this->webroot ?>routestrategys/add', params, function (data) {
            var tmp = data.split("|");
            var p = {theme: 'jmsg-success', life: 100};
            if (tmp[1].trim() == 'false') {
                p = {theme: 'jmsg-alert', life: 500};
                jGrowl_to_notyfy(tmp[0], p);
            }
            else {
                jGrowl_to_notyfy(tmp[0], p);
                var require_comment = "<?php echo $require_comment; ?>";
                if (require_comment != '0')
                {
                    location.href = '<?php echo $this->webroot ?>logging/index/' + tmp[3].trim() + '/routestrategys-routes_list-' + tmp[2].trim();
                }else{
                    location.href = '<?php echo $this->webroot ?>routestrategys/routes_list/' + tmp[2].trim();
                }
            }
        });
    }
    function edit(currRow) {
        var columns = [{}, {className: ' width90 input in-text  check_strNum'}, {}, {}, {}, {}];
        editRow(currRow, columns);
        var btn = currRow.cells[5].getElementsByTagName("a")[0];

        if (btn) {
            var btn2 = currRow.cells[5].getElementsByTagName("a")[1];
            var cancel = currRow.cells[5].getElementsByTagName("a")[1].cloneNode(true);
            cancel.title = "<?php __('cancel') ?>";
            cancel.getElementsByTagName("i")[0].className = "icon-remove";
            btn2.innerHTML = '';
            $(cancel).attr("href", 'javascript:void(0)');
            $(cancel).removeAttr("onclick");
            cancel.onclick = function () {
                location.reload();
            }
            currRow.cells[5].appendChild(cancel);
            btn.getElementsByTagName("i")[0].className = "icon-save";
            btn.title = "<?php __('Save') ?>";
            btn.setAttribute("oldtitle", "");
            $(btn).qtip({
                style: {
                    classes: 'qtip-shadow qtip-tipsy'
                }
            });
            jQuery(btn).attr('style', 'margin-left:31px');
            btn.onclick = function () {
                var location = "";
                var params = {
                    name: currRow.cells[1].getElementsByTagName('input')[0].value,
                    id: currRow.cells[0].getElementsByTagName('input')[0].value};
                if (!/^[0-9a-zA-Z_][0-9a-zA-Z_ \|\.\=\-]+[0-9a-zA-Z_]$/.test(params['name'])) {
                    jGrowl_to_notyfy('Name,allowed characters: a-z,A-Z,0-9,-,_,space!', {theme: 'jmsg-error'});
                    return false;
                }
                jQuery.post('<?php echo $this->webroot ?>routestrategys/update', params, function (data) {

                    var p = {theme: 'jmsg-success', beforeClose: function () {
                            var str = location.toString();
                            if (str.indexOf("?") != '-1') {
                                alert(1);
                                location = location;

                            } else {
                                location = location.toString() + "?edit_id=" + params.id;
                            }

                        }, life: 100};
                    var tmp = data.split("|");
                    if (tmp[1].trim() == 'false')
                        p = {theme: 'jmsg-alert', life: 500};
                    jGrowl_to_notyfy(tmp[0], p);
                    var require_comment = "<?php echo $require_comment; ?>";
                    if (require_comment != '0')
                    {
                        window.location.href = "<?php echo $this->webroot; ?>logging/index/" + tmp[2].trim() + "/routestrategys-strategy_list";
                    }else{
                        window.location = location;
                    }

                });
                //window.location = location;
            }
        }
        jQuery('input.check_strNum').xkeyvalidate({type: 'strNum'}).attr('maxLength', '256');
    }
</script>
<div id="cover"></div>
<?php
$mydata = $p->getDataArray();
$loop = count($mydata);
?>
<?php $w = $session->read('writable') ?>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>routestrategys/strategy_list">
        <?php __('Routing') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>routestrategys/strategy_list">
        <?php echo __('Routing Plan') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Routing Plan') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php
    if ($_SESSION['role_menu']['Routing']['routestrategys']['model_w'])
    {
        ?>
        <a class="btn btn-primary btn-icon glyphicons circle_plus" id="add" href="javascript:void(0)" onclick="add();"><i></i> <?php __('Create New') ?></a>
    <?php if ($loop > 0): ?>
            <a class="link_btn delete_selected btn btn-primary btn-icon glyphicons remove" onclick="deleteAll('<?php echo $this->webroot ?>routestrategys/del_strategy/all/<?php echo isset($_GET['filter_static']) ? $_GET['filter_static'] : ''; ?>');" href="###"><i></i> <?php echo __('Delete All') ?></a>
            <a class="link_btn delete_selected btn btn-primary btn-icon glyphicons remove" onclick="deleteSelected('rec_strategy', '<?php echo $this->webroot ?>routestrategys/del_strategy/selected', 'routing plan');" href="###"><i></i> <?php echo __('Delete Selected') ?></a>
        <?php endif; ?>
    <?php } ?>
    <?php
    if (isset($edit_return))
    {
        ?>
        <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot ?>routestrategys/strategy_list"><i></i> <?php __('Back'); ?></a>
<?php } ?>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <form method="get">
                <div class="filter-bar">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search') ?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                    </div>
                    <!-- // Filter END -->

                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query') ?></button>
                    </div>
                    <!-- // Filter END -->

                </div>
                <div class="clearfix"></div>
            </form>
            <div class="clearfix"></div>


            <?php
            if ($loop > 0):
                ?>
                <div class="clearfix"></div>

                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

                    <thead>
                        <tr>
                            <th style="padding: 5px;"><input type="checkbox" class="checkbox-left checkAll" value=""/></th>
                            <!--<th><?php echo $appCommon->show_order('route_strategy_id', __('ID', true)) ?></th>-->
                            <th><?php echo $appCommon->show_order('name', __('Name', true)) ?></th>
                            <th><?php echo $appCommon->show_order('routes', __('Usage Count', true)) ?></th>
                            <th><?php echo __('Update At', true); ?></th>
                            <th><?php echo __('Update By', true); ?></th>
                                <?php
                                if ($_SESSION['role_menu']['Routing']['routestrategys']['model_w'])
                                {
                                    ?> <th class="last"><?php echo __('action') ?></th><?php } ?>
                        </tr>
                    </thead>
                    <tbody id="rec_strategy">
    <?php
    for ($i = 0; $i < $loop; $i++)
    {
        ?>
                            <tr class="row-1">
                                <td style="text-align:center"><input type="checkbox" class="checkbox-left" value="<?php echo $mydata[$i][0]['route_strategy_id'] ?>"/></td>
                                <!--<td><?php echo $mydata[$i][0]['route_strategy_id'] ?></td>-->
                                <td style="font-weight: bold;"><a href="<?php echo $this->webroot ?>routestrategys/routes_list/<?php echo base64_encode($mydata[$i][0]['route_strategy_id']) ?>" class="link_width"><?php echo $mydata[$i][0]['name'] ?></a></td>
                                <td><a href="###" onclick="javascript:(window.location.href = $(this).attr('url'));" url="<?php echo $this->webroot ?>prresource/gatewaygroups/view_ingress?resource_prefix=<?php echo $mydata[$i][0]['route_strategy_id'] ?>"><?php echo $mydata[$i][0]['routes'] ?></a></td>
                                <td><?php echo $mydata[$i][0]['update_at']; ?></td>
                                <td><?php echo $mydata[$i][0]['update_by']; ?></td>
                                       <?php
                                       if ($_SESSION['role_menu']['Routing']['routestrategys']['model_w'])
                                       {
                                           ?><td align="center">
                                        <a title="<?php echo __('edit') ?>" href="javascript:void(0)" onclick="edit(this.parentNode.parentNode)"> <i class="icon-edit"></i> </a>
                                        <a title="<?php echo __('del') ?>" onclick="myconfirm('Are you sure to delete routing plan[<?php echo $mydata[$i][0]['name'] ?>] ?', this);
                                               return false;"  href="<?php echo $this->webroot ?>routestrategys/del_strategy/<?php echo $mydata[$i][0]['route_strategy_id'] ?>" style="margin-left: 10px;" > <i class="icon-remove"></i> </a>
                                    </td>
        <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
    <?php echo $this->element('page'); ?>
                    </div> 
                </div>
                <div class="clearfix"></div>
<?php else: ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none;">
                    <thead>
                        <tr>
                            <th style="padding: 5px;"><input type="checkbox" class="checkbox-left checkAll" value=""/></th>
                            <!--<th><?php echo $appCommon->show_order('route_strategy_id', __('ID', true)) ?></th>-->
                            <th><?php echo $appCommon->show_order('name', __('Name', true)) ?></th>
                            <th><?php echo $appCommon->show_order('routes', __('Usage Count', true)) ?></th>
                            <th><?php echo __('Update At', true); ?></th>
                            <th><?php echo __('Update By', true); ?></th>
    <?php
    if ($_SESSION['role_menu']['Routing']['routestrategys']['model_w'])
    {
        ?> <th><?php echo __('Action') ?></th><?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
<?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
<?php if (!$loop && !isset($_GET['search'])): ?>
            $("#add").click();
<?php endif; ?>

    });
</script>
<script>
    $(document).ready(function () {
        $('.checkAll').on('click', function(){
            $('tbody > tr:visible').find('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
        });
    });
</script>
