<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Editing Rates') ?> <font class="editname"> <?php echo empty($name[0][0]['name']) || $name[0][0]['name'] == '' ? '' : '[' . $name[0][0]['name'] . ']' ?> </font></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">
        <?php echo __('Editing Rates') ?> <font class="editname"> <?php echo empty($name[0][0]['name']) || $name[0][0]['name'] == '' ? '' : '[' . $name[0][0]['name'] . ']' ?> </font>
    </h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a href="javascript:void(0)" class="add btn btn-primary btn-icon glyphicons circle_plus">
        <i></i><?php echo __('Create New',true);?>
    </a>
    <?php if (@$_GET['filter_effect_date'] == 'all'): ?>
        <a  onclick="show_current_rate();" class="link_btn btn btn-primary btn-icon glyphicons fire" href="javascript:void(0);"><i></i> <?php __('Show  Current')?> </a> 
    <?php else: ?>
        <a onclick="show_all_rate();" class="link_btn btn btn-primary btn-icon glyphicons list" href="javascript:void(0);"><i></i> <?php __('Show  All')?> </a>
    <?php endif; ?>

    <?php
    if ($_SESSION['role_menu']['Switch']['clientrates']['model_w'])
    {
        ?>
<!--        <a class="link_btn btn btn-primary btn-icon glyphicons remove" onClick="return myconfirm('Are you sure to remove all?', this)" href="<?php echo $this->webroot ?>clientrates/mass_delete/<?php echo $this->params['pass'][0]; ?><?php
        if (isset($search_flg))
        {
            echo '/0/' . $search_q . '/' . $_GET['effectiveDate'];
        }
        ?>" ><i></i> Delete All </a> 
        <a class="link_btn delete_selected btn btn-primary btn-icon glyphicons remove" rel="popup" href="###"><i></i> <?php echo __('Delete Selected') ?></a>-->
    <?php } ?>
    <a class="link_back btn btn-icon glyphicons btn-inverse circle_arrow_left" href="<?php echo $this->webroot ?>rates/rates_list"><i></i> <?php echo __('Back', true); ?></a>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="clearfix"></div>
        <div class="widget-head">
            <ul class="tabs">
                <li class="active"><a  class="glyphicons justify" href="<?php echo $this->webroot ?>clientrates/view/<?php echo $table_id ?>/<?php echo $currency ?>"><i></i><?php echo __('Rates', true); ?> </a></li>
                <?php if ($jur_type == 3 || $jur_type == 4): ?>
                    <li><a   class="glyphicons notes_2" href="<?php echo $this->webroot ?>clientrates/view/<?php echo $table_id ?>/<?php echo $currency ?>/npan"><i></i><?php echo __('NPANXX Rate', true); ?> </a></li>
                <?php endif; ?>
                <li><a class="glyphicons nails" href="<?php echo $this->webroot ?>clientrates/simulate/<?php echo $table_id ?>"><i></i> <?php echo __('Simulate', true); ?></a></li>
                <?php
                if ($_SESSION['role_menu']['Switch']['clientrates']['model_x'])
                {
                    ?>
                    <li><a href="<?php echo $this->webroot ?>clientrates/import/<?php echo $table_id ?>"  class="glyphicons upload"><i></i> <?php echo __('Import', true); ?></a></li>
                    <li><a href="<?php echo $this->webroot ?>downloads/rate/<?php echo $table_id ?>"  class="glyphicons download"><i></i> <?php echo __('Export', true); ?></a></li>
                <?php } ?>
            </ul>
        </div>
        <div class="widget-body">
            <div class="filter-bar">

                <form action="" method="get" id="likesearch"  >
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search')?>:</label>
                        <input type="text" id="search-_q_rate"
                               value="<?php
                               if (!empty($_GET['search']['_q']))
                               {
                                   echo $_GET['search']['_q'];
                               }
                               else
                               {
                                   echo '';
                               }
                               ?>"
                               class=""  name="search[_q]" />
                    </div>
                    <!-- // Filter END -->
                    <div>
                        <label><?php __('Effective Date')?> :</label>
                        <input type="text" id="search-_q_rate" 
                               value="<?php
                               if (!empty($_GET['effectiveDate']))
                               {
                                   echo $_GET['effectiveDate'];
                               }
                               else
                               {
                                   echo '';
                               }
                               ?>"
                               onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" name="effectiveDate" />
                        <input type="hidden" value="1" name="search_flg" />
                    </div>
                    <!-- Filter -->
                    <!-- Filter -->
                    <div>
                        <label><?php __('Group')?>:</label>
                        <select name="rate_group" >
                            <option value="1"><?php __('by Code')?></option>
                            <option value="2"   selected='selected'><?php __('by Code Name')?></option>
                        </select>
                    </div>
                    <!-- // Filter END -->
                    <div>
                        <button id='search_query' name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->

                </form>
            </div>
            <?php
            $data = $p->getDataArray();
            ?>
            <?php
            if (count($data) == 0)
            {
                ?>
                <br />
                <h2 class="msg center"><?php echo __('no_data_found') ?></h2>
                <table class="list" style="display:none;">
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('code_name', __('Code Name', true)) ?></th>
                            <th><?php echo __('Rate', true); ?></th>
                            <th><?php echo __('Country', true); ?></th>
                            <th><?php echo $appCommon->show_order('effective_date', __('Effective Date', true)) ?></th>
                            <th><?php __('Action')?></th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
                <?php
            }
            else
            {
                ?>
                <div class="clearfix"></div>
                <fieldset>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                            <tr>
                                <th><?php echo $appCommon->show_order('code_name', __('Code Name', true)) ?></th>
                                <th><?php echo __('Rate', true); ?></th>
                                <th><?php echo __('Country', true); ?></th>
                                <th><?php echo $appCommon->show_order('effective_date', __('Effective Date', true)) ?></th>
                                <th><?php __('Action')?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($data as $item)
                            {
                                ?>
                                <tr>
                                    <td><?php echo $item[0]['code_name']; ?></td>
                                    <td><?php echo $item[0]['rate']; ?></td>
                                     <td><?php echo $item[0]['country']; ?></td>
                                    <?php
//                                    $effective_date = "";
//                                    $effective_date_timezone = "";
//                                    if (!empty($item[0]['effective_date']))
//                                    {
//                                        $effective_date = $appCommon->del_date_timezone($item[0]['effective_date']);
//                                        $effective_date_timezone = $appCommon->get_date_timezone($item[0]['effective_date']);
//                                    }
//                                    
                                    ?>
                                    <td>
                                        <?php echo $item[0]['effective_date']; ?>
                                    </td>
                                    <td>
                                        <a title="" class="edit"  style="float:left;margin-left:5px;" href="javascript:void(0)"> 
                                            <i class="icon-edit"></i>  
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('page'); ?>
                        </div> 
                    </div>
                </fieldset>
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">


    function  show_all_rate() {
        $('#likesearch').append('<input name="filter_effect_date" type="hidden" value="all" />');
        $("#search_query").click();
    }

    /*
     *显示当前rate
     */
    function show_current_rate() {
        $('#likesearch').append('<input name="filter_effect_date" type="hidden" value="" />');
        $("#search_query").click();
    }
<?php $action_url = empty($massEdit) ? $massEdit . "/" . $npa : ""; ?>
    $(function() {
        $('a[class=edit]').click(function() {
            var code_name = encodeURI($(this).parent().parent().children().eq(0).html());
            var rate = encodeURI($(this).parent().parent().children().eq(1).html());
            var effective_date = $(this).parent().parent().children().eq(3).html().trim();
            $(this).parent().parent().trAdd({
            action: "<?php echo $this->webroot ?>clientrates/save_code_name_rate/<?php echo $table_id; ?>/<?php echo $action_url; ?>",
                    ajax: "<?php echo $this->webroot ?>clientrates/ajax_code_name_rate",
                    ajaxData: "code_name=" + code_name + "&rate=" + rate + "&date=" + effective_date,
                    saveType: 'edit',
                    onsubmit: function() {
                    var input_code_name = $("#code_name").val();
                    if (/[^0-9A-Za-z-\_\.\s]+/.test(input_code_name) || input_code_name.length > 50) {
                        jQuery.jGrowlError(' Code Name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum  of 50 characters in length! ');
                        return false;
                    }
                    return true;
                }
            });
        });
        $('a.add').click(function() {
            jQuery('table.list tbody').trAdd({
                action: "<?php echo $this->webroot ?>clientrates/create_code_name_rate/<?php echo $table_id; ?>",
                    ajax: "<?php echo $this->webroot ?>clientrates/ajax_code_name_rate",
                    saveType: 'add',
                    insertNumber: 'first',
                    onsubmit: function() {
                       let input_code_name = $("#trAdd #code_name").val();
                       let rate = $("#trAdd #rate").val();
                       let effective_date = $("#trAdd input[name='effective_date']").val();
                       if (!input_code_name)
                       {
                           jGrowl_to_notyfy("Code Name can not be empty!.", {theme: 'jmsg-error'});
                           return false;
                       }
                       if (!rate)
                       {
                           jGrowl_to_notyfy("Rate can not be empty!.", {theme: 'jmsg-error'});
                           return false;
                       }
                       if (!effective_date)
                       {
                           jGrowl_to_notyfy("Effective Date can not be empty!.", {theme: 'jmsg-error'});
                           return false;
                       }
                       if (/[^0-9A-Za-z-\_\.\s]+/.test(input_code_name) || input_code_name.length > 50) {
                            jQuery.jGrowlError(' Code Name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum  of 50 characters in length! ');
                            return false;
                        }
                        return true;
                    }
            });
        });
    });
</script>


