<script src="<?php echo $this->webroot ?>js/BubbleSort.js" type="text/javascript"></script>
<?php $d = $p->getDataArray(); ?>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>products/product_list">
        <?php __('Routing') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>products/product_list">
        <?php echo __('Static Routing') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Static Routing') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php if ($_SESSION['role_menu']['Routing']['products']['model_w'])
    {
        ?>
        <a class="link_btn btn btn-primary btn-icon glyphicons circle_plus" rel="popup"  href="javascript:void(0)" onclick="cover('addproduct')">
            <i></i><?php echo __('createnew') ?>
        </a>
    <?php } ?>
    <?php if (count($d) > 0): ?>
        <?php if ($_SESSION['role_menu']['Routing']['products']['model_w'])
        {
            ?> <a class="link_btn btn btn-primary btn-icon glyphicons remove" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot ?>products/del_all_pro');" ><i></i> <?php echo __('deleteall') ?></a>
            <a class="link_btn btn btn-primary btn-icon glyphicons remove" rel="popup" href="javascript:void(0)" onclick="ex_deleteSelected('producttab', '<?php echo $this->webroot ?>products/del_selected_pro', 'static route table');"><i></i> <?php echo __('deleteselected') ?></a>
        <?php } ?>
    <?php endif; ?>

</div>
<div class="clearfix"></div>
<?php
$codeDecks = array();
if (!empty($code_decks))
{
    foreach ($code_decks as $code_deck)
    {
        $codeDecks[$code_deck[0]['code_deck_id']] = $code_deck[0]['name'];
    }
}
?>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
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
                </form>
            </div>
            <div class="clearfix"></div>


            <?php if (count($d) == 0)
            {
                ?>
                <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                <div class="overflow_x">
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none">
                        <thead>
                        <tr>
                            <th><input type="checkbox" class="checkAll" value=""/></th>
                            <!--		<th><?php echo $appCommon->show_order('product_id', ' ID ') ?></th>-->
                            <th><?php echo $appCommon->show_order('name', __('produname', true)) ?></th>
                            <th><?php __('Defined By') ?></th>
                            <th><?php __('Code Deck') ?></th>
                            <th><?php __('Route By') ?></th>
                            <th><?php echo $appCommon->show_order('modify_time', __('updateat', true)) ?></th>
                            <th><?php echo $appCommon->show_order('routes', __('ofroutes', true)) ?></th>
                            <th><?php echo $appCommon->show_order('ingress', __('routecount', true)) ?></th>
                            <th><?php echo __('Update By', true); ?></th>
                            <?php if ($_SESSION['role_menu']['Routing']['products']['model_w'])
                            {
                                ?><th><?php echo __('action') ?></th><?php } ?>
                        </tr>
                        </thead>
                    </table>
                </div>
            <?php
            }
            else
            {
                ?>
                <div class="overflow_x">
                    `                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="">
                        <thead>
                        <tr>
                            <th><input type="checkbox" class="checkAll" value=""/></th>
                            <!--		<th><?php echo $appCommon->show_order('product_id', __('ID', true)) ?></th>-->
                            <th><?php echo $appCommon->show_order('name', __('produname', true)) ?></th>
                            <th><?php __('Defined By') ?></th>
                            <th><?php __('Code Deck') ?></th>
                            <th><?php __('Route By') ?></th>
                            <th><?php echo $appCommon->show_order('modify_time', __('updateat', true)) ?></th>
                            <th><?php echo $appCommon->show_order('routes', __('ofroutes', true)) ?></th>
                            <th><?php echo $appCommon->show_order('ingress', __('routecount', true)) ?></th>
                            <th><?php echo __('Update By', true); ?></th>
                            <?php if ($_SESSION['role_menu']['Routing']['products']['model_w'])
                            {
                                ?><th><?php echo __('action') ?></th><?php } ?>
                        </tr>
                        </thead>
                        <tbody id="producttab">
                        <?php
                        $mydata = $p->getDataArray();
                        $loop = count($mydata);
                        for ($i = 0; $i < $loop; $i++)
                        {
                            ?>
                            <tr id="line<?php echo $i + 1; ?>">

                                <td><input type="checkbox" value="<?php echo $mydata[$i][0]['id'] ?>"/></td>
                                <!--		    		<td class="in-decimal"  style="text-align: center;"><?php echo $mydata[$i][0]['id'] ?></td>-->
                                <td style="font-weight: bold;">
                                    <a style="width:200px;display:block" href="<?php echo $this->webroot ?>products/route_info/<?php echo $mydata[$i][0]['id'] ?>" class="link_width" >
                                        <?php echo substr($mydata[$i][0]['name'], 0, 40) ?>
                                    </a>
                                </td>
                                <td><?php
                                    if ($mydata[$i][0]['code_type'] == 0)
                                    {
                                        echo "Code";
                                    }
                                    else if ($mydata[$i][0]['code_type'] == 1)
                                    {
                                        echo "Code Name";
                                    }
                                    else if ($mydata[$i][0]['code_type'] == 2)
                                    {
                                        echo "Country";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if (!empty($mydata[$i][0]['code_deck_id']))
                                    {
                                        echo isset($codeDecks[$mydata[$i][0]['code_deck_id']]) ? $codeDecks[$mydata[$i][0]['code_deck_id']] : '';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php echo $mydata[$i][0]['route_lrn'] == 0 ? 'DNIS' : 'LRN'; ?>
                                </td>
                                <td><?php echo $mydata[$i][0]['m_time'] ?></td>
                                <td><a style="width:80%;display:block" href="<?php echo $this->webroot ?>products/route_info/<?php echo $mydata[$i][0]['id'] ?>" class="link_width" ><?php echo $mydata[$i][0]['routes'] ?></a></td>

                                <td align="center"><a style="width:80%;display:block" href="<?php echo $this->webroot ?>routestrategys/strategy_list?filter_static=<?php echo $mydata[$i][0]['id'] ?>" class="link_width"><?php echo $mydata[$i][0]['ingress'] ?></a></td>
                                <td><?php echo $mydata[$i][0]['update_by'] ?></td>
                                <?php if ($_SESSION['role_menu']['Routing']['products']['model_w'])
                                {
                                    ?> <td >
                                    <a id="edit"  title="<?php echo __('edit') ?>" role_id="<?php echo $mydata[$i][0]['id'] ?>"  style="margin-left:10px;" href="javascript:void(0)" onclick="modifyName('<?php echo $this->webroot ?>',this,'products','<?php echo __('pro_update_success') ?>',3);">
                                        <i class="icon-edit"></i>
                                    </a>
                                    <a title="<?php echo __('del') ?>" href="javascript:void(0)" onclick="ex_delConfirm(this, '<?php echo $this->webroot ?>products/delbyid/<?php echo $mydata[$i][0]['id'] ?>', 'static route <?php echo $mydata[$i][0]['name'] ?>');">
                                        <i class="icon-remove"></i>
                                    </a>
                                    <a title="<?php echo __('Copy') ?>" href="javascript:void(0)" onclick="jQuery('#copyratetmp').dialog('open').find('#tmpid').val('<?php echo $mydata[$i][0]['id'] ?>')">
                                        <i class="icon-copy"></i>
                                    </a>
                                </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="separator"></div>
                <div class="row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php } ?>
        </div>
    </div>
</div>
<div id="copyratetmp" class="hide form-horizontal">
    <input class="input in-input in-text" style="display:none;" id="tmpid"/>
    <div class="control-group">
        <label for="balance" class="control-label"><?php echo __('Name', true); ?></label>
        <div class="controls">
            <input class="input in-text in-input" type="text" id="pname"/>
        </div>
    </div>
    <div class="center">
        <input type="button" onclick="copy_route();" value="<?php echo __('submit', true); ?>" class="input in-button in-submit btn btn-primary"/>
    </div>

</div>
<script type="text/javascript">

    $(function () {
        <?php if (!count($d) && empty($_GET['search'])): ?>
        cover();
        <?php endif; ?>
        jQuery('#copyratetmp').dialog({autoOpen: false, width: '400px'});

        $('table tr td a#edit').click(function(){
            var val = $(this).parent().parent().find('td:eq(2) input').val();
            $(this).parent().parent().find('td:eq(2)').html(val);
        });
    });

    function copy_route() {
        var id = jQuery('#copyratetmp').find('#tmpid').val();
        var name = jQuery('#copyratetmp').find('#pname').val();
        if (name == "") {
            jQuery.jGrowlError('Rate Table \'s Name is required');
            return false;
        } else {
            if (!/^[0-9a-zA-Z_][0-9a-zA-Z_ \|\.\=\-]{1,62}[0-9a-zA-Z_]$/.test(name)) {
                jQuery.jGrowlError('Name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 64 characters!');
                return false;
            }
        }
        var data = jQuery.ajaxData('<?php echo $this->webroot ?>products/check_name?name=' + name);
        if (!data.indexOf('false')) {
            jQuery.jGrowlError('name is repeat');
            return;

        }
        location = "<?php echo $this->webroot ?>products/copy?id=" + id + "&name=" + name;
    }


    //该方法覆盖默认方法
    function order() {
        var div = jQuery('<div/>')
        var input = jQuery("<input type='checkbox'>");
        var label = jQuery('<label/>').append('id');
        var select = jQuery('<select style="width:60px"/>').append("<option value='asc'>asc</option><option value='desc'>desc</option>");
        div.append(input).append('&nbsp;&nbsp;&nbsp;&nbsp;').append(select);
        jQuery.float({innerHtml: div});
    }
    function cover() {
        if (jQuery('tr[add=add]').size() > 0) {
            return;
        }
        if (jQuery('.msg').size() > 0) {
            jQuery('table.list').show();
        }
        jQuery('.msg').hide();
        var tr = jQuery('<tr/>').attr('add', 'add');
        for (i = 0; i < 10; i++) {
            var td = jQuery('<td/>');
            if (i == 1) {
                td.append("<input style=\"width: 150px\" maxlength=\"100\"  id=\"pname\" class=\"input in-text\"/>");
            }
            if (i == 2) {
                td.append("<select style=\"width: 100px\" onchange= 'changeCodeType(this);' class='select in-select' id='code_type'><option value=0>Code</option><option value=1>Code Name</option><option value=2>Country</option></select>");
            }
            if (i == 3) {
                var code_deck = document.getElementById('add_code_deck');
                td.append(code_deck.innerHTML);
            }
            if (i == 4) {
                td.append("<select style=\"width: 100px\" class='select in-select' id='route_lrn'><option value=0>DNIS</option><option value=1>LRN</option></select>");
            }
            if (i == 9) {
                td.append("<a style=\"margin-left: 10px;\" title=\"Save\" href=\"# \" id=\"save\" onclick=\"add(new Array('pname','code_type','code_deck_id','route_lrn'),'<?php echo $this->webroot ?>products/add_product');\">" +
                    "<i class=\"icon-save\"></i>" +
                    "</a>" +
                    "<a style=\"margin-left: 5px;\" title=\"delete\"  href=\"# \"style=\"margin-left:20px\" id=\"delete\" onclick=\"closeCover(this)\"><i class=\"icon-remove\"></i></a>"
                );
            }
            tr.append(td);
        }
        jQuery('table.list').prepend(tr);
    }
    function closeCover(a) {
        jQuery(a).parent().parent().remove();
        if (jQuery('table.list tr').size() == 1)
        {
            jQuery('table.list').hide();
            jQuery('.msg').show();
        }
    }
    jQuery(document).ready(
        function () {
            jQuery('#pname').xkeyvalidate({type: 'strName'});

            var options = Array();
            var webroot = '<?php echo $this->webroot ?>';
            jQuery.ajax(
                {
                    url: webroot + "products/getallproducts",
                    async: false,
                    success: function (data) {
                        var dataarr = eval(data);
                        for (i in dataarr)
                        {
                            var obj = dataarr[i];
                            obj.value = obj.id;
                            obj.key = obj.name;
                            options.push(obj);
                        }
                    }
                });
            jQuery('#swap').xshowadd(
                {
                    width: '400px',
                    height: '150px',
                    title: '<div style="ing-left:20px;text-align:left;background:#9DC0E0;font-weight:bolder;height:22px"><span>Swap<span></div>',
                    posts: [
                        {'label': 'The Product to be swaped:&nbsp;&nbsp;', 'id': 'productA', 'name': 'productA', 'fin': 'select', 'options': options},
                        {'label': '&nbsp;&nbsp;Select a product to swap:&nbsp;&nbsp;', 'id': 'productB', 'name': 'productB', 'fin': 'select', 'options': options}
                    ],
                    callBack: function (data) {
                        jQuery('#container').html(data);
                        jQuery.xcloseadd();
                    }
                }
            );
        }


    );

</script>

<!-- 添加不成功时 显示原来输入的名称 -->
<?php
$n = $session->read('product_name');
if (!empty($n))
{
    $session->del('product_name');
    ?>
    <script>
        cover('addproduct');
        document.getElementById("pname").value = "<?php echo $n ?>";
    </script>
<?php } ?>
<dl id="addproduct" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:200px;height:100px;">
    <dd style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('addproduct') ?></dd>
    <dd style="margin-top:10px;"><?php echo __('produname') ?>:<input class="input in-text" id="pname"maxLength="100" /></dd>
    <dd style="margin-top:10px; margin-left:10%;width:100px;height:auto;">
        <input type="button" onclick="add('pname', '<?php echo $this->webroot ?>products/add_product');"style=" width: 60px;" value="<?php echo __('submit') ?>" class="input in-submit" />
        <input type="button" onclick="closeCover('addproduct');" value="<?php echo __('cancel') ?>" class="input in-submit" style=" width: 60px;" />
    </dd>
</dl>
<div id="add_code_deck" style="display: none;">
    <?php echo $xform->input("code_deck_id", Array("name" => "code_deck_id", "options" => $codeDecks, "style" => "display:none;width:100px")) ?>
</div>
<script type="text/javascript" >
    $(document).ready(function(){
        $('.checkAll').on('click', function(){
            $('tbody > tr:visible').find('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
        });
    });
    jQuery('a[id=edit]').click(function () {
        var role_id = jQuery(this).attr('role_id');
        jQuery(this).parent().parent().trAdd({
            action: "<?php echo $this->webroot ?>products/modifyname",
            ajax: "<?php echo $this->webroot ?>products/data_edit/" + role_id,
            saveType: "edit"
        });
    });

</script>


<script>
    function changeCodeType(obj) {
        $code_deck = $(obj).parent().next().find('select');
        if ($(obj).val() == 0) {
            $code_deck.hide();
            $code_deck.append('<option value="0"></option>');
            $code_deck.attr('value', "0");
        }

        if ($(obj).val() == 1) {
            $code_deck.show();
            //$code_deck.attr
            $code_deck.find('option[value="0"]').remove();
        }
    }
</script>
