<script src="<?php echo $this->webroot ?>js/BubbleSort.js" type="text/javascript"></script>
<script>
    var webroot = "<?php echo $this->webroot ?>";//全局路径
    function getWeek(day) {
        var week = {
            1: "<?php echo __('Monday') ?>",
            2: "<?php echo __('Tuesday') ?>",
            3: "<?php echo __('Wednesday') ?>",
            4: "<?php echo __('Thursday') ?>",
            5: "<?php echo __('Friday') ?>",
            6: "<?php echo __('Saturday') ?>",
            7: "<?php echo __('Sunday') ?>"
        };
        return week[day];
    }
</script>
<?php $w = $session->read('writable'); ?>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>timeprofiles/profile_list">
        <?php __('Switch') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>timeprofiles/profile_list">
        <?php __('Time Profile') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Time Profile') ?> </h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php if ($_SESSION['role_menu']['Switch']['timeprofiles']['model_w'])
    { ?>  
        <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="###"><i></i> <?php __('Create New') ?></a>
    <?php } ?>
    <?php if (isset($edit_return))
    { ?>
        <a class="link_back btn btn-default btn-icon glyphicons left_arrow" href="<?php echo $this->webroot ?>timeprofiles/profile_list">><i></i> <?php echo __('Back', true); ?></a>
<?php } ?>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label> <?php __('Search') ?>:</label>
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
            <?php $d = $p->getDataArray();
            if (count($d) == 0)
            {
                ?>
                <br /><h2 class="msg center"><?php echo __('no_data_found') ?></h2>
            <?php } ?>


            <div     id="no_data" style="<?php
            if (count($d) == 0)
            {
                echo 'display:none';
            }
            ?>"  >   
                <div class="clearfix"></div>
                <div>
                    <table id="mainTable" class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

                        <thead>
                            <tr>

<!--     <td>
                                    <?php echo $appCommon->show_order('time_profile_id', 'ID'); ?>
</td>-->
                                <th><?php echo $appCommon->show_order('name', __('timeprofilename', true)) ?></th>
                                <th><?php echo $appCommon->show_order('type', __('type', true)) ?></th>
                                <th><?php echo $appCommon->show_order('start_week', __('startweek', true)) ?></th>
                                <th><?php echo $appCommon->show_order('end_week', __('endweek', true)) ?></th>
                                <th><?php echo $appCommon->show_order('start_time', __('starttime', true)) ?></th>
                                <th>
                                    <?php echo $appCommon->show_order('end_time', __('endtime', true)) ?>
                                </th>
                                <!--th>
                                    <?php echo __('GMT') ?>
                                </th-->

                                <?php if ($_SESSION['role_menu']['Switch']['timeprofiles']['model_w']) { ?>
                                    <th><?php echo __('action') ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody id="producttab">
                                    <?php
                                    $mydata = $p->getDataArray();
                                    $loop = count($mydata);
                                    for ($i = 0; $i < $loop; $i++)
                                    {
                                        ?>
                                <tr class="row-1">
            <!--		    <td class="in-decimal"  style="text-align: center;"><?php echo $mydata[$i][0]['time_profile_id'] ?></td>-->
                                    <td style="font-weight: bold;">
                                        <?php if ($_SESSION['role_menu']['Switch']['timeprofiles']['model_w'])
                                        { ?> 
                                            <a class="edit link_width " title="<?php echo __('edit') ?>" time_profile_id='<?php echo $mydata[$i][0]['time_profile_id'] ?>'  href="<?php echo $this->webroot ?>timeprofiles/edit_profile/<?php echo $mydata[$i][0]['time_profile_id'] ?>">
                                            <?php echo $mydata[$i][0]['name'] ?>
                                            </a> 
                                        <?php }
                                        else
                                        { ?>
                                            <?php echo $mydata[$i][0]['name'] ?>
    <?php } ?>
                                    </td>

                                    <td style="font-weight: bold;">
                                    <?php
                                    if ($mydata[$i][0]['type'] == 0)
                                        echo __('alltime');
                                    else if ($mydata[$i][0]['type'] == 1)
                                        echo __('weekly');
                                    else
                                        echo __('daily');
                                    ?>
                                    </td>
                                    <td><script>if (getWeek(<?php echo $mydata[$i][0]['start_week'] ?>))
                                        document.write(getWeek(<?php echo $mydata[$i][0]['start_week'] ?>));</script></td>
                                    <td align="center"><script>if (getWeek(<?php echo $mydata[$i][0]['end_week'] ?>))
                                        document.write(getWeek(<?php echo $mydata[$i][0]['end_week'] ?>));</script></td>

                                    <td><?php echo $mydata[$i][0]['start_time'] ? $mydata[$i][0]['start_time'].' GMT' : "" ?> </td>
                                    <td align="center"><?php echo $mydata[$i][0]['start_time'] ? $mydata[$i][0]['end_time'].' GMT' : "" ?></td>
                                    <!--td>

                                        <?php
                                        if ($mydata[$i][0]['type'] != 0)
                                        {
                                            if (!empty($mydata[$i][0]['time_zone']))
                                            {
                                                echo "GMT " . $mydata[$i][0]['time_zone'];
                                            }
                                            else
                                            {
                                                echo "GMT +0000";
                                            }
                                        }
                                        ?>
                                    </td-->
    <?php if ($_SESSION['role_menu']['Switch']['timeprofiles']['model_w'])
    { ?> 
                                        <td align="center;">
        <?php if ($w == true)
        { ?>
                                                <a class="edit" title="<?php echo __('edit') ?>" time_profile_id='<?php echo $mydata[$i][0]['time_profile_id'] ?>'  href="<?php echo $this->webroot ?>timeprofiles/edit_profile/<?php echo $mydata[$i][0]['time_profile_id'] ?>">
                                                    <i class="icon-edit"></i>
                                                </a>
                                                <a title="<?php echo __('del') ?>" href="javascript:void(0)" onclick="ex_delConfirm(this, '<?php echo $this->webroot ?>timeprofiles/delbyid/<?php echo $mydata[$i][0]['time_profile_id'] ?>', 'time profile <?php echo $mydata[$i][0]['name'] ?>');">
                                                    <i class="icon-remove"></i>
                                                </a><?php } ?>
                                        </td>
    <?php } ?>
                                </tr>
<?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
<?php echo $this->element('page'); ?>
                    </div> 
                </div>
                <div class="clearfix"></div>

            </div>


        </div>
        <div>
        </div>
    </div>
</div>
<script type="text/javascript">

    jQuery('#add').click(function() {

        jQuery('#no_data').show();
        jQuery('.msg').hide();
        jQuery('#mainTable').trAdd({
            ajax: '<?php echo $this->webroot ?>timeprofiles/js_save',
            action: '<?php echo $this->webroot ?>timeprofiles/save',
            insertNumber: 'first',
            removeCallback: function() {
                if (jQuery('table.list tr').size() == 1) {
                    jQuery('#no_data').hide();
                    jQuery('.msg').show();
                }
            },
            callback: function(options) {
                typechange(jQuery('#TimeprofileType'));
            },
            onsubmit: trAddSubmit
        });
        return false;
    });
    jQuery('.edit').click(function() {
        var id = jQuery(this).attr('time_profile_id');
        jQuery(this).parent().parent().trAdd({
            ajax: '<?php echo $this->webroot ?>timeprofiles/js_save/' + id,
            action: '<?php echo $this->webroot ?>timeprofiles/save/' + id,
            callback: function(options) {
                typechange(jQuery('#TimeprofileType'));
            },
            saveType: 'edit',
            onsubmit: trEditSubmit
        });
        return false;
    });
    function trAddSubmit(options) {
        var obj = jQuery('#' + options.log);
        if (obj.find('#TimeprofileName').val() == '') {
            jQuery.jGrowlError('The field name cannot be NULL.');
            return false;
        } else {
            if (!/^(\w|\-|\s)*$/.test(obj.find('#TimeprofileName').val())) {
                jQuery.jGrowlError('Name, allowed characters: a-z,A-Z,0-9,-,_,space, maximum of 100 characters!');
                return false;
            }
        }
        var id = options.time_profile_id;
        if (!id) {
            id = -1;
        }
        var name = obj.find('#TimeprofileName').val();
        var data = jQuery.ajaxData("<?php echo $this->webroot ?>timeprofiles/check_name/" + id + "?name=" + name);
        if (data.indexOf('false') != -1) {
            jQuery.jGrowlError(name + ' is already in use! ');
            return false;
        }

        if (obj.find("#TimeprofileType").val() == 2)
        {
            if (obj.find("#TimeprofileStartTime").val() == '')
            {
                jQuery.jGrowlError('The field Start Time cannot be NULL.');
                return false;
            }

            var start_time = obj.find("#TimeprofileStartTime").val();
            var end_time = obj.find("#TimeprofileEndTime").val();

            var cmp = start_time.localeCompare(end_time);
            if (cmp == 1)
            {
                jQuery.jGrowlError('End Time must be greater than Start Time');
                return false;
            }
        }
        if (obj.find("#TimeprofileType").val() == 1)
        {
            if (obj.find("#TimeprofileStartWeek").val() == '' || obj.find("#TimeprofileEndWeek").val() == '')
            {
                jQuery.jGrowlError('Start Week && End Week, cannot be null!');
                return false;
            }

            var start_week = obj.find("#TimeprofileStartWeek").val();
            var end_week = obj.find("#TimeprofileEndWeek").val();



            /*if(end_week == 7)
                end_week = '0';

            if(start_week == 7)
                start_week = '0';*/

            if(end_week < start_week){
                jQuery.jGrowlError('End Week must be later than Start Week!');
                return false;
            }

            var start_time = obj.find("#TimeprofileStartTime").val();
            var end_time = obj.find("#TimeprofileEndTime").val();

            var cmp = start_time.localeCompare(end_time);
            if(cmp == 1) {

                jQuery.jGrowlError('End Time must be greater than start time');
                return false;
            }
        }
        return true;
    }

    function trEditSubmit(options) {
        var obj = jQuery('#' + options.log);
        if (obj.find('#TimeprofileName').val() == '') {
            jQuery.jGrowlError('The field Name cannot be NULL.');
            return false;
        }
        var id = options.time_profile_id;
        if (!id) {
            id = -1;
        }
        //	var name=obj.find('#TimeprofileName').val();
        //		var data=jQuery.ajaxData("<?php echo $this->webroot ?>timeprofiles/check_name/"+id+"?name="+name);
        //		if(data.indexOf('false')!=-1){
        //			jQuery.jGrowlError(name+' is already in use! ');
        //			return false;
        //		}


        if (obj.find("#TimeprofileType").val() == 2)
        {
            if (obj.find("#TimeprofileStartTime").val() == '')
            {
                jQuery.jGrowlError('Start Time is required');
                return false;
            }

            var start_time = obj.find("#TimeprofileStartTime").val();
            var end_time = obj.find("#TimeprofileEndTime").val();

            var cmp = start_time.localeCompare(end_time);
            if (cmp == 1)
            {
                jQuery.jGrowlError('End Time must be greater than Start Time');
                return false;
            }
        }
        if (obj.find("#TimeprofileType").val() == 1)
        {
            if (obj.find("#TimeprofileStartWeek").val() == '' || obj.find("#TimeprofileEndWeek").val() == '')
            {
                jQuery.jGrowlError('Start Week && End Week, cannot be null!');
                return false;
            }

            var start_week = obj.find("#TimeprofileStartWeek").val();
            var end_week = obj.find("#TimeprofileEndWeek").val();



//            if(end_week == 7)
//                end_week = '0';

//            if(start_week == 7)
//                start_week = '0';

            if(end_week < start_week){
                jQuery.jGrowlError('End Week must be later than Start Week!');
                return false;
            } else if(end_week == start_week) {
                var start_time = obj.find("#TimeprofileStartTime").val();
                var end_time = obj.find("#TimeprofileEndTime").val();

                var cmp = start_time.localeCompare(end_time);
                if (cmp == 1)
                {
                    jQuery.jGrowlError('End Time must be greater than start time');
                    return false;
                }
            }


        }
        return true;
    }

    $(function() {
<?php if (!count($d)): ?>
            $("#add").click();
<?php endif; ?>

    });

    $(document).on('DOMNodeInserted', function(){
        $('#delete').attr('title', 'Delete');
    });
</script>
