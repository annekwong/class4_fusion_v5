<style type="text/css">
    #advsearch .input {
        font-size: 0.87em;
        width: 100px;
    }
    #advsearch {
        text-align:right;
        display: none;
        margin-bottom: 10px;
        position: relative;
    }
    .list img {
        vertical-align: middle;
    }
    .form .value, .list-form .value{ text-align:center;}
    table.list input, textarea, .uneditable-input {
        width:50px;
    }
</style>
<script type="text/javascript">
    $(function () {
        $('#b-me-full legend select').bind('change', watchAction);
        watchAction();
        $('#actionPanelEdit select[name*=action]').bind('change', function () {
            var field = $(this).attr('name');
            watchField(field.substring(0, field.indexOf('_action')));
        }).each(function () {
            var field = $(this).attr('name');
            watchField(field.substring(0, field.indexOf('_action')));
        });
    });

</script>
<?php $mydata =$p->getDataArray();?>

<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Editing Rates') ?> <font class="editname"> <?php echo empty($name[0][0]['name'])||$name[0][0]['name']==''?'':'['.$name[0][0]['name'].']' ?> </font></li>
</ul>

<div class="heading-buttons">
    <h1><?php echo __('Editing Rates') ?> <font class="editname"> <?php echo empty($name[0][0]['name'])||$name[0][0]['name']==''?'':'['.$name[0][0]['name'].']' ?> </font></h1>
    <div class="buttons pull-right">
        <?php if(@$_GET['filter_effect_date'] == 'all'): ?>
        <a  onclick="show_current_rate();" class="link_btn btn btn-primary btn-icon glyphicons fire" href="#"><i></i> Show  Current </a>
        <?php else: ?>
        <a onclick="show_all_rate();" class="link_btn btn btn-primary btn-icon glyphicons list" href="<?php echo $this->webroot?>clientrates/view/<?php echo $table_id; ?>"><i></i> Show  All </a>
        <?php endif; ?>

        <?php  if ($_SESSION['role_menu']['Switch']['clientrates']['model_w']) {?>
        <a onclick="addItem();return false;" class="btn btn-primary btn-icon glyphicons circle_plus" href="###"><i></i> Create New</a>
        <a class="link_btn btn btn-primary btn-icon glyphicons remove" onClick="return myconfirm('Are you sure to remove all?', this)" href="<?php echo $this->webroot?>clientrates/mass_delete/<?php  echo $this->params['pass'][0];?><?php if(isset($search_flg)){echo '/0/'.$search_q.'/'.$_GET['effectiveDate'];} ?>" ><i></i> Delete All </a>
        <a class="link_btn delete_selected btn btn-primary btn-icon glyphicons remove" rel="popup" href="###"><i></i> <?php echo __('Delete Selected')?></a>
        <?php }?>
        <a class="link_back btn btn-default btn-icon glyphicons left_arrow" href="<?php echo $this->webroot?>rates/rates_list"><i></i> <?php echo __('Back',true);?></a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li><a  class="glyphicons justify" href="<?php echo $this->webroot?>clientrates/view/<?php echo $table_id?>/<?php echo $currency?>"><i></i><?php echo __('Rates',true);?> </a></li>
                <?php if ($jur_type == 3 || $jur_type == 4): ?>
                <li class="active"><a   class="glyphicons notes_2" href="<?php echo $this->webroot?>clientrates/view/<?php echo $table_id?>/<?php echo $currency?>/npan"><i></i><?php echo __('NPANXX Rate',true);?> </a></li>
                <?php endif; ?>
                <li><a class="glyphicons nails" href="<?php echo $this->webroot?>clientrates/simulate/<?php echo $table_id?>"><i></i> <?php echo __('Simulate',true);?></a></li>
                <?php  if ($_SESSION['role_menu']['Switch']['clientrates']['model_x']) {?>
                <li><a href="<?php echo $this->webroot?>clientrates/import/<?php echo $table_id?>"  class="glyphicons upload"><i></i> <?php echo __('Import',true);?></a></li>
                <li><a href="<?php echo $this->webroot?>downloads/rate/<?php echo $table_id?>"  class="glyphicons download"><i></i> <?php echo __('Export',true);?></a></li>
                <?php }?>
            </ul>
        </div>
        <div class="widget-body">

       <div class="filter-bar">
                <form action="" method="get" id="likesearch"  >
                    <!-- Filter -->
                    <div>
                        <label>Search:</label>
                        <input type="text" id="search-_q_rate"
                       value="<?php if(!empty($_GET['search']['_q'])){echo $_GET['search']['_q'] ;}else{ echo '';}   ?>"
                       class=""  name="search[_q]" />
                    </div>
                    <!-- // Filter END -->
                    <div>
                        <label>Effective Date :</label>
                        <input type="text" id="search-_q_rate"
                               value="<?php if(!empty($_GET['effectiveDate'])){echo $_GET['effectiveDate'] ;}else{ echo '';}   ?>"
                               onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" name="effectiveDate" />
                        <input type="hidden" value="1" name="search_flg" />
                    </div>
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn">Query</button>
                    </div>
                    <!-- // Filter END -->

                </form>
        </div>


     <div class="clearfix"></div>
    <div class="separator bottom row-fluid">
        <div class="pagination pagination-large pagination-right margin-none">
            <?php echo $this->element('page'); ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <h2 class="msg center" id="noRows"><?php  echo __('no_data_found') ?></h2>
    <form id="objectForm" method="post" action="<?php echo $this->webroot?>clientrates/add_rate?page=<?php echo $p->getCurrPage()?>&size=<?php echo $p->getPageSize()?>">
        <input type="hidden" id="delete_rate_id" value="" name="delete_rate_id" class="input in-hidden">
        <input type="hidden" id="id" value="<?php echo $table_id?>" name="id" class="input in-hidden">
        <input type="hidden" value="1" name="page" class="input in-hidden">
        <div id="showadd" style="display:none;">
            <table class="list list-form">
                <tbody>
                    <tr>
                        <td>Rate Table Name:<input class="input in-text in-input" readonly value="<?=$addShowResult['name']?>" /></td>
                        <td>Code Deck:<input class="input in-text in-input" readonly value="<?=$addShowResult['code_deck_id']?>" /></td>
                        <td>Currency:<input class="input in-text in-input" readonly value="<?=$addShowResult['currency']?>" /></td>
                        <td>Type:<input class="input in-text in-input" readonly value="<?=$addShowResult['rate_type']?>" /></td>
                        <td>Jurisdiction:<input class="input in-text in-input" readonly value="<?=$addShowResult['jurisdiction_country_id']?>" /></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <table id="list" style="display: none;" class="list list-form  footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
            <thead>
                <tr>
                    <th width="25"><input id="selectAll" name="checkbox" type="checkbox"/></th>
                    <?php if (!in_array($jur_type, array(3, 4))): ?>
                    <th><?php echo $appCommon->show_order('code',__('Code',true))?></th>
                    <th><?php echo $appCommon->show_order('code_name',__('Code Name',true))?></th>
                    <th><?php echo $appCommon->show_order('country',__('Country',true))?></th>
                    <?php endif; ?>
                    <?php if ($jur_type == 3 || $jur_type == 4): ?>
                    <th><?php echo __('OCN',true);?></th>
                    <th><?php echo __('LATA',true);?></th>
                    <?php endif; ?>
                    <th><?php echo __('Rate',true);?></th>
                    <?php if($appRate->is_show_jur_rate($table_id)){?>
                    <th><?php echo __('Intra Rate',true);?></th>
                    <th><?php echo __('Inter Rate',true);?></th>
                    <?php }?>
                    <th><?php echo $appCommon->show_order('effective_date',__('Effective Date',true))?></th>
                    <th><?php echo $appCommon->show_order('end_date',__('End Date',true))?></th>
                    <th><span rel="helptip" class="helptip" id="ht-100006"><?php echo __('Extra Fields',true);?></span><span class="tooltip" id="ht-100006-tooltip">Min Time / Interval / Grace Time / Profile / Notes</span></th>
                    <?php  if ($_SESSION['role_menu']['Switch']['clientrates']['model_w']) {?> <th class="last">&nbsp;</th>
                    <?php }?>
                </tr>
            </thead>
            <tbody id="rows">
                <tr id="tpl">
                    <td><input class="selected" type="checkbox"/><input type="hidden" name="rate_id" /></td>
                    <?php if (!in_array($jur_type, array(3, 4))): ?>
                    <td>
                        <input type="text" name="code" style="_width:60px; _float:left;font-weight:bold;" />
                    </td>
                    <td id="tpl-code_name-write"><input type="text" name="code_name" class="code_name-input code_name" style="_float:left; _width:80px;" />
                    </td>
                    <td><input type="text" name="country" class="country-input country"  style="_float:left; _width:80px;"  />
                    </td>
                    <td id="tpl-code_name-read"><small id="tpl-code_name-text">code_name</small>
                        <input type="hidden" name="code_name" /></td>
                    <?php endif; ?>
                    <?php if ($jur_type == 3 || $jur_type == 4): ?>
                    <td>
                        <input type="text"  rel="format_number" name="ocn"  class="country-input country"  style="_float:left; _width:80px;" />
                        </td>
                    <td>
                        <input type="text"  rel="format_number"  name="lata"  class="country-input country"  style="_float:left; _width:80px;" />
                    </td>
                    <?php endif; ?>

                    <td ><input type="text" rel="format_number" name="rate" style="font-weight:bold;text-align:right;" /></td>
                    <?php if($appRate->is_show_jur_rate($table_id)){?>
                    <td><input type="text"  rel="format_number" class="in-decimal" style="width:60px;" name="intra_rate"/>
                        &nbsp;&nbsp;&nbsp;</td>
                    <td><input type="text" rel="format_number" class="in-decimal" style="width:60px;" name="inter_rate"/>
                        &nbsp;&nbsp;&nbsp;</td>
                    <?php }?>
                    <td>
                        <input type="text" name="effective_date" style="width:100px;"   class="input in-text wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});"  />
                        <select name="effective_date_timezone" style="width:100px;">
                            <option value="-12">GMT -12:00</option>
                            <option value="-11">GMT -11:00</option>
                            <option value="-10">GMT -10:00</option>
                            <option value="-09">GMT -09:00</option>
                            <option value="-08">GMT -08:00</option>
                            <option value="-07">GMT -07:00</option>
                            <option value="-06">GMT -06:00</option>
                            <option value="-05">GMT -05:00</option>
                            <option value="-04">GMT -04:00</option>
                            <option value="-03">GMT -03:00</option>
                            <option value="-02">GMT -02:00</option>
                            <option value="-01">GMT -01:00</option>
                            <option value="+00"   selected="selected">GMT +00:00</option>
                            <option value="+01">GMT +01:00</option>
                            <option value="+02">GMT +02:00</option>
                            <option value="+03">GMT +03:00</option>
                            <option value="+03">GMT +03:30</option>
                            <option value="+04">GMT +04:00</option>
                            <option value="+05">GMT +05:00</option>
                            <option value="+06">GMT +06:00</option>
                            <option value="+07">GMT +07:00</option>
                            <option value="+08">GMT +08:00</option>
                            <option value="+09">GMT +09:00</option>
                            <option value="+10">GMT +10:00</option>
                            <option value="+11">GMT +11:00</option>
                            <option value="+12">GMT +12:00</option>
                            <option value=""></option>
                        </select>
                    </td>
                    <td >
                        <input type="text" name="end_date" style="width:100px;"  class="input in-text wdate" onFocus="WdatePicker({startDate:'%y-%M-01 23:59:59',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:false})" />
                        <select name="end_date_timezone" style="width:100px;">
                            <option value="-12">GMT -12:00</option>
                            <option value="-11">GMT -11:00</option>
                            <option value="-10">GMT -10:00</option>
                            <option value="-09">GMT -09:00</option>
                            <option value="-08">GMT -08:00</option>
                            <option value="-07">GMT -07:00</option>
                            <option value="-06">GMT -06:00</option>
                            <option value="-05">GMT -05:00</option>
                            <option value="-04">GMT -04:00</option>
                            <option value="-03">GMT -03:00</option>
                            <option value="-02">GMT -02:00</option>
                            <option value="-01">GMT -01:00</option>
                            <option value="+00"   selected="selected">GMT +00:00</option>
                            <option value="+01">GMT +01:00</option>
                            <option value="+02">GMT +02:00</option>
                            <option value="+03">GMT +03:00</option>
                            <option value="+03">GMT +03:30</option>
                            <option value="+04">GMT +04:00</option>
                            <option value="+05">GMT +05:00</option>
                            <option value="+06">GMT +06:00</option>
                            <option value="+07">GMT +07:00</option>
                            <option value="+08">GMT +08:00</option>
                            <option value="+09">GMT +09:00</option>
                            <option value="+10">GMT +10:00</option>
                            <option value="+11">GMT +11:00</option>
                            <option value="+12">GMT +12:00</option>
                            <option value=""></option>
                        </select>
                    </td>
                    <td id="tpl-params-block"><a href="#" class="tpl-params-link" title="Additional properties"><small id="tpl-params-text"></small> <b class="neg">&raquo;</b></a>
                        <div class="tooltip-box params-block" style="display:none;">
                            <table id="xs">
                                <tr>

                                    <td><?php echo __('Setup Fee',true);?>:
                                        <input type="text" style="width:60px;"  rel="format_number"  name="setup_fee" style="text-align:right;" /></td>
                                    <td><?php echo __('Min Time',true);?>:
                                        <input id="min_time" style="width:60px;"  type="text" name="min_time"    class="in-decimal" />
                                        sec</td>
                                    <td><?php echo __('Interval',true);?>:
                                        <input type="text" style="width:60px;"  name="interval" class="in-decimal" />
                                        sec</td>
                                    <td><?php echo __('Grace Time',true);?>:
                                        <input id="grace_time"  style="width:60px;"  type="text" name="grace_time" class="in-decimal" />
                                        sec</td>
                                    <td><?php echo __('Seconds',true);?>:
                                        <input type="text"  style="width:60px;"  name="seconds" class="in-decimal" />
                                        sec</td>
                                    <td><?php echo __('Profile',true);?>:
                                        <?php
                                        echo $form->input('client_id',array('options'=>$timepro,  'id'=>"time_profile_id",'name'=>"time_profile_id",'empty'=>' ',
                                        'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select','class'=>"in-decimal"));
                                        ?>
                                        &nbsp;&nbsp;&nbsp; </td>

                                    <!--<td><?php echo __('Time Zone',true);?>:
                                    <?php
                                                                                                                  echo $form->input('client_id',array('options'=>$appRate->get_time_zone(),  'id'=>"zone",'name'=>"zone",'empty'=>' ',
                                                                                                  'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select','class'=>"in-decimal"));
                                                                                                                  ?>
                                      &nbsp;&nbsp;&nbsp; </td>-->
                                    <?php if($appRate->is_show_jur_rate($table_id)){?>
                                    <!--
                                      <td>Intra rate:</td>
                                      <td><input type="text"  rel="format_number" class="in-decimal" name="intra_rate"/>
                                        &nbsp;&nbsp;&nbsp;</td>
                                      <td>Inter rate:</td>
                                      <td><input type="text" rel="format_number" class="in-decimal" name="inter_rate"/>
                                        &nbsp;&nbsp;&nbsp;</td>
                                    -->
                                    <td><?php echo __('Local Rate',true);?>:
                                        <input type="text" style="width:60px;"   class="in-decimal"  rel="format_number" name="local_rate" />
                                        &nbsp;&nbsp;&nbsp;</td>
                                </tr>
                                <?php }?>
                            </table>
                        </div></td>
                    <?php  if ($_SESSION['role_menu']['Switch']['clientrates']['model_w']) {?>
                        <td style="text-align:center;"><a title="Delete" href="#" id="tpl-delete-row"><i class="icon-remove"></i></a></td>
                    <?php }?>
                </tr>
            </tbody>
        </table>
    </form>
    <div class="row-fluid">
        <div class="pagination pagination-large pagination-right margin-none">
            <?php echo $this->element('page'); ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php if($_SESSION['login_type']=='1'){?>
    <fieldset id="b-me">
        <legend> <?php  if ($_SESSION['role_menu']['Switch']['clientrates']['model_w']) {?>
            <a onClick="$('#b-me').hide();$('#b-me-full').show();return false;" href="#"> <span rel="helptip" class="helptip" id="ht-100007">Mass Edit»</span> <span class="tooltip" id="ht-100007-tooltip">Edit all rates covered by current search terms at once</span> </a><?php }?> </legend>
    </fieldset>
    <form id="actionForm" method="POST" action="<?php echo $this->webroot?>clientrates/view/<?php echo $table_id?>/massEdit/<?php echo $_GET['page']?>/<?php echo $_GET['size']?>">
        <input type="hidden" id="id" value="<?php echo $table_id?>" name="id" class="input in-hidden">
        <input type="hidden" id="isQuery" name="isQuery" value="<?php isset($_GET['search'])?1:0; ?>" />
        <input type="hidden" id="isAll" value="<?php isset($_GET['search']['filter_effect_date'])?1:0; ?>" />
        <input type="hidden" id="stage_param" value="preview" name="stage" class="input in-hidden">
        <fieldset style="display: none;" id="b-me-full">
            <legend> <a href="#" onClick="$('#b-me').show();$('#b-me-full').hide();return false;">Mass Edit Action: </a>
                <select id="action" name="action" class="input in-select">
                    <option value="insert">insert as new rates</option>
                    <option value="update">update current rates</option>
                    <option value="delete">delete found rates</option>
                    <option value="updateall">update all rates</option>
                </select>
            </legend>
            <input type="hidden" name="searchstr" value="<?php echo isset($_GET['search']['_q'])?$_GET['search']['_q']:'' ?>" />
            <div id="actionPanelEdit" style="display: block;">
                <table class="form" border="00" cellspacing="0" cellpadding="00">
                    <tr>
                        <td><label> <span rel="helptip" class="helptip" id="ht-100008"><?php echo __('rate',true);?></span> <span class="tooltip" id="ht-100008-tooltip">Price per 1 minute of call</span>: </label>
                            <select id="rate_per_min_action" name="rate_per_min_action" class="input in-select">
                                <option value="none">preserve</option>
                                <option value="set">set to</option>
                                <option value="inc">inc for</option>
                                <option value="dec">dec for</option>
                                <option value="mul">mul for</option>
                            </select></td>
                        <td style="text-align: left;">
                            <input type="text" id="rate_per_min_value" class="in-decimal input in-text" value="0.0000" name="rate_per_min_value" style="display: none;width:120px;">
                            <!--                <input type="button" class="set_to_null" style="width:auto;" value="Set As NULL" />-->
                            <a href="###" class="set_to_null" title="Set As NULL">
                                <i class="icon-remove"></i>
                            </a>
                        </td>
                        <td><label> <span rel="helptip" class="helptip" id="ht-100009"><?php echo __('Min Time',true);?></span> <span class="tooltip" id="ht-100009-tooltip">Minimal time of call that will be tarificated (seconds). For example, if total call time was 20 seconds, and Min Time is 30, then client will pay for 30 seconds of call</span>: </label>
                            <select id="min_time_action" name="min_time_action" class="input in-select">
                                <option value="none">preserve</option>
                                <option value="set">set to</option>
                            </select></td>
                        <td style="text-align: left;"><input type="text" id="min_time_value" class="in-decimal input in-text" value="1" name="min_time_value" style="display: none;"></td>
                        <td><label><span rel="helptip" class="helptip" id="ht-100010"><?php echo __('Effective Date',true);?></span><span class="tooltip" id="ht-100010-tooltip">Rate start date, before this date the rate will not be used</span>:</label>
                            <select id="effective_from_action" name="effective_from_action" class="input in-select">
                                <option value="none">preserve</option>
                                <option value="set">set to</option>
                            </select></td>
                        <td style="text-align: left;"><div id="effective_from_value">
                                <table class="in-date"  border="00" cellspacing="0" cellpadding="00">
                                    <tr>
                                        <td><input type="text" id="effective_from_value-wDt"
                                                   value="<?php echo date('Y-m-d 00:00:00')?>"   onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});"
                                                   class="input in-text wdate",readonly=true,
                                                   name="effective_from_value"></td>
                                    </tr>
                                </table>
                            </div></td>
                        <?php  if ($_SESSION['role_menu']['Switch']['clientrates']['model_w']) {?>
                        <td class="buttons" rowspan="3"><input type="submit" value="Preview" id="action_preview" class="input in-submit btn btn-default">
                            <input type="button" value="Process" onClick="return actionProcess();" id="action_process" class="input in-button btn btn-default"></td>
                        <?php }?>
                    </tr>
                    <tr>
                        <td><label><span rel="helptip" class="helptip" id="ht-100011"><?php echo __('Setup Fee',true);?></span><span class="tooltip" id="ht-100011-tooltip">Fee, that applied when time of call is greater than 0</span>:</label>
                            <select id="pay_setup_action" name="pay_setup_action" class="input in-select">
                                <option value="none">preserve</option>
                                <option value="set">set to</option>
                                <option value="inc">inc for</option>
                                <option value="dec">dec for</option>
                            </select></td>
                        <td style="text-align: left;"><input type="text" id="pay_setup_value" class="in-decimal input in-text" value="0.0000" name="pay_setup_value" style="display: none;"></td>
                        <td><label><span rel="helptip" class="helptip" id="ht-100012"><?php echo __('Interval',true);?></span><span class="tooltip" id="ht-100012-tooltip">Tarification interval (seconds). This parameter is used, when Min Time time expires</span>:</label>
                            <select id="pay_interval_action" name="pay_interval_action" class="input in-select">
                                <option value="none">preserve</option>
                                <option value="set">set to</option>
                            </select></td>
                        <td style="text-align: left;"><input type="text" id="pay_interval_value" class="in-decimal input in-text" value="1" name="pay_interval_value" style="display: none;"></td>
                        <td><label><span rel="helptip" class="helptip" id="ht-100013"><?php echo __('end date',true);?></span><span class="tooltip" id="ht-100013-tooltip">Rate end date, after this date the rate will not be used</span>:</label>
                            <select id="end_date_action" name="end_date_action" class="input in-select">
                                <option value="none">preserve</option>
                                <option value="set">set to</option>
                            </select></td>
                        <td style="text-align: left;"><div id="end_date_value">
                                <table class="in-date">
                                    <tr>
                                        <td><input type="text" id="end_date_value-wDt"
                                                   onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});"    class="input in-text wdate" readonly value="<?php echo date('Y-m-d 23:59:59')?>"
                                                   name="end_date_value"></td>
                                    </tr>
                                </table>
                            </div></td>
                    </tr>
                    <tr>
                        <td><label><span rel="helptip" class="helptip" id="ht-100014"><?php echo __('Grace Time',true);?></span><span class="tooltip" id="ht-100014-tooltip">Time interval (seconds), below which calls are not tarificated</span>:</label>
                            <select id="grace_time_action" name="grace_time_action" class="input in-select">
                                <option value="none">preserve</option>
                                <option value="set">set to</option>
                            </select></td>
                        <td style="text-align: left;"><input type="text" id="grace_time_value" class="in-decimal input in-text" value="0" name="grace_time_value" style="display: none;"></td>
                        <td><label><span rel="helptip" class="helptip" id="ht-100015"><?php echo __('Profile',true);?></span><span class="tooltip" id="ht-100015-tooltip">Which time profile will be used for current rate</span>:</label>
                            <select id="id_time_profiles_action" name="id_time_profiles_action" class="input in-select">
                                <option value="none">preserve</option>
                                <option value="set">set to</option>
                            </select></td>
                        <td style="text-align: left;"><select id="id_time_profiles_value" name="id_time_profiles_value" class="input in-select" style="display: none;">
                                <option value="2">business time</option>
                                <option value="3">non-business time</option>
                                <option value="4">weekends</option>
                                <option value="8">1200</option>
                                <option value="9">all time</option>
                                <option value="10">OFFLINE</option>
                                <option value="11">Ramka Tutulik</option>
                                <option value="15">fulltime</option>
                                <option value="1">all time 2</option>
                            </select></td>
                        <td><label><span rel="helptip" class="helptip" id="ht-100016"><?php echo __('Seconds',true);?></span><span class="tooltip" id="ht-100016-tooltip">Additional notes about rate (like CLI, direct, etc)</span>:</label>
                            <select id="notes_action" name="notes_action" class="input in-select">
                                <option value="none">preserve</option>
                                <option value="set">set to</option>
                            </select></td>
                        <td style="text-align: left;"><input type="text" id="notes_value" value="" name="seconds_value" class="input in-text" style="display: none;"></td>
                    </tr>
                    <tr>
                        <td><label><span rel="helptip" class="helptip" id="ht-100014"><?php echo __('Interstate Rate',true);?> </span><span class="tooltip" id="ht-100014-tooltip">Time interval (seconds), below which calls are not tarificated</span>:</label>
                            <select id="inter_rate_action" name="inter_rate_action" class="input in-select">
                                <option value="none">preserve</option>
                                <option value="set">set to</option>
                            </select></td>
                        <td style="text-align: left;">
                            <input type="text" id="inter_rate_value" class="in-decimal input in-text" value="0" name="inter_rate_value" style="display: none;width:120px;">
                            <a href="###" class="set_to_null" title="Set As NULL">
                                <i class="icon-remove"></i>
                            </a>
                        </td>
                        <td ><label><span rel="helptip" class="helptip" id="ht-100016">Intrastate Rate</span><span class="tooltip" id="ht-100016-tooltip">Additional notes about rate (like CLI, direct, etc)</span>:</label>
                            <select id="intra_rate_action" name="intra_rate_action" class="input in-select">
                                <option value="none">preserve</option>
                                <option value="set">set to</option>
                            </select></td>
                        <td style="text-align: left;">
                            <input type="text" id="intra_rate_value" value="" name="intra_rate_value" class="input in-text" style="display: none;width:120px;">
                            <a href="###" class="set_to_null" title="Set As NULL">
                                <i class="icon-remove"></i>
                            </a>
                        </td>
                        <td><label><span rel="helptip" class="helptip" id="ht-100016"><?php echo __('Local Rate',true);?></span><span class="tooltip" id="ht-100016-tooltip">Additional notes about rate (like CLI, direct, etc)</span>:</label>
                            <select id="local_rate_action" name="local_rate_action" class="input in-select">
                                <option value="none">preserve</option>
                                <option value="set">set to</option>
                            </select></td>
                        <td style="text-align: left;">
                            <input type="text" id="local_rate_value" value="" name="local_rate_value" class="input in-text" style="display: none;width:120px;">
                            <a href="###" class="set_to_null" title="Set As NULL">
                                <i class="icon-remove"></i>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="actionPanelDelete" style="display: none;">
                <input class="btn btn-default" type="hidden" name="rate_ids" id="rate_ids" value=""/>
                <?php  if ($_SESSION['role_menu']['Switch']['clientrates']['model_w']) {?>
                <input type="button" value="Process" onClick="return actionProcess();" id="action_process" class="input in-button btn btn-default"/>
                <?php }?>
            </div>
        </fieldset>
    </form>
    <?php }?>
    <script type="text/javascript">
        function mass_fun(){
            var re=true;

            var arrs;
            arrs=Array();
            jQuery('#list tbody tr.row-1,#list tbody tr.row-2').each(
            function(){
                if (jQuery(this).find("input[type='checkbox']").attr("checked") == true)
                {
                    var rate_id=jQuery(this).find('input[name*=rate_id]').val();
                    var arr;
                    arr=Array(rate_id);
                    arrs.push(arr);
                }
            }
        );
            $("#rate_ids").attr('value',arrs);
            //alert(arrs);
            if (0)//($("#action").val() != 'delete')
            {
                if(! /^\d+(\.)?\d+$/.test(jQuery('#inter_rate_value:visible').val())){
                    jGrowl_to_notyfy("The field Intrastate Rate must be numeric only!",{theme:'jmsg-error'});
                    re =false;
                }

                if(! /^\d+(\.)?\d+$/.test(jQuery('#local_rate_value:visible').val())){
                    jGrowl_to_notyfy("The field Local rate must be numeric only!",{theme:'jmsg-error'});
                    re =false;
                }

                if(! /^\d+(\.)?\d+$/.test(jQuery('#intra_rate_value:visible').val())){
                    jGrowl_to_notyfy("Interstate Rate, must contain numeric characters only!",{theme:'jmsg-error'});
                    re =false;
                }
            }
            return re;
        }

        function valiReport(){
            var arrs;
            arrs=Array();
            var re=true;
            jQuery('#list tbody tr.row-1,#list tbody tr.row-2').each(
            function(){
                //	var code_name=jQuery(this).find('input[id*=code]').val();
                var effective_date=jQuery(this).find('input[id*=effective_date]').val();
                var end_date=jQuery(this).find('input[id*=end_date]').val();
                var time_profile_id=jQuery(this).find('select[id*=time_profile_id]').val();
                var time_profile=jQuery(this).find('select[id*=time_profile_id]').find("option:selected").text();
                var arr;
                arr=Array(effective_date,end_date,time_profile_id);
                for(var i in arrs){
                    if(!isReport(arrs[i],arr)){
                        re=true;
                        time_profile=jQuery.trim(time_profile);
                        if(time_profile==''){
                            time_profile='is already in use!';

                        }
                        //	jQuery.jGrowlError(code_name+' '+time_profile );
                        //	break;
                    }
                }
                arrs.push(arr);
            }
        );



            jQuery('input[id$=-code]:visible').each(function(){
                if(/\D/.test(jQuery(this).val())){
                    jGrowl_to_notyfy('Code , must be whole number! ',{theme:'jmsg-error'});
                    re = false;
                }
            });
            jQuery('input[id$=min_time]').each(function(){
                if(/\D/.test(jQuery(this).val())){
                    jGrowl_to_notyfy("Min Time, must be whole number!",{theme:'jmsg-error'});
                    re =false;
                }
            });

            jQuery('input[id$=interval]').each(function(){
                if(/\D/.test(jQuery(this).val())){
                    jGrowl_to_notyfy("Interval, must be whole number! ",{theme:'jmsg-error'});
                    re =false;
                }
            });
            jQuery('input[id$=grace_time]').each(function(){
                if(/\D/.test(jQuery(this).val())){
                    jGrowl_to_notyfy("Grace Time, must be whole number!  ",{theme:'jmsg-error'});
                    re =false;
                }


            })
            jQuery('input[id$=seconds]').each(function(){
                if(/\D/.test(jQuery(this).val())){
                    jGrowl_to_notyfy("Seconds, must be whole number!",{theme:'jmsg-error'});
                    re =false;
                }
            });

            return re;
        }


        function isReport(arr1,arr2){
            if(arr2[0]=='' && arr2[3]==''){
                return true;
            }
            if(arr1[0]==arr2[0] && arr1[3]==arr2[3]){
                return false;
            }
            return true;
        }
        jQuery(document).ready(function(){
            jQuery('#selectAll').selectAll('.selected');
        });

    </script>
    <script type="text/javascript">
        //<[!CDATA[
        function watchAction()
        {
            var sAction = $('#action').val();
            if (sAction == 'delete') {
                $('#actionPanelEdit').hide();
                $('#actionPanelDelete').show();
            } else {
                $('#actionPanelEdit').show();
                $('#actionPanelDelete').hide();
            }

            var elAction = $('#effective_from_action').get(0);
            if (sAction == 'insert' && elAction.options.length == 2) {
                // elAction.options[0] = null;
            } else if (sAction == 'update' && elAction.options.length == 1) {
                var elOption = document.createElement('option');
                elOption.value = 'none';
                elOption.innerHTML = 'preserve';
                elAction.insertBefore(elOption, elAction.options[0]);
                elAction.selectedIndex = 0;
            }
            watchField('effective_from');
        }
        function watchField(fname)
        {
            if ($('#'+fname+'_action').val() == 'none') {
                $('#'+fname+'_value').hide();
            } else {
                $('#'+fname+'_value').show();
            }
        }

        function actionProcess()
        {
            if (!confirm('Continue with specified parameters?')) {
                return false;
            }
            if(! mass_fun() && $("#action").val() != 'delete'){
                return false;
            }
            $('#action_preview').attr('disabled', 'disabled');
            $('#action_process').attr('disabled', 'disabled');
            $('#stage_param').val('process');
            //鍋氭牎楠?




            $('#actionForm').submit();
        }
        //]]>
    </script>
    <script type="text/javascript">
        function codename_all_callback(code_name){
            var data=jQuery.ajaxData('<?php echo $this->webroot?>codedecks/find_codename/'+code_name);
            data=eval(data);
            jQuery('#list tr:visible').each(function(){
                if(jQuery(this).find('td:nth-child(3)').find('input').val()==code_name){
                    jQuery(this).remove();
                }
            });
            for(var i in data){
                var obj=data[i].Code;
                var code=obj.code;
                var code_name=obj.name;
                var country=obj.country;

                var tr=addItem();
                jQuery(tr).find('input[id*=-code]').val(code);
                jQuery(tr).find('input[id*=-code_name]').val(code_name);
                jQuery(tr).find('input[id*=-country]').val(country);
                jQuery(tr).find('input[id$=-rate]').val(jQuery(CodeNameTr).find('input[id$=-rate]').val());
                jQuery(tr).find('input[id$=-setup_fee]').val(jQuery(CodeNameTr).find('input[id$=-setup_fee]').val());
                jQuery(tr).find('input[id$=-effective_date]').val(jQuery(CodeNameTr).find('input[id$=-effective_date]').val());
                jQuery(tr).find('input[id$=-end_date]').val(jQuery(CodeNameTr).find('input[id$=-end_date]').val());
                CodeNameTr.remove();
            }
        }
        function country_all_callback(country){
            var data=jQuery.ajaxData('<?php echo $this->webroot?>codedecks/find_country/'+country);
            data=eval(data);
            jQuery('#list tr:visible').each(function(){
                if(jQuery(this).find('td:nth-child(4)').find('input').val()==country){
                    jQuery(this).remove();
                }
            });
            for(var i in data){
                var obj=data[i].Code;
                var code=obj.code;
                var code_name=obj.name;
                var country=obj.country;
                var tr=addItem();
                jQuery(tr).find('input[id*=-code]').val(code);
                jQuery(tr).find('input[id*=-code_name]').val(code_name);
                jQuery(tr).find('input[id*=-country]').val(country);
                jQuery(tr).find('input[id$=-rate]').val(jQuery(CodeNameTr).find('input[id$=-rate]').val());
                jQuery(tr).find('input[id$=-setup_fee]').val(jQuery(CodeNameTr).find('input[id$=-setup_fee]').val());
                jQuery(tr).find('input[id$=-effective_date]').val(jQuery(CodeNameTr).find('input[id$=-effective_date]').val());
                jQuery(tr).find('input[id$=-end_date]').val(jQuery(CodeNameTr).find('input[id$=-end_date]').val());
                CodeNameTr.remove();
            }
        }
    </script>
    <?php if(isset($_GET['stage'])&& $_GET['stage']=='preview'){?>
    <fieldset id="actionPanelPreview">
        <legend><?php echo __('Mass Edit / Preview results',true);?></legend>
        <table class="list">
            <thead>
                <tr>
                    <td width="10%"><span rel="helptip" class="helptip" id="ht-100017"><?php echo __('code',true);?></span> <span class="tooltip" id="ht-100017-tooltip"><b><?php echo __('Priority of rates',true);?>:</b> longest code has highest priority, then effective date is applied.</span></td>
                    <td width="20%"><?php echo __('code_name',true);?></td>
                    <td width="7%"><span rel="helptip" class="helptip" id="ht-100018"><?php echo __('rate',true);?></span> <span class="tooltip" id="ht-100018-tooltip">Price per 1 minute of call</span></td>
                    <td width="7%"><span rel="helptip" class="helptip" id="ht-100019"><?php echo __('Setup Fee',true);?></span> <span class="tooltip" id="ht-100019-tooltip">Fee, that applied when time of call is greater than 0</span></td>
                    <td width="15%"><span rel="helptip" class="helptip" id="ht-100020"><?php echo __('Effective Date',true);?></span><span class="tooltip" id="ht-100020-tooltip">Rate start date, before this date the rate will not be used</span></td>
                    <td width="15%"><span rel="helptip" class="helptip" id="ht-100021"><?php echo __('end date',true);?></span><span class="tooltip" id="ht-100021-tooltip">Rate end date, after this date the rate will not be used</span></td>
                    <td width="21%" class="last"><span rel="helptip" class="helptip" id="ht-100022"><?php echo __('Extra Fields',true);?></span><span class="tooltip" id="ht-100022-tooltip">Min Time / Interval / Grace Time / Profile / Notes</span></td>
                    <?php  if ($_SESSION['role_menu']['Switch']['clientrates']['model_w']) {?><td class="last">&nbsp;</td>
                    <?php }?>
                </tr>
            </thead>
            <tbody>
                <?php
                $size=count($previewRates);
                for ($i=0;$i<$size;$i++){?>
                <tr class="row-1">
                    <td><b><?php echo $previewRates[$i][0]['code']?></b></td>
                    <td><?php echo $previewRates[$i][0]['code_name']?></td>
                    <td class="in-decimal"><?php echo $previewRates[$i][0]['rate']?></td>
                    <td class="in-decimal"><?php echo $previewRates[$i][0]['setup_fee']?></td>
                    <td align="center"><?php echo $previewRates[$i][0]['code_name']?></td>
                    <td align="center">&mdash;<?php echo $previewRates[$i][0]['effective_date']?></td>
                    <td class="last">1 / 1 / 0 sec &mdash; <?php echo $previewRates[$i][0]['tf']?></td>
                    <?php  if ($_SESSION['role_menu']['Switch']['clientrates']['model_w']) {?>
                    <td><a href="#" id="tpl-delete-row"><i class='icon-remove'></i></a></td>
                    <?php }?>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </fieldset>
    <?php }?>
    <?php  if ($_SESSION['role_menu']['Switch']['clientrates']['model_w']) {?>
    <div id="form_footer" class=" buttons center">
        <?php if($_SESSION['login_type']=='1'){?>

        <input id="sub" type="button" value="<?php echo __('Submit',true);?>"
               onclick="this.disabled=true;" class="input in-button btn btn-primary"/>

        <input type="button" value="<?php echo __('Cancel',true);?>" onClick="history.back()" class="input in-button btn btn-default"/>
        <?php }?>
    </div>
    <?php }?>
    <script type="text/javascript">
        jQuery('#sub').click(
        function(){
            if(valiReport()){
                jQuery('#objectForm').submit();
            }else{
                jQuery(this).removeAttr('disabled');
            }


        });
        var lastId = 0;
        var eRows = $('#rows');
        var eTpl = $('#tpl').unbind();
        var profiles=<?php echo $t?>;
        //var profiles = {"2":"business time","3":"non-business time","4":"weekends","8":"1200","9":"all time","10":"OFFLINE","11":"Ramka Tutulik","15":"fulltime","1":"all time 2"};
    </script>
    <script type="text/javascript">
        function showadd(){
            document.getElementById('showadd').style.display='block';
        }
        function addItem(rows, append)
        {
            lastId++;
            if (lastId == 1) {
                $('#noRows').hide();
                $('.list-form').show();
            }
            if (!rows || !rows['rate_id']) {
                row = {
                    'effective_date': '<?php echo date('Y-m-d 00:00:00')?>',
                    'time_profile_id': '',
                    'rate': '0.000000',
                    'min_time': '1',
                    'seconds': '60',
                    'interval': '1',
                    'grace_time': '0',
                    'intra_rate': '',
                    'inter_rate': '',
                    'local_rate': ''
                };
            }else{
                row=rows;
            }
            if (profiles[row['time_profile_id']] == undefined) {
                row['time_profile_id'] = '';
            }
            for (k in row) { if (row[k] == null) row[k] = ''; }
            if(row['rate'] != '') {
                row['rate'] = printf('%.6f', row['rate']);
            }
            row['setup_fee'] = printf('%.6f', row['setup_fee']);
            var prefixId = 'row-'+lastId;
            var prefixName = 'rates['+lastId+']';
            var tRow = eTpl.clone().attr('id', prefixId).show();//涓存椂鍑嗗鐨勮
            if (row['rate_id']) {
                tRow.find('#tpl-code_name-read').unbind().remove();
                $('<input type="hidden" name="currentItems[]" />').val(row['rate_id']).appendTo($('#objectForm'));
                if (row['code_simulated']) {
                    tRow.find('#tpl-code_name-read').addClass('s-none');
                }
            } else {
                tRow.find('#tpl-code_name-read').unbind().remove();
            }
            tRow.find('input,select').each(function () {
                var el = $(this);
                var field = el.attr('name');
                el.attr({id: prefixId+'-'+field, name: prefixName+'['+field+']'}).val(row[field]);
            });
            tRow.find('#tpl-code_name-text').text(row['code_name'] ? row['code_name'] : '');
            buildParams(tRow);
            if (row['rate_id']) {
                tRow.appendTo(eRows);
            } else {
                tRow.prependTo(eRows);
            }
            if (!row['rate_id']) {
                initForms(tRow);
                initList();
            }
            if(!rows){
                tRow.find('input.selected').hide();
            }else{
                tRow.find('input.selected').val(rows.rate_id);
            }
            return tRow;
        }

        function buildParams(row)
        {
            var s = '';
            s = row.find('input[name*=min_time]').val() + ' / '
                + row.find('input[name*=interval]').val() + ' / '
                + row.find('input[name*=grace_time]').val() + ' / '
                + profiles[row.find('select[name*=time_profile_id]').val()];

            row.find('#tpl-params-text').html(s);
            if ($(row).find('input[name*=notes]').val() == '') {
                row.find('#tpl-params-block').find('b').hide();
            } else {
                row.find('#tpl-params-block').find('b').show();
            }
            return s;
        }
        function hideParams()
        {
            $('.tempcls').remove();
            $('#rows div.params-block:visible').hide().attr('id', '').each(function () {
                buildParams($(this).parent().parent());
            });
        }
        function findCode(rowId, type)
        {
            var _ss_ids = {};
            if (type != 'code_name') {
                _ss_ids['code'] = rowId+'-code';
            }
            _ss_ids['code_name'] = rowId+'-code_name';
            _ss_ids['country']=rowId+'-country';
            ss_code(1, _ss_ids,undefined,"<?php echo array_keys_value($mydata,'0.0.code_deck_id')?>");
        }
        function findCodeName(rowId,type,tr){
            var _ss_ids = {};
            CodeNameTr=jQuery(tr);
            _ss_ids['code'] = rowId+'-code';
            _ss_ids['code_name'] = rowId+'-code_name';
            _ss_ids['country']=rowId+'-country';
            ss_codename_all(1, _ss_ids,undefined,"<?php echo array_keys_value($mydata,'0.0.code_deck_id')?>");
        }
        function findCountry(rowId,type,tr){
            var _ss_ids = {};
            CodeNameTr=jQuery(tr);
            _ss_ids['code'] = rowId+'-code';
            _ss_ids['code_name'] = rowId+'-code_name';
            _ss_ids['country']=rowId+'-country';
            ss_country_all(1, _ss_ids,undefined,"<?php echo array_keys_value($mydata,'0.0.code_deck_id')?>");
        }

        // live event handlers
        $('#rows #tpl-params-block div').live('click', function (e) {
            e.stopPropagation();
        });
        $('#rows #tpl-params-block div a').live('click', function () {
            hideParams();
            return false;
        });

        $('#rows .tpl-params-link').live('click', function () {
            var vis = 0;
            var div = $(this).parent().find('div');
            if (div.attr('isvisible') == 'yes') vis = 1;
            hideParams();
            if (!vis) {
                div.attr({'id':'tooltip','height':'300px', 'isvisible': 'yes'});
                var $newdiv = div.clone();
                $newdiv.show();
                var $td = $('<td colspan="11" height="55px"></td>').append($newdiv);
                var $tr = $('<tr class="tempcls"></tr>').append($td);
                $(this).parent().parent().after($tr);
            } else {
                $(this).parent().find('div').attr('isvisible', 'no');
            }
            return false;
        });

        $('#rows #tpl-code-search').live('click', function () {
            findCode($(this).closest('tr').attr('id'), 'code');
        });
        $('#rows #tpl-code_name-search').live('click', function () {
            findCodeName($(this).closest('tr').attr('id'), 'code',$(this).closest('tr'));
        });

        $('#rows #tpl-country-search').live('click', function () {
            findCountry($(this).closest('tr').attr('id'), 'code',$(this).closest('tr'));
        });
        //
        $('#rows').find('input[rel*=format_number]').live('keyup',function(){
            jQuery(this).xkeyvalidate({type:'Ip'});
            //filter_chars(this);
        });

        $('#rows #tpl-delete-row').live('click', function () {
            if(confirm(" Are you sure to delete rates "+$(this).closest('tr').find('input[name*=code]').val())){
                var del_rate_id=$(this).closest('tr').find('input[name*=rate_id]').val();



                if(del_rate_id!=null&& del_rate_id!=''){
                    var del_val=$('#delete_rate_id').val()+","+del_rate_id;
                    $('#delete_rate_id').val(del_val);
                    $.ajax({
                        url:"<?php echo $this->webroot?>clientrates/ajax_delete_rate.json",
                        data:{rate_id:del_rate_id},
                        type:'POST',
                        async:false,
                        success:function(text){
                            if(text=='1'){
                                showMessages("[{'field':'#ingrLimit','code':'201','msg':'this  rate  delete   success'}]");
                            }
                        },
                        error:function(XmlHttpRequest){showMessages("[{'field':'#ingrLimit','code':'101','msg':'"+XmlHttpRequest.responseText+"'}]");}
                    });

                }


                $(this).closest('tr').remove();
                //璁板綍鍒犻櫎鐨刬d
            }
            return false;
        });
        //$(window).click(hideParams);
        // fill itesm
            <?php
        foreach ($mydata  as  $key =>$value){
            $time_profile_id= !empty($value[0]['time_profile_id'])?$value[0]['time_profile_id']:'';
            $rate_id=!empty($value[0]['rate_id'])?$value[0]['rate_id']:'';
            $rate_table_id=!empty($value[0]['rate_table_id'])?$value[0]['rate_table_id']:'';
            $code=!empty($value[0]['code'])?$value[0]['code']:'';
            $rate=!empty($value[0]['rate'])?$value[0]['rate']:'';
            $setup_fee=!empty($value[0]['setup_fee'])?$value[0]['setup_fee']:'0.000000';
            $effective_date=!empty($value[0]['effective_date'])?$appCommon->del_date_timezone($value[0]['effective_date']):'';
            $effective_date_timezone='+00';
            $end_date=!empty($value[0]['end_date'])?$appCommon->del_date_timezone($value[0]['end_date']):'';
            $end_date_timezone='+00';
            $min_time=!empty($value[0]['min_time'])?$value[0]['min_time']:'0';
            $grace_time=!empty($value[0]['grace_time'])?$value[0]['grace_time']:'0';
            $interval=!empty($value[0]['interval'])?$value[0]['interval']:'0';
            $seconds=!empty($value[0]['seconds'])?$value[0]['seconds']:'0';
            $code_name=!empty($value[0]['code_name'])?$value[0]['code_name']:'';
            $intra_rate=!empty($value[0]['intra_rate'])?$value[0]['intra_rate']:"''";
            $inter_rate=!empty($value[0]['inter_rate'])?$value[0]['inter_rate']:"''";
            $local_rate=!empty($value[0]['local_rate'])?$value[0]['local_rate']:"''";
            $country=!empty($value[0]['country'])?$value[0]['country']:'';
            $zone=$value[0]['zone'];
            $ocn = $value[0]['ocn'];
            $lata = $value[0]['lata'];
            echo "addItem({\"rate_id\":\"$rate_id\",\"rate_table_id\":\"$rate_table_id\",\"time_profile_id\":\"$time_profile_id\"	,\"code\":\"$code\",\"effective_date\":\"$effective_date\", \"effective_date_timezone\" : \"$effective_date_timezone\" ,\"end_date\":\"$end_date\",\"end_date_timezone\" : \"$end_date_timezone\",\"rate\":\"$rate\",\"setup_fee\":\"$setup_fee\",\"interval\":\"$interval\",\"min_time\":\"$min_time\",\"grace_time\":\"$grace_time\",\"seconds\":$seconds,\"code_name\":\"$code_name\",	\"intra_rate\":$intra_rate,\"inter_rate\":$inter_rate,\"local_rate\":$local_rate,country:\"$country\",zone:'$zone', ocn:'$ocn', lata:'$lata'}, 1);\n";

        }
        if(empty($mydata)){echo "$('#toppage').remove();$('#tmppage').remove();";}
            ?>
            eRows.hide();
        eRows.show();
        eTpl.hide();
        jQuery(document).ready(function(){eTpl.remove()});
    </script>
    <script type="text/javascript">
        function aa (){
            if(jQuery('#search-state_eq').attr('value')=='all'){
                /* jQuery('#search-now-wDt').attr('style','display:none');
                     jQuery('#search-now2-wDt').attr('style','display:none'); */
                jQuery('#timenow').attr('style','display:none');
            }else{
                /*jQuery('#search-now-wDt').attr('style','display:inlike');
                     jQuery('#search-now2-wDt').attr('style','display:inlike');	*/
                jQuery('#timenow').attr('style','display:inlike').css('text-align','right');
            }
        }

        jQuery('#action_preview').click(function(){
            var ret=true;
            ret=mass_fun();
            return ret;
        });

        jQuery('#search-state_eq').change(function(){
            aa();
        });

        jQuery(document).ready(function(){
            $('#ratetable_info_btn').click(function() {
                $('#ratetable_info').toggle();
            });

            $('.set_to_null').click(function() {
                $(this).prev().val('');
            });

            aa();
            jQuery('#advsearch').attr("style","display:none");
            jQuery('#rate_per_min_value,#pay_setup_value,#intra_rate_value').xkeyvalidate({type:'Ip'});
            jQuery('#min_time_value,#pay_interval_value,#grace_time_value,#inter_rate_value').xkeyvalidate({type:'Num'})
        });


        $(function() {
            $('.delete_selected').click(function() {
                var delete_ids = new Array();
                $('#list tbody input:checkbox:checked').each(function(index, item) {
                    delete_ids.push($(this).val());
                });
                deleteSelected('list','<?php echo $this->webroot; ?>clientrates/mass_delete/<?php echo $this->params['pass'][0]?>/' + delete_ids);
            });
            $('.country').live('click', function(){
                $(this).autocomplete(countries)
            });

            $('.code_name').live('click', function(){
                $(this).autocomplete(cities)
            });

            $('#is_down').val('0');

            $('#actionForm').submit(function() {
                $('#is_down').value('1');
                $('#advsearch_form').submit();
                return false;
            });
        });

    </script>
</div>
    </div>
</div>