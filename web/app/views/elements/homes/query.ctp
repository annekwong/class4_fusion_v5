<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;margin-left: 15px">
    <h4 class="heading glyphicons search"><i></i> <?php __('Search')?></h4>
    <div style="margin:0px auto; text-align:center;">
        <form action="<?php echo $this->webroot ?>homes/show_report" method="post">

            <div style="text-align: left">
                <span style="display:inline-block;width: 100px"><?php __('Report Type')?>:</span>
                <select name="report_type" style="width: 133px" value="<?php echo isset($_POST['report_type']) ? $_POST['report_type'] : ''?>">
                    <option value="0"><?php __('Origination')?></option>
                    <option value="1"><?php __('Termination')?></option>
                    <option value="2"><?php __('Destination')?></option>
                </select>
                <span style="display:inline-block;width: 100px"><?php __('Start Date/Time')?>:</span>
                <input type="text" name="start_time" style="width: 120px" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" value="<?php echo isset($_POST['start_time']) ? $_POST['start_time'] : $date. ' ' . "00:00:00"; ?>" />
                <span style="display:inline-block;width: 100px"><?php __('End Date/Time')?>:</span>
                <input type="text" name="end_time" style="width: 120px" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" value="<?php echo isset($_POST['end_time']) ? $_POST['end_time'] : $date. ' ' . "23:59:59"; ?>" />
                <span style="display:inline-block;width: 100px"><?php __('Timezone')?>:</span>
                <select name="timezone" style="width: 133px" value="<?php echo isset($_POST['timezone']) ? $_POST['timezone'] : "+0000"?>">
                    <option value="-1200">GMT -12:00</option>
                    <option value="-1100">GMT -11:00</option>
                    <option value="-1000">GMT -10:00</option>
                    <option value="-0900">GMT -09:00</option>
                    <option value="-0800">GMT -08:00</option>
                    <option value="-0700">GMT -07:00</option>
                    <option value="-0600">GMT -06:00</option>
                    <option value="-0500">GMT -05:00</option>
                    <option value="-0400">GMT -04:00</option>
                    <option value="-0300">GMT -03:00</option>
                    <option value="-0200">GMT -02:00</option>
                    <option value="-0100">GMT -01:00</option>
                    <option value="+0000">GMT +00:00</option>
                    <option value="+0100">GMT +01:00</option>
                    <option value="+0200">GMT +02:00</option>
                    <option value="+0300">GMT +03:00</option>
                    <option value="+0330">GMT +03:30</option>
                    <option value="+0400">GMT +04:00</option>
                    <option value="+0500">GMT +05:00</option>
                    <option value="+0600">GMT +06:00</option>
                    <option value="+0700">GMT +07:00</option>
                    <option value="+0800">GMT +08:00</option>
                    <option value="+0900">GMT +09:00</option>
                    <option value="+1000">GMT +10:00</option>
                    <option value="+1100">GMT +11:00</option>
                    <option value="+1200">GMT +12:00</option>
                </select>
            </div>
            <div  style="text-align: left">
                <span style="display:inline-block;width: 100px"><?php __('Country')?>:</span>
                <input type="text" name="country" style="width: 120px" value="<?php echo isset($_POST['country']) ? $_POST['country'] : ''?>" />
                <span style="display:inline-block;width: 100px"><?php __('Destination')?>:</span>
                <input type="text" name="destination" style="width: 120px" value="<?php echo isset($_POST['destination']) ? $_POST['destination'] : ''?>" />
                <span style="display:inline-block;width: 100px"><?php __('Orig Filter')?>:</span>
                <select name="ingress_trunk" style="width: 133px" value="<?php echo isset($_POST['ingress_trunk']) ? $_POST['ingress_trunk'] : ''?>">
                    <option></option>
                    <?php foreach($ingress_trunks as $trunks): ?>
                        <option value="<?php echo $trunks[0]['resource_id'] ?>" <?php echo isset($_POST['ingress_trunk']) && ($trunks[0]['resource_id'] ==  $_POST['ingress_trunk']) ? 'selected="selected"' : ''?>><?php echo $trunks[0]['alias'] ?></option>
                    <?php endforeach; ?>
                </select>
                <span style="display:inline-block;width: 100px"><?php __('Term Filter')?>:</span>
                <select name="egress_trunk" style="width: 133px" value="<?php echo isset($_POST['egress_trunk']) ? $_POST['egress_trunk'] : ''?>">
                    <option></option>
                    <?php foreach($egress_trunks as $trunks): ?>
                        <option value="<?php echo $trunks[0]['resource_id'] ?>" <?php echo isset($_POST['egress_trunk']) && ($trunks[0]['resource_id'] ==  $_POST['egress_trunk']) ? 'selected="selected"' : ''?>><?php echo $trunks[0]['alias'] ?></option>
                    <?php endforeach; ?>
                </select>
                &nbsp;&nbsp;&nbsp;
                <input type="submit" value="<?php __('Submit')?>" class="input in-submit btn btn-primary margin-bottom10">
            </div>

        </form>
    </div>
</fieldset>

<script>
    $(function(){
        var report_type = $('select[name="report_type"]');
        var v1 = '<?php echo isset($_POST['report_type']) ? $_POST['report_type'] : '0'?>';
        report_type.find('option[value="'+v1+'"]').prop('selected',true);

        var timezone = $('select[name="timezone"]');
        var v2 = '<?php echo isset($_POST['timezone']) ? $_POST['timezone'] : '+0000'?>';
        timezone.find('option[value="'+v2+'"]').prop('selected',true);
    })
</script>


