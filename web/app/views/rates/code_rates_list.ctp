<?php if ($_SESSION['login_type'] == 1)
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
                jGrowl_to_notyfy('Please select at least one templates rates', {theme: 'jmsg-alert'});
                return;
            }
            ids = ids.substring(0, ids.lastIndexOf(","));
            document.getElementById("g_ids").value = ids;
            if (count == -1) {
                cover('copyratetmp');
                document.getElementById('tmpid').value = ids;
                return;
            }
            cover('generatert');
        }
        function generate_rt() {
            var rt_n = document.getElementById("pname_g").value;
            if (!rt_n) {
                jGrowl_to_notyfy('Please enter copy the name of the template new rate table !', {theme: 'jmsg-alert'});
                return;
            }
            var cur = document.getElementById("g_currency").value;
            var r_url = "<?php echo $this->webroot ?>rates/generate_tmp?rt_n=" + rt_n + "&cur=" + cur + "&way=" + $('input[name=way]:checked').val() + "&ids=" + $('#g_ids').val();
            location = r_url;
        }
        function copy_rate() {
            var v = document.getElementById("tmpid").value;
            var n = document.getElementById("pname").value;
            if (!n)
                jGrowl_to_notyfy('<?php echo __('enterratename') ?>', {theme: 'jmsg-alert'});
            else
                location = '<?php echo $this->webroot ?>rates/copy_tmp?id=' + v + '&name=' + n;
        }
        function save_rate(tr) {
            var curr_url = '<?php echo $_SESSION['curr_url']; ?>';
            var name = tr.cells[2].getElementsByTagName("input")[0].value;
            if (!name)
                jGrowl_to_notyfy('<?php echo __('enterratename') ?>', {theme: 'jmsg-alert'});
            else {
                var c = tr.cells[3].getElementsByTagName("select")[0].value;
                var cu = tr.cells[4].getElementsByTagName("select")[0].value;
                var country = tr.cells[6].getElementsByTagName("select")[0].value;
                if (!cu) {
                    jGrowl_to_notyfy('<?php echo __('nocurrency') ?>', {theme: 'jmsg-alert'});
                    return;
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
        <li><?php __('Switch') ?></li>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><?php echo __('Code Deck') ?></li>
        <li class="divider"><i class="icon-caret-right"></i></li>
        <li><?php echo __('Rate Table') ?></li>
    </ul>    
    <div class="heading-buttons">
        <h4 class="heading"><?php __('Code Rate Table') ?></h4>
        <div class="buttons pull-right">

            <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="###"><i></i> <?php __('Create New') ?></a>
            <a onclick="generate_tmp();" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0)"><i></i><?php __('Auto Create') ?></a>
            <a class="btn btn-primary btn-icon glyphicons remove"  rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot ?>rates/delete_all');"><i></i> <?php __('Delete All') ?></a>
            <a class="btn btn-primary btn-icon glyphicons remove"  rel="popup" href="javascript:void(0)" onclick="deleteSelected('ratetab', '<?php echo $this->webroot ?>rates/delete_selected');"><i></i> <?php __('Delete Seleted') ?></a>
    <?php if (isset($curr_search))
    {
        echo $appCommon->show_back_href();
    } ?>
        </div>
        <div class="clearfix"></div>
    </div> 
    <div class="separator bottom"></div>
    <div class="innerLR">

        <div class="widget widget-tabs widget-body-white">
            <div class="filter-bar">
                
                <form action="" method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search')?>:</label>
                        <input type="text" id="search-_q" class=" validate[custom[onlyNumberSp]] in-search default-value input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                    </div>
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->

                    <div class="pull-right" title="Advance">
                        <a  class="btn" href="###"  onclick="advSearchToggle();">
                            <i class="icon-long-arrow-down"></i> 
                        </a>
                    </div>
                </form>
            </div>


            <div id="title">
                <h1><?php __('System') ?>&gt;&gt;<?php echo __('rateTable') ?></h1>
    <?php if ($_SESSION['login_type'] == '1')
    {
        ?>
                    <ul id="title-search">
                        <li>
                            <form>
                                <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                            </form>
                        </li>
                        <li style="display: list-item;" id="title-search-adv" onclick="advSearchToggle();" title="advanced search »" class=""></li>
                    </ul>

                    <?php } ?>
            </div>
            <dl id="copyratetmp" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:100px;">
                <dd style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('copyratetmp') ?></dd>
                <dd style="margin-top:10px;margin-left:5%;">
    <?php echo __('ratetmpname') ?>:<input class="input in-text" id="pname"/>
                </dd>
                <dd>
                    <input style="display:none" id="tmpid"/>
                </dd>
                <dd style="margin-left: 20%; width:200px;height:auto; ">
                    <input type="button" onclick="copy_rate();" value="<?php echo __('submit') ?>" class="input in-button" >
                    <input type="button" onclick="closeCover('copyratetmp');" value="<?php echo __('cancel') ?>" class="input in-button"  >
                </dd>
            </dl>
            <dl id="generatert" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:250px;">
                <dd style="text-align:center;width:100%;height:30px;font-size: 16px;"><?php echo __('generatert') ?></dd>
                <dd>
                    <table style="margin:10px">
                        <tr>
                            <td style="text-align:right">  Input <?php echo __('ratetmpname') ?>:</td>
                            <td style="text-align:left">
                                <script type="text/javascript">
                                    var _ss_ids_rate = {'id_rates': 'query-id_rates', 'id_rates_name': 'query-id_rates_name'};
                                </script>
                                <input type="text" id="query-id_rates_name"       ondblclick="ss_rate(_ss_ids_rate)" value="" name="data[name]" class="input in-text">        
                                <img width="9" height="9" onclick="ss_rate(_ss_ids_rate)" class="img-button" src="<?php echo $this->webroot ?>images/search-small.png">
                                <img width="9" height="9" onclick="ss_clear('card', _ss_ids_rate)" class="img-button" src="<?php echo $this->webroot ?>images/delete-small.png">
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:right"><?php echo __('currency') ?>:</td>
                            <td style="text-align:left">
                                <select  id="g_currency" name="data[currency]" style="width:180px">
    <?php for ($i = 0; $i < count($currs_s); $i++)
    {
        ?>
                                        <option value="<?php echo $currs_s[$i][0]['currency_id'] ?>"><?php echo $currs_s[$i][0]['code'] ?></option>
    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:right;"><?php echo __('type') ?>:</td>
                            <td style="text-align:left">
                                <select  name="data[type]" style="width: 180px" onchange="copy_change_type(this)">
                                    <option value=1><?php __('By minimum')?></option>
                                    <option value=2><?php __('By maximum')?></option>
                                    <option value=3><?php __('On average')?></option>
                                    <option value=4><?php __('Percentage increase')?></option>
                                    <option value=5><?php __('By number')?></option>
                                </select>
                                <input style="display:none;width:180px" type="text" name=data[type_num] />
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:right"><?php echo __('Same code of', true); ?>:</td>
                            <td style="text-align:left">
                                <select name='data[code_type]'  style="width:180px">
                                    <option value='ignore'><?php __('ignore')?></option>
                                    <option value='overwrite'><?php __('overwrite')?></option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </dd>
                <dd><input id="g_ids" name='data[ids]' style="display:none"/></dd>
                <dd style="margin-top:10px; margin-left:25%;width:150px;height:auto;">
                    <input type="button" onclick="jQuery('#generatert').xForm({action: '<?php echo $this->webroot ?>rates/generate_tmp', onsubmit: function(options) {
                                    if (jQuery('#copyName').val() == '') {
                                        jQuery('#copyName').jGrowlError(' Please select at least one templates rate table!');
                                        return false;
                                    }
                                    return true;
                                }})" value="<?php echo __('submit') ?>" class="input in-button">
                    <input type="button" onclick="closeCover('generatert');" value="<?php echo __('cancel') ?>" class="input in-button">
                </dd>
            </dl>
            <div id="container">
                <fieldset class="title-block" id="advsearch" style="display:none;width:100%;margin-left:1px;">
                    <form method="get">
                        <input type="hidden" name="advsearch" value="1"/>
                        <table  style="width:auto;">
                            <tbody>
                                <tr>
                                    <td style="display: none;">
                                        <label style="padding-top:3px;"><?php echo __('ratetmpname') ?>:</label>
                                        <input type="text" name="name" class="input in-text"/>
                                    </td>
                                    <td>
                                        <label style="padding-top:3px;"><?php echo __('codedecks') ?>:</label>
                                        <select id="search_code_deck" class="select in-select" name="search_code_deck">
                                            <option value=""><?php echo __('select') ?></option>
    <?php for ($i = 0; $i < count($codecs_s); $i++)
    {
        ?>
                                                <option value="<?php echo $codecs_s[$i][0]['code_deck_id'] ?>"><?php echo $codecs_s[$i][0]['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <label style="padding-top:3px;"><?php echo __('currency') ?>:</label>
                                        <select id="search_currency" name="search_currency" class="select in-select">
                                            <option value=""><?php echo __('select') ?></option>
    <?php for ($i = 0; $i < count($currs_s); $i++)
    {
        ?>
                                                <option value="<?php echo $currs_s[$i][0]['currency_id'] ?>"><?php echo $currs_s[$i][0]['code'] ?></option>
                    <?php } ?>
                                        </select>
                                    </td>
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
                            <?php for ($i = 0; $i < count($r_reseller); $i++)
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
                <div id="cover"></div>
                <?php
                $d = $p->getDataArray();
                if (count($d) == 0)
                {
                    ?>
                    <div class="msg"  id="msg_div"><?php echo __('no_data_found') ?></div>
                    <?php
                    }
                    else
                    {
                        ?>
                    <div class="msg"  id="msg_div"  style="display: none;"><?php echo __('no_data_found') ?></div>
    <?php } ?>
    <?php
    $d = $p->getDataArray();
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
                        <div id="toppage"></div>
                        <div>
                            <table class="list">
                                <col style="width:5%;">
                                <col style="width:5%;">
                                <col style="width:15%;">
                                <col style="width:15%;">
                                <col style="width:15%;">
                                <col style="width:15%;">
                                <col style="width:15%;">
                                <col style="width:15%;">
                                <thead>
                                    <tr>
                                        <td>
                                    <?php if ($_SESSION['login_type'] == '1')
                                    {
                                        ?>
                                                <input id="selectAll" class="select" type="checkbox" onclick="checkAllOrNot(this, 'ratetab');" value=""/>
                                    <?php } ?>
                                        </td>
                                        <td><?php echo $appCommon->show_order('rate_table_id', __('ID', true)) ?>    </td>
                                        <td><?php echo $appCommon->show_order('name', __('Name', true)) ?></td>
                                        <td><?php echo $appCommon->show_order('code_deck', __('Code Deck', true)) ?></td>
                                        <td><?php echo $appCommon->show_order('currency', __('Currency', true)) ?></td>
                                        <td><?php echo $appCommon->show_order('egress_rate', __('Usage Count', true)) ?></td>
                                        <td><?php echo $appCommon->show_order('jurisdiction_country_id', __('Jurisdiction Country', true)) ?></td>
                                        <td style="display: none;"><a href="javascript:void(0)" onclick="my_sort('use_gift_money', 'asc')"><img width="10" height="10" src="<?php echo $this->webroot ?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('use_gift_money') ?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('use_gift_money', 'desc')"><img width="10" height="10" src="<?php echo $this->webroot ?>images/list-sort-desc.png"></a></td>
                                        <td  style="display: none;"><a href="javascript:void(0)" onclick="my_sort('use_money_order', 'asc')"><img width="10" height="10" src="<?php echo $this->webroot ?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('use_money_order') ?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('use_money_order', 'desc')"><img width="10" height="10" src="<?php echo $this->webroot ?>images/list-sort-desc.png"></a></td>
                                        <td class="last"><?php echo __('action') ?></td>
                                    </tr>
                                </thead>
                                <tbody id="ratetab">
                                                   <?php
                                                   $mydata = $p->getDataArray();
                                                   $loop = count($mydata);
                                                   for ($i = 0; $i < $loop; $i++)
                                                   {
                                                       ?>
                                        <tr class="row-1"  id="i_<?php echo $loop - $i ?>">
                                            <td style="text-align:center">
        <?php if ($_SESSION['login_type'] == '1')
        {
            ?>
                                                    <input class="select" type="checkbox" value="<?php echo $mydata[$i][0]['rate_table_id'] ?>"/>
                                                <?php } ?>
                                            </td>
                                            <td class="in-decimal" style="text-align:center"><?php echo $mydata[$i][0]['rate_table_id'] ?></td>
                                            <td style="font-weight: bold;">

                                                <a title="<?php echo __('viewrates') ?>" class="link_width"  style="float:left;margin-left:10px;" 
                                                   href="<?php echo $this->webroot ?>clientrates/view/<?php echo $mydata[$i][0]['rate_table_id'] ?>/">
        <?php echo $mydata[$i][0]['name'] ?>
                                                </a>

                                            </td>
                                            <td><a style="width:100%;display:block" href="<?php echo $this->webroot ?>codedecks/codedeck_list?edit_id=<?php echo $mydata[$i][0]['code_deck_id'] ?>&viewType=rate"><?php echo $mydata[$i][0]['code_deck'] ?></a></td>
                                            <td><?php echo $mydata[$i][0]['currency'] ?></td>
                                            <td><?php echo $mydata[$i][0]['client_rate'] + $mydata[$i][0]['egress_rate'] ?></td>			
                                            <td><?php echo $appRate->show_jurisdiction_country($jurisdiction_countries, $mydata[$i][0]['jurisdiction_country_id']) ?></td>
                                            <td   style="text-align: center;"  align="center" valign="middle">
                                                <a title="<?php echo __('viewrates') ?>" style="float:left;margin-left:10px;" 
                                                   href="<?php echo $this->webroot ?>clientrates/view/<?php echo $mydata[$i][0]['rate_table_id'] ?>/">
                                                    <img src="<?php echo $this->webroot ?>images/bOrigTariffs.gif" />
                                                </a>
        <?php if ($_SESSION['login_type'] == 1)
        {
            ?>
                                                    <a title="<?php echo __('edit') ?>" style="float:left;margin-left:8px;" href="#" id='edit' rate_table_id='<?php echo $mydata[$i][0]['rate_table_id'] ?>'>
                                                        <i class="icon-edit"></i>
                                                    </a>
                                                    <a title="<?php echo __('del') ?>" style="float:left;margin-left:8px;" href="javascript:void(0)" onclick="ex_delConfirm(this, '<?php echo $this->webroot ?>rates/del_rate_tmp/<?php echo $mydata[$i][0]['rate_table_id'] ?>', 'rate table  <?php echo $mydata[$i][0]['name'] ?>');">
                                                        <i class="icon-remove"></i>
                                                    </a>
                                                    <a title="<?php echo __('copyratetmp') ?>" style="float:left;margin-left:8px;" href="javascript:void(0)" onclick="cover('copyratetmp');
                                                            document.getElementById('tmpid').value = '<?php echo $mydata[$i][0]['rate_table_id'] ?>';">
                                                        <img src="<?php echo $this->webroot ?>images/arrow_orange.gif" />
                                                    </a>
        <?php } ?>
                                            </td>
                                            <td style="display:none"><?php echo $mydata[$i][0]['reseller_id'] ?></td>
                                        </tr>
                                        <tr style="display:none;"><td>
                                                <dl id="i_<?php echo $loop - $i ?>-tooltip" class="tooltip">
                                                    <dd><?php echo __('createdate') ?>:</dd>
                                                    <dd><?php echo $mydata[$i][0]['create_time'] ?></dd>
                                                    <dd><?php echo __('updateat') ?>:</dd>
                                                    <dd><?php echo $mydata[$i][0]['modify_time'] ?></dd>
                                                </dl>				
                                            </td></tr>
    <?php } ?>
                                </tbody>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div id="tmppage">
    <?php echo $this->element('page'); ?>
                        </div>
                    </div>
                </div>
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
                            jQuery('#ratetab').trAdd({
                                action: "<?php echo $this->webroot ?>rates/save",
                                ajax: "<?php echo $this->webroot ?>rates/js_save",
                                onsubmit: function() {
                                    var ret = true;
                                    var rate_name = jQuery('#RateName').val();
                                    if (rate_name.length > 16 || /[^0-9A-Za-z-\_\s]+/.test(rate_name)) {
                                        jQuery('#RateName').addClass('invalid');
                                        jGrowl_to_notyfy('Name,allowed characters:a-z,A-Z,0-9,-,_,space,maximum of 16 characters in length! ', {theme: 'jmsg-error'});
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
                                    if (/[^0-9A-Za-z-\_\s]+/.test(rate_name) || name_length > 16)
                                    {
                                        jQuery('#RateName').addClass('invalid');
                                        jGrowl_to_notyfy('Name,allowed characters:a-z,A-Z,0-9,-,_,space,maximum of 16 characters in length! ', {theme: 'jmsg-error'})

                                        re = false;
                                    }
                                    return re;
                                }
                            });
                        });
                    });
                </script>
            </div>
<?php
}
else
{
    echo $this->element('rate/client_rate');
}
?>
