<style>
    select,input[type="text"]{margin-bottom: 0;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Editing Rates') ?> <font class="editname"> <?php echo empty($name[0][0]['name'])||$name[0][0]['name']==''?'':'['.$name[0][0]['name'].']' ?> </font></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Simulate') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Simulate') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a id="controlladd" class="btn btn-primary btn-icon glyphicons circle_plus" href="###"><i></i> <?php __('Create New')?></a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <?php echo $this->element('downloads/rate_tabs',array('action' => 'simulate')) ?>
        </div>
        <div class="widget-body"> 
    <form  id="from" style="margin: 15px 0pt 10px;" method="post" action="">
           
        <input type="hidden" id="id" value="148" name="id" class="input in-hidden">
        <input type="hidden" id="process" value="1" name="process" class="input in-hidden">
        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="controltable">
            <thead></thead>
            <tbody>
                <tr>

                    <td class="right"><?php echo __('Date', true); ?></td>
                    <td>
                        <input type="text" name="date[]" class="input-small" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" value="<?php echo date("Y-m-d") ?>" id="search-now-wDt">
                    </td>
                    <td class="right"><?php echo __('Time', true); ?></td>
                    <td>
                        <input type="text" name="time[]" class="input-small"  onfocus="WdatePicker({dateFmt:'HH:mm:ss'});" value="00:00:00" />
                    </td>
                    <td><select id="tz" name="tz[]" class="input in-select input-small"><option value="-1200">GMT -12:00</option><option value="-1100">GMT -11:00</option><option value="-1000">GMT -10:00</option><option value="-0900">GMT -09:00</option><option value="-0800">GMT -08:00</option><option value="-0700">GMT -07:00</option><option value="-0600">GMT -06:00</option><option value="-0500">GMT -05:00</option><option value="-0400">GMT -04:00</option><option value="-0300">GMT -03:00</option><option value="-0200">GMT -02:00</option><option value="-0100">GMT -01:00</option><option value="+0000">GMT +00:00</option><option value="+0100">GMT +01:00</option><option value="+0200">GMT +02:00</option><option value="+0300">GMT +03:00</option><option value="+0330">GMT +03:30</option><option value="+0400">GMT +04:00</option><option value="+0500">GMT +05:00</option><option value="+0600">GMT +06:00</option><option value="+0700">GMT +07:00</option><option value="+0800">GMT +08:00</option><option value="+0900">GMT +09:00</option><option value="+1000">GMT +10:00</option><option value="+1100">GMT +11:00</option><option value="+1200">GMT +12:00</option></select></td>
                    <!--<td><?php echo __('ANI', true); ?></td>
                    <td><input type="text" id="ani"  name="ani[]" class="input in-text input-small"></td>-->
                    <td class="right"><?php echo __('DNIS', true); ?></td>
                    <td><input type="text" id="dnis"   name="dnis[]" class="input in-text input-small"></td>
                    <td class="right"><?php echo __('Duration', true); ?></td>
                    <td><input type="text" id="duration" value="60" class="input-small" name="duration[]"> sec</td>
                    <td colspan="11"> <?php if ($_SESSION['role_menu']['Switch']['rates']['model_w']) { ?>
                        <input type="submit" value="<?php __('Process')?>" class="input in-submit btn btn-primary">
                    <?php } ?></td>
                </tr>
            </tbody>
        </table>

    </form>


    <?php if (isset($data)): ?>

        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
            <thead>
                <tr>
                    <th><?php echo __('Date', true); ?></th>
                    <!--<th><?php echo __('ANI', true); ?></th>-->
                    <th><?php echo __('DNIS', true); ?></th>
                    <th><?php echo __('Rate', true); ?></th>
                    <th><?php echo __('Cost', true); ?></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($data as $val): ?>
                    <tr>
                        <td><?php echo $val['date']; ?></td>
                        <!--<td><?php echo $val['ani']; ?></td>-->
                        <td><?php echo $val['dnis']; ?></td>
                        <td><?php echo $val['rate']; ?></td>
                        <td><?php echo $val['cost']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>    

    <?php endif; ?>


</div>
    </div>
</div>
<script type="text/javascript">
    <!--
    jQuery(document).ready(function(){
        jQuery('#from').submit(function(){

            var ret = true; 
           /* if(/\D/.test(jQuery('#ani').val())){
					   
                jQuery('#ani').addClass('invalid');
                jGrowl_to_notyfy('ANI, must be whole number!  ',{theme:'jmsg-error'});
                ret= false;			   
            }*/
      
            if(/\D/.test(jQuery('#dnis').val())){
					   
                jQuery('#dnis').addClass('invalid');
                jGrowl_to_notyfy('DNIS, must be whole number!  ',{theme:'jmsg-error'});
                ret= false;			   
            }

            if(/\D/.test(jQuery('#duration').val())){
					   
                jQuery('#duration').addClass('invalid');
                jGrowl_to_notyfy('Duration, must be whole number!  ',{theme:'jmsg-error'});
                ret= false;			   
            }

            return ret;
        });
    });

    //-->

    $(function() {
        $('#controlladd').click(function() {
            var $tr = $('#controltable tbody tr:last').clone(true);
            $tr.find('#tz option[value="<?php echo $dzone; ?>"]').attr('selected','selected');
            $tr.appendTo('#controltable tbody');
        });    
    
        $('#tz option[value="<?php echo $dzone; ?>"]').attr('selected','selected');
    });
</script>





