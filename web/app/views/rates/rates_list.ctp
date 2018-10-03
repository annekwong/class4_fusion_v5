<style type="text/css">
    table select{width: 100px;}
    table input {width:100px;}
    #RateName{width:150px;}
    .overflow_x{
        overflow-x:auto;
        margin-bottom: 10px;
    }
    #cover{
        background: #000;
        position: absolute;
        left: 0px;
        top: 0px;
        z-index: 100000;
        /*filter: Alpha(opacity=50);*/
        /*-moz-opacity:.1;*/
        /*opacity:0.5;*/
    }
    #copyratetmp dd{margin:0;}
    .hidden{display:none !important;}
    #list_div .btn-group-sm{
        margin-bottom:30px !important;
    }
</style>

<?php $d = $p->getDataArray(); ?>
<?php
if ($_SESSION['login_type'] == 1)
{
    ?>
    <script language="JavaScript" type="text/javascript">
        function generate_tmp() {
            var tab = document.getElementById("ratetab");
            var inputs = tab.getElementsByTagName("input");
            var ids = '';
            var count = 0;

            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].type == 'checkbox' && inputs[i].checked == true) {
                    ids += inputs[i].value + ",";
                    count++;
                }
            }
            if (count <= 0) {
                jGrowl_to_notyfy('Please select at least one templates rates table ', {theme: 'jmsg-alert'});
                return false;
            }
            ids = ids.substring(0, ids.lastIndexOf(","));
            document.getElementById("g_ids").value = ids;
            if (count == -1) {
                //cover('copyratetmp');
                document.getElementById('tmpid').value = ids;
                return false;
            }
            cover('generatert');
        }

        function generate_rt() {
            var rt_n = document.getElementById("pname_g").value;
            if (!rt_n) {
                jGrowl_to_notyfy('Please enter name for the news rate table to be created!', {theme: 'jmsg-alert'});
                return false;
            }
            var cur = document.getElementById("g_currency").value;
            var r_url = "<?php echo $this->webroot ?>rates/generate_tmp?rt_n=" + rt_n + "&cur=" + cur + "&way=" + $('input[name=way]:checked').val() + "&ids=" + $('#g_ids').val();
            location = r_url;
        }

        function copy_rate() {
            var v = document.getElementById("tmpid").value;
            var n = document.getElementById("pname").value;
            if (!n) {
                jGrowl_to_notyfy('<?php echo __('enterratename') ?>', {theme: 'jmsg-alert'});
            }
            else {
                location = '<?php echo $this->webroot ?>rates/copy_tmp?id=' + v + '&name=' + n;
            }
        }

        function save_rate(tr) {
            var curr_url = '<?php echo $_SESSION['curr_url']; ?>';
            var name = tr.cells[2].getElementsByTagName("input")[0].value;
            if (!name) {
                jGrowl_to_notyfy('<?php echo __('enterratename') ?>', {theme: 'jmsg-alert'});
            }
            else {
                var c = tr.cells[3].getElementsByTagName("select")[0].value;
                var cu = tr.cells[4].getElementsByTagName("select")[0].value;
                var country = tr.cells[6].getElementsByTagName("select")[0].value;
                if (!cu) {
                    jGrowl_to_notyfy('<?php echo __('nocurrency') ?>', {theme: 'jmsg-alert'});
                    return false;
                }
                url = "<?php echo $this->webroot ?>rates/add_tmp?n=" + name + "&c=" + c + "&cu=" + cu + "&country=" + country + "";
                location = url;
            }
            return false;
        }
    </script>

    <ul class="breadcrumb">
        <li><?php __('You are here')?></li>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><a href="<?php echo $this->webroot ?>rates/rates_list">
            <?php __('Switch') ?></a></li>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><a href="<?php echo $this->webroot ?>rates/rates_list">
            <?php echo __('Rate Table') ?></a></li>
    </ul>

    <div class="heading-buttons">
        <h4 class="heading"><?php echo __('Rate Table') ?></h4>

    </div>
    <div class="separator bottom"></div>
    <div class="buttons pull-right newpadding">
        <?php
    if ($_SESSION['role_menu']['Switch']['rates']['model_w'])
    {
        ?>
        <a  class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>rates/create_ratetable"><i></i> <?php echo __('Create New') ?></a>
        <a class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0)" onclick="generate_tmp();" ><i></i> <?php echo __('Auto Create') ?></a>
        <?php if (count($d) > 0) : ?>
        <a  class="btn btn-primary btn-icon glyphicons remove" relm="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot ?>rates/delete_all');"><i></i> <?php echo __('Delete All') ?></a>
        <a class="btn btn-primary btn-icon glyphicons remove" rel="popup" href="javascript:void(0)" onclick="beforeDeleteSeleted()"><i></i> <?php echo __('Delete Selected') ?></a>
    <?php endif; ?>
    <?php } ?>
    <?php
    if (isset($curr_search))
    {
        ?>
        <?php echo $appCommon->show_back_href(); ?>
    <?php } ?>
    </div>
    <div class="clearfix"></div>
    <!-- Modal Gallery -->
<div id="myModal_copyRate" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">&times;</button>
        <h3><?php __('Copy Rate Table'); ?></h3>
    </div>
    <div class="modal-body">
        <table class="form table table-condensed">
            <tr>
                <td class="align_right"><?php __('ratetmpname'); ?></td>
                <td>
                    <input class="input in-text width220" id="pname" type="text"/>
                    <input type="hidden" id="tmpid"/>
                </td>
            </tr>
        </table>
    </div>
    <div class="modal-footer">
        <input type="button" onclick="copy_rate();" value="<?php echo __('submit') ?>" class="input in-button btn btn-primary" >
        <a href="javascript:void(0)" id="support_close" data-dismiss="modal" class="btn btn-default"><?php __('Close'); ?></a>
    </div>
</div>
<!-- // Modal Gallery END -->
<!--    <dl id="copyratetmp" class="tooltip-styled" style="border-radius: 5px;display:none;position:absolute;left:40%;top:200px;z-idnex:99;width:300px;height:120px; border: 1px solid #fff; background-color: #ffffff;">-->
<!--        <dd style="text-align:center;width:100%;height:25px;font-size: 16px;border-radius: 5px;background: #7FAF00;color: #fff;">--><?php //echo __('copyratetmp') ?>
<!--            <span style="float:right"><a href="javascript:closeCover('copyratetmp');" id="pop-close" class="pop-close"><i class="icon-remove"></i>&nbsp;</a></span>-->
<!--        </dd>-->
<!--        <dd style="margin-top:10px;margin-left:5%;"> --><?php //echo __('ratetmpname') ?><!--:-->
<!--            <input class="input in-text" id="pname" type="text"/>-->
<!--        </dd>-->
<!--        <dd>-->
<!--            <input style="display:none" id="tmpid"/>-->
<!--        </dd>-->
<!--        <dd style="margin-top: 10px;margin-left: 20%; width:200px;height:auto;">-->
<!--            <input type="button" onclick="copy_rate();" value="--><?php //echo __('submit') ?><!--" class="input in-button btn btn-primary" >-->
<!--            <input type="button" onclick="closeCover('copyratetmp');" value="--><?php //echo __('cancel') ?><!--" class="input in-button btn btn-default"  >-->
<!--        </dd>-->
<!--    </dl>-->
    <dl id="generatert" class="tooltip-styled" style="display:none; position:absolute;margin:auto;left:400px;top:200px;z-index:200000;width:450px;height:250px; border: 1px solid #000000; background-color: #ffffff;">
        <dd style="text-align:center;width:100%;height:30px;font-size: 16px;background-color:#F4F4F4;margin-left: 0px;line-height: 30px;"><?php echo __('generatert') ?>
            <span class="float_right"><a href="javascript:closeCover('generatert')" id="pop-close" class="pop-close">&nbsp;</a></span>

        </dd>

        <dd>
            <table style="margin:10px">
                <tr>
                    <td style="text-align:right"> Input <?php echo __('ratetmpname') ?>:</td>
                    <td style="text-align:left"><script type="text/javascript">
                        var _ss_ids_rate = {'id_rates': 'query-id_rates', 'id_rates_name': 'query-id_rates_name'};

                        </script>
                        <input type="text" id="query-id_rates_name" ondblclick="ss_rate(_ss_ids_rate)" value="" name="data[name]" class="input in-text">
                        <a href="#"onclick="ss_rate(_ss_ids_rate)"  > <i class="icon-search"></i> </a><a href="#"  onclick="ss_clear('card', _ss_ids_rate)"><i class="icon-remove"></i></a></td>
                </tr>
                <tr>
                    <td style="text-align:right"><?php echo __('currency') ?>:</td>
                    <td style="text-align:left"><select  id="g_currency" name="data[currency]" style="width:180px">
                            <?php
    for ($i = 0; $i < count($currs_s); $i++)
    {
        ?>
        <option value="<?php echo $currs_s[$i][0]['currency_id'] ?>"><?php echo $currs_s[$i][0]['code'] ?></option>
    <?php } ?>
                        </select></td>
                </tr>
                <tr>
                    <td style="text-align:right;"><?php echo __('type') ?>:</td>
                    <td style="text-align:left"><select  name="data[type]" style="width: 180px" onchange="copy_change_type(this)">
                            <option value=1><?php __('By minimum') ?></option>
                            <option value=2><?php __('By maximum') ?></option>
                            <option value=3><?php __('On average') ?></option>
                            <option value=4><?php __('Percentage increase') ?></option>
                            <option value=5><?php __('By number') ?></option>
                        </select>
                        <input style="display:none;width:180px" type="text" name=data[type_num] /></td>
                </tr>
                <tr>
                    <td style="text-align:right"><?php __('Same code of') ?>:</td>
                    <td style="text-align:left"><select name='data[code_type]'  style="width:180px">
                            <option value='ignore'><?php __('ignore') ?></option>
                            <option value='overwrite'><?php __('overwrite') ?></option>
                        </select></td>
                </tr>
            </table>
        </dd>
        <dd>
            <input id="g_ids" value="" type="hidden" name='data[ids]' style="display:none"/>
        </dd>
        <dd style="height: 40px;background-color: #f4f4f4;margin-left: 0px;padding-top: 10px;text-align: right;padding-right: 10px;">
            <input type="button" onclick="jQuery('#generatert').xForm({action: '<?php echo $this->webroot ?>rates/generate_tmp', onsubmit: function(options) {
                            if (jQuery('#query-id_rates_name').val() == '') {
                                jQuery('#query-id_rates_name').jGrowlError('Please enter name for the news rate table to be created!');
                                return false;
                            }
                            return true;
                        }})" value="<?php echo __('submit') ?>" class="input in-button btn btn-primary">
            <input type="button" onclick="closeCover('generatert');" value="<?php echo __('cancel') ?>" class="input in-button btn btn-default">
        </dd>
    </dl>

    <div class="innerLR">

        <div class="widget widget-tabs widget-body-white">
            <div class="widget-body">
                <div class="filter-bar">
                    <form method="get">
                        <!-- Filter -->
                        <div>
                            <label><?php __('Search')?>:</label>
                            <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                        </div>
                        <!-- // Filter END -->
                        <!-- Filter -->
                        <div>
                            <label><?php __('Code Deck')?>:</label>
                            <select id="search_code_deck" class="select in-select" name="search_code_deck">
                                <option value=""><?php echo __('select') ?></option>
                                <?php
    for ($i = 0; $i < count($codecs_s); $i++)
    {
        ?>
        <option value="<?php echo $codecs_s[$i][0]['code_deck_id'] ?>"><?php echo $codecs_s[$i][0]['name'] ?></option>
    <?php } ?>
                            </select>
                        </div>
                        <!-- // Filter END -->
                        <!-- Filter -->
                        <div>
                            <label><?php __('Currency')?>:</label>
                            <select id="search_currency" name="search_currency" class="select in-select">
                                <option value=""><?php echo __('select') ?></option>
                                <?php
    for ($i = 0; $i < count($currs_s); $i++)
    {
        ?>
        <option value="<?php echo $currs_s[$i][0]['currency_id'] ?>"><?php echo $currs_s[$i][0]['code'] ?></option>
    <?php } ?>
                            </select>
                        </div>
                        <!-- // Filter END -->
                        <!-- Filter -->
                        <div>
                            <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                        </div>
                        <!-- // Filter END -->
                    </form>
                </div>

                <fieldset class="title-block" id="advsearch" style="display:none;width:100%;margin-left:1px;">
                    <form method="get">
                        <input type="hidden" name="advsearch" value="1"/>
                        <table  style="width:auto;">
                            <tbody>
                                <tr>
                                    <td style="display: none;"><label style="padding-top:3px;"><?php echo __('ratetmpname') ?>:</label>
                                        <input type="text" name="name" class="input in-text"/></td>
                                    <td><label style="padding-top:3px;"><?php echo __('codedecks') ?>:</label>
                                        <select id="search_code_deck" class="select in-select" name="search_code_deck">
                                            <option value=""><?php echo __('select') ?></option>
                                            <?php
    for ($i = 0; $i < count($codecs_s); $i++)
    {
        ?>
        <option value="<?php echo $codecs_s[$i][0]['code_deck_id'] ?>"><?php echo $codecs_s[$i][0]['name'] ?></option>
    <?php } ?>
                                        </select></td>
                                    <td><label style="padding-top:3px;"><?php echo __('currency') ?>:</label>
                                        <select id="search_currency" name="search_currency" class="select in-select">
                                            <option value=""><?php echo __('select') ?></option>
                                            <?php
    for ($i = 0; $i < count($currs_s); $i++)
    {
        ?>
        <option value="<?php echo $currs_s[$i][0]['currency_id'] ?>"><?php echo $currs_s[$i][0]['code'] ?></option>
    <?php } ?>
                                        </select></td>
                                    <td></td>
                                    <td class="buttons"><input type="submit" value="<?php echo __('search') ?>" class="input in-submit"></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                    <?php
    if (!empty($searchForm))
    {
        $d = array_keys($searchForm);
    foreach ($d as $k)
    {
        ?>
        <script type="text/javascript">
            if (document.getElementById("<?php echo $k ?>")) {
                document.getElementById("<?php echo $k ?>").value = "<?php echo $searchForm[$k] ?>";
            }
        </script>
    <?php } ?>
        <script type="text/javascript">document.getElementById("advsearch").style.display = 'block';</script>
    <?php } ?>
                </fieldset>
                <div id="tmpres" style="display:none;">
                    <select  class="in-select input" style="width:100px;height:20px;">
                        <?php
    for ($i = 0; $i < count($r_reseller); $i++)
    {
        ?>
        <option value="<?php echo $r_reseller[$i][0]['reseller_id'] ?>">
            <?php
            $space = "";
            for ($j = 0; $j < $r_reseller[$i][0]['spaces']; $j++)
            {
                $space .= "&nbsp;&nbsp;";
            }
            if ($i == 0)
            {
                echo "{$r_reseller[$i][0]['name']}";
            }
            else
            {
                echo "&nbsp;&nbsp;" . $space . "↳" . $r_reseller[$i][0]['name'];
            }
            ?>
        </option>
    <?php } ?>
                        <option value=""><?php echo __('anyreseller') ?></option>
                    </select>
                </div>

                <?php
    if (count($d) == 0)
    {
        ?>
        <div class="msg center"  id="msg_div">
            <br />
            <h2><?php echo __('no_data_found') ?></h2>
        </div>
    <?php
    }
    else
    {
        ?>
        <div class="msg"  id="msg_div"  style="display: none;"><?php echo __('no_data_found') ?></div>
    <?php } ?>
    <?php
    if (count($d) == 0)
    {
        ?>
        <div  id="list_div"  style="display: none;">
    <?php
    }
    else
    {
        ?>
        <div   id="list_div">
    <?php } ?>
                        <div class="clearfix"></div>
<!--                        --><?php
//    if ($_SESSION['role_menu']['Switch']['rates']['model_w'])
//    {
//        ?><!--<div style="padding:5px;text-align:right"><button id="msedit" class="input in-submit btn" style="width:100px;">--><?php //__('Mass Edit')?><!--</button></div>-->
<!--    --><?php //} ?>
                        <div class="overflow_x">
                            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

                                <thead>
                                    <tr>
                                        <?php
    if ($_SESSION['role_menu']['Switch']['rates']['model_w'])
    {
        ?>
        <th class="footable-first-column expand" data-class="expand"><?php
            if ($_SESSION['login_type'] == '1')
            {
                ?>
                <input id="selectAll" class="select" type="checkbox" onclick="checkAllOrNot(this, 'ratetab');" value=""/>
            <?php } ?></th>
    <?php } ?>
                                        <th><?php echo $appCommon->show_order('name', __('Name', true)) ?></th>
                                        <th><?php echo $appCommon->show_order('code_deck', __('Code Deck', true)) ?></th>
                                        <th><?php echo $appCommon->show_order('currency', __('Currency', true)) ?></th>
                                        <th><?php __('Usage Count') ?></th>
                                        <th><?php echo $appCommon->show_order('rate_type', __('Billing Method', true)) ?></th>
                                        <th><?php echo $appCommon->show_order('jur_type', __('Rate Type', true)) ?></th>
                                        <th><?php echo  __('Defined By', true)?></th>
                                        <th data-hide="phone,tablet"  style="display: table-cell;"><?php echo __('Update At', true); ?></th>
                                        <th data-hide="phone,tablet"  style="display: table-cell;"><?php echo __('Update By', true); ?></th>
                                            <?php
    if ($_SESSION['role_menu']['Switch']['rates']['model_w'])
    {
        ?> <th data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;"><?php echo __('Action') ?></th>
    <?php } ?>
                                    </tr>
                                </thead>
                                <tbody id="ratetab">
                                    <?php
    $mydata = $p->getDataArray();

    $loop = count($mydata);
    if (!$mydata || !$loop) {?>
    <tr>
        <td colspan='11' class="center">
        <?php echo __('No data found!');?>
        </td>
    </tr>

    <?php } else {
    for ($i = 0; $i < $loop; $i++)
    {
        ?>
    <tr class="row-1"  id="i_<?php echo $loop - $i ?>">
        <?php
        if ($_SESSION['role_menu']['Switch']['rates']['model_w'])
        {
            ?><td class="footable-first-column expand" data-class="expand"><?php
            if ($_SESSION['login_type'] == '1')
            {
                ?>
                <input class="select multi_select" type="checkbox" value="<?php echo $mydata[$i][0]['rate_table_id'] ?>"/>
            <?php } ?></td>
        <?php } ?>
        <td style="font-weight: bold;"><a title="<?php echo __('viewrates') ?>" class="link_width"
                                          href="<?php echo !$mydata[$i][0]['define_by'] ? $this->webroot.'clientrates/view/'.base64_encode($mydata[$i][0]['rate_table_id']) : $this->webroot. 'clientrates/view_code_name_rate/'.$mydata[$i][0]['rate_table_id'] ?>?get_back_url=<?php echo base64_encode($this->params['getUrl']) ?>"> <?php echo $mydata[$i][0]['name'] ?> </a>
        </td>
        <td><a style="width:100%;display:block" href="<?php echo $this->webroot ?>codedecks/codedeck_list?edit_id=<?php echo $mydata[$i][0]['code_deck_id'] ?>&viewType=rate"><?php echo $mydata[$i][0]['code_deck'] ?></a></td>
        <td><?php echo $mydata[$i][0]['currency'] ?></td>
        <td>
            <a target="_blank" href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_egress?rate_table_id=<?php echo $mydata[$i][0]['rate_table_id'] ?>" title="Egress Trunk Usage Count">
                <?php echo $mydata[$i][0]['egress_count'] ?>
            </a>
            /
            <a target="_blank" href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_ingress?rate_table_id=<?php echo $mydata[$i][0]['rate_table_id'] ?>" title="Ingress Trunk Usage Count">
                <?php echo $mydata[$i][0]['ingress_count'] ?>
            </a>
        </td>
        <td><?php echo isset($billing_methods[$mydata[$i][0]['rate_type']]) ? $billing_methods[$mydata[$i][0]['rate_type']] : ""; ?></td>
        <td><?php echo isset($jur_lists[$mydata[$i][0]['jur_type']]) ? $jur_lists[$mydata[$i][0]['jur_type']] : ''; ?></td>
        <td data-hide="phone,tablet"  style="display: table-cell;"><?php echo $define_by_arr[$mydata[$i][0]['define_by']] ?></td>
        <td data-hide="phone,tablet"  style="display: table-cell;"><?php echo $mydata[$i][0]['update_at'] ?></td>
        <td data-hide="phone,tablet"  style="display: table-cell;"><?php echo $mydata[$i][0]['update_by'] ?></td>
        <?php
        if ($_SESSION['role_menu']['Switch']['rates']['model_w'])
        {
            ?>
            <td data-hide="phone,tablet" class="footable-last-column"  style="display: table-cell;" >
            <a title="<?php echo __('View Rates') ?>" style="float:left;margin-left:5px;"
            href="<?php echo !$mydata[$i][0]['define_by'] ? $this->webroot.'clientrates/view/'.base64_encode($mydata[$i][0]['rate_table_id']) : $this->webroot. 'clientrates/view_code_name_rate/'.$mydata[$i][0]['rate_table_id'] ?>?get_back_url=<?php echo base64_encode($this->params['getUrl']) ?>">
             <i class="icon-align-justify"></i>
             </a>
                <?php
                if ($_SESSION['login_type'] == 1)
                {
                    ?>
                    <a title="<?php echo __('Edit') ?>" style="float:left;margin-left:5px;" href="#" id='edit' rate_table_id='<?php echo $mydata[$i][0]['rate_table_id'] ?>'>  <i class="icon-edit"></i> </a>
                    <a item="<?php echo $mydata[$i][0]['rate_table_id'] ?>" title="<?php echo __('del') ?>" style="float:left;margin-left:5px;" href="javascript:void(0)" class="delbtn">
                        <i class="icon-remove"></i>
                    </a>
                    <?php if($mydata[$i][0]['jur_type'] == 2):?>
                    <a title="<?php echo __('Indeterminate') ?>" control="<?php echo $mydata[$i][0]['rate_table_id'] ?>" class="show_indeterminate" style="float:left;margin-left:5px;" href="javascript:void(0)"> <i class="icon-ellipsis-vertical"></i> </a>
                    <?php endif;?>
                    <a title="<?php echo __('Copy Rate Table') ?>" data-toggle="modal" style="float:left;margin-left:5px;" href="#myModal_copyRate" class="copy_rate_btn" rate_table_id='<?php echo $mydata[$i][0]['rate_table_id'] ?>' table_name="<?php echo $mydata[$i][0]['name'] ?>">
                        <i class="icon-copy"></i>
                    </a>
                    &nbsp;<a title="<?php __('Send')?>" href="<?php echo $this->webroot; ?>rates/send_rate/<?php echo base64_encode($mydata[$i][0]['rate_table_id']); ?>">
                    <i class="icon-envelope"></i>
                    <a title="<?php __('Assign Rate Deck')?>" href="<?php echo $this->webroot; ?>rates/assign_rate_table/<?php echo base64_encode($mydata[$i][0]['rate_table_id']); ?>">
                        <i class="icon-share"></i>
                </a>
                <?php } ?></td>
            </tr>
        <?php } ?>
        <tr style="display:none;">
            <td><dl id="i_<?php echo $loop - $i ?>-tooltip" class="tooltip">
                    <dd><?php echo __('createdate') ?>:</dd>
                    <dd><?php echo $mydata[$i][0]['create_time'] ?></dd>
                    <dd><?php echo __('updateat') ?>:</dd>
                    <dd><?php echo $mydata[$i][0]['modify_time'] ?></dd>
                </dl></td>
            <td class="hidden"></td>
            <td class="hidden"></td>
            <td class="hidden"></td>
            <td class="hidden"></td>
            <td class="hidden"></td>
            <td class="hidden"></td>
            <td class="hidden"></td>
            <td class="hidden"></td>
            <td class="hidden"></td>
            <td class="hidden"></td>

        </tr>
    <?php } ?>
 <?php } ?>
                                </tbody>

                            </table>
                        </div>
                        <?php if ($mydata ) {?>
                        <div id="tmppage" class="row-fluid">
                            <div class="pagination pagination-large pagination-right margin-none">
                                <?php echo $this->element('page'); ?>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="cover"></div>
    <script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.center.js"></script>
    <script type="text/javascript">
                                                function copy_change_type(obj) {
                                                    if (jQuery(obj).val() == 4 || jQuery(obj).val() == 5) {
                                                        jQuery(obj).parent().find('input').show();
                                                    } else {
                                                        jQuery(obj).parent().find('input').hide().val('');
                                                    }
                                                }
    </script> 
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('#selectAll').selectAll('.select');
            jQuery('#add').click(function() {
                $("#msg_div").hide();
                $("#list_div").show();
                jQuery('#ratetab').trAdd({
                    insertNumber: 'first',
                    action: "<?php echo $this->webroot ?>rates/save",
                    ajax: "<?php echo $this->webroot ?>rates/js_save",
                    onsubmit: function() {
                        var ret = true;
                        var rate_name = jQuery('#RateName').val();
                        if (rate_name.length > 36 || /[^0-9A-Za-z-\_\s]+/.test(rate_name)) {
                            jQuery('#RateName').addClass('invalid');
                            jGrowl_to_notyfy('Name,allowed characters:a-z,A-Z,0-9,-,_,space,maximum of 16 characters in length! ', {theme: 'jmsg-error'});
                            ret = false;
                        }
                        var currency = $("#RateCurrencyId").val();
                        if (currency == '0' || currency == null)
                        {
                            jGrowl_to_notyfy('The field Currency cannot be NULL.', {theme: 'jmsg-error'});
                            ret = false;
                        }

                        return ret;

                    }
                });
            });
            jQuery('a[id=edit]').click(function() {
                var rate_table_id = jQuery(this).attr('rate_table_id');
                jQuery(this).parent().parent().trAdd({
                    action: "<?php echo $this->webroot ?>rates/save/" + rate_table_id,
                    ajax: "<?php echo $this->webroot ?>rates/js_save/" + rate_table_id,
                    saveType: 'edit',
                    onsubmit: function() {
                        var re = true;

                        var rate_name = jQuery('#RateName').val();
                        var name_length = rate_name.length;
                        if (!/^[0-9a-zA-Z_][0-9a-zA-Z_ \|\.\=\-]+[0-9a-zA-Z_]$/.test(rate_name) || name_length > 36)
                        {
                            jQuery('#RateName').addClass('invalid');
                            jGrowl_to_notyfy('Name,allowed characters:a-z,A-Z,0-9,-,_,space,maximum of 36 characters in length! ', {theme: 'jmsg-error'})

                            re = false;
                        }
                        var currency = $("#RateCurrencyId").val();
                        if (currency == '0' || currency == null)
                        {
                            jGrowl_to_notyfy('The field Currency cannot be NULL.', {theme: 'jmsg-error'});
                            re = false;
                        }
                        return re;
                    }
                });
            });
        });
    </script> 

    <script type="text/javascript" language="javascript">
        var _move = false;//移动标记
        var _x, _y;//鼠标离控件左上角的相对位置
        $(document).ready(function() {
            $("#generatert").click(function() {
                //alert("click");//点击（松开后触发）
            }).mousedown(function(e) {
                _move = true;
                _x = e.pageX - parseInt($("#generatert").css("left"));
                _y = e.pageY - parseInt($("#generatert").css("top"));
//                $("#generatert").fadeTo(20, 0.25);//点击后开始拖动并透明显示
            });
            $("#generatert").mousemove(function(e) {
                if (_move) {
                    var x = e.pageX - _x;//移动时根据鼠标位置计算控件左上角的绝对位置
                    var y = e.pageY - _y;
                    $("#generatert").css({top: y, left: x});//控件新位置
                }
            }).mouseup(function() {
                _move = false;
//                $("#generatert").fadeTo("fast", 1);//松开鼠标后停止移动并恢复成不透明
            });
        });
    </script>

    <script type="text/javascript">
        $(function() {
            $('#msedit').click(function() {
                var selected_arr = new Array();
                $('#ratetab input[type=checkbox]').each(function(index) {
                    var $this = $(this);
                    if ($this.attr('checked')) {
                        selected_arr.push($this.val());
                    }
                });
                if (selected_arr.length === 0) {
                    showMessages("[{'field':'#msedit','code':'101','msg':'You must select at least one item!'}]");
                    return false;
                }
                window.location.href = "<?php echo $this->webroot ?>rates/massedit/" + selected_arr.join(',');
            });
        });
    </script>


    </div>
    <div id="pop-div" class="pop-div" style="display:none;">
        <div class="pop-thead">
            <span></span>

            <span class="float_right"><a href="javascript:closeDiv('pop-div')" id="pop-close" class="pop-close">&nbsp;</a></span>
        </div>
        <div class="pop-content" id="pop-content"></div>
    </div>
    <?php
}
else
{
    echo $this->element('rate/client_rate');
}
?>

<div id="dd" class="easyui-dialogui" title="Trunks using this rate table" closed="true" style="width:800px;height:400px;display:none;"
     data-options="iconCls:'icon-save',resizable:true,modal:true">
    <div class="product_list">
        <table id="useditem" class="list table dynamicTable tableTools table-bordered  table-primary table-white">
            <thead>
            <tr>
                <th><?php __('Carrier Name')?></th>
                <th><?php __('Trunk Name')?></th>
                <th><?php __('Type')?></th>
                <th><?php __('Active')?></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
            <tr>
                <td colspan="4">
                    <input id="delcontinue" type="button" class="btn btn-primary" value="<?php __('Continue')?>" />
                    <input id="delcancel" type="button" class="btn btn-default" value="<?php __('Cancel')?>" />
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>



<script type="text/javascript">


    function beforeDeleteSeleted() {

        var selected = new Array();
        $('.multi_select').each(function() {
            var $this = $(this);
            if ($this.is(':checked')) {
                selected.push($this.val());
            }
        });
        if (!selected.length) {
            jGrowl_to_notyfy("You did not select any item!", {theme: 'jmsg-error'});
        }
        else
        {
            bootbox.dialog(" ", [

                {
                    label: 'Delete unused records',
                    callback: function () {
                        deleteSelected(selected, 2);
                    }
                },
                {
                    label: 'Delete the used records',
                    callback: function () {
                        deleteSelected(selected, 3);
                    }
                },
                {
                    label: 'Delete All',
                    callback: function () {
                        deleteSelected(selected, 1);
                    }
                }
            ], {
                'header': 'Are you sure to delete selected rate tables?',
                'headerCloseButton': true
            });
        }
    }

    function deleteSelected(selected, type) {
        $.ajax({
            'url': '<?php echo $this->webroot; ?>rates/delete_selected',
            'type': 'POST',
            'dataType': 'json',
            'data': {
                'ids[]': selected,
                'type': type
            },
            'success': function (data) {
                if (data.status == 1) {
                    jGrowl_to_notyfy("The numbers you selected is deleted successfully!", {theme: 'jmsg-success'});
                } else {
                    jGrowl_to_notyfy("The numbers you selected is deleted failed!", {theme: 'jmsg-error'});
                }
                window.setTimeout("window.location.reload();", 1500);
            }
        });
    }

    $(function() {

        $(".copy_rate_btn").click(function(){
            var $rate_table_id = $(this).attr('rate_table_id');
            var $rate_table_name = $(this).attr('table_name');
            var title_text = $("#myModal_copyRate").find("h3").html()+"["+ $rate_table_name +"]";
            $("#myModal_copyRate").find("h3").html(title_text);
            $("#tmpid").val($rate_table_id);
        });

        $('#search_code_deck').val(<?php
if (!empty($search_code_deck))
{
    echo $search_code_deck;
}
else
{
    echo "";
}
?>);
        $('#search_currency').val(<?php
if (!empty($search_currency))
{
    echo $search_currency;
}
else
{
    echo "";
}
?>);

        function preventInput(event) {
            if ($(this).parent().prev().find('select').val() == '')
                event.preventDefault();

        }
        $('#RateLnpDippingRate').live('keydown', preventInput);
        $('#RateLnpDippingRate').live('keyup', preventInput);

        $('.show_indeterminate').click(function() {
            showDiv('pop-div', 'auto', 'auto', '<?php echo $this->webroot; ?>rates/indeteminate/' + $(this).attr('control'));
        });

        $('#update_in').live('click', function() {
            var form_serial = $('#update_in_form').serialize();
            $.ajax({
                url: '<?php echo $this->webroot; ?>rates/update_indeter?' + form_serial,
                method: 'GET',
                dataType: 'text',
                success: function(data) {
                    jGrowl_to_notyfy('Sucessfully!', {theme: 'jmsg-success'});
                }
            });
        })

        var $dd = $('#dd');
        var $useditems = $('#useditem tbody');
        var $delcontinue = $('#delcontinue');
        var $delcancel = $('#delcancel');

        $('a.delbtn').click(function() {
            var $this = $(this);
            var item = $this.attr('item');

            $.ajax({
                'url': '<?php echo $this->webroot ?>rates/checkused',
                'type': 'POST',
                'dataType': 'json',
                'data': {'rate_table_id': item},
                'success': function(data) {
                    if (data.length)
                    {
                        $useditems.empty();
                        $.each(data, function(idx, item) {
                            var $tr = $('<tr></tr>');
                            $tr.append('<td>' + item['client_name'] + '</td><td>' + item['resource_name'] + '</td><td>' + item['type'] + '</td><td>' + item['is_active'] + '</td>');

                            $useditems.append($tr);
                        });

                        $delcontinue.unbind('click');

                        $delcontinue.click(function() {
                            window.location.href = '<?php echo $this->webroot ?>rates/del_rate_tmp/' + item;
                        });
                        $dd.dialog({
                            'width': '600px'
                        });
                        $delcancel.click(function() {
                            $delcontinue.unbind('click');
                            $dd.dialog('close');
                        });
                        $dd.dialog('open');
                    } else {
                        bootbox.confirm("Are you sure do this?", function(result) {
                            if (result) {
                                window.location.href = '<?php echo $this->webroot ?>rates/del_rate_tmp/' + item;
                            }
                        });

                    }
                }
            });

            return false;
        });
    });
</script>

