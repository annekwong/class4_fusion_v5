<style>
    input[type="text"],select{margin:0;}
    .widget-body .dynamicTable tableTools table-bordered {border-top: 1px solid #ebebeb}
    .table th, .table td.right{line-height: 30px;}
</style>
<script type="text/javascript">
    function save_device() {

        var host = $("#ingress_ip").val();
        var dnis = $("#dnis").val();
        var ani = $("#ani").val();
        var ivr_path = $("#ivr_path").val();
        var codecs = $("#codecs").val();
        var itv = $("#interval").val();
        var duration = $("#duration").val();

        var flag = true;
        if (host == '' || host == null) {
            $("#host").attr('class', 'invalid');
            jGrowl_to_notyfy('Ingress Ip is  null', {theme: 'jmsg-alert'});
            flag = false;
        }
        else
        {
            $("#host").attr('class', 'input width220');
        }

        if (ani == '' || ani == null) {
            $("#ani").attr('class', 'invalid');
            jGrowl_to_notyfy('ani is  null', {theme: 'jmsg-alert'});
            flag = false;
        }
        else
        {
            $("#ani").attr('class', 'input width220');
        }


        if (dnis == '' || dnis == null) {
            $("#dnis").attr('class', 'invalid');
            jGrowl_to_notyfy('dnis is  null', {theme: 'jmsg-alert'});
            flag = false;
        }
        else
        {
            $("#dnis").attr('class', 'input width220');
        }




        if (itv == '' || itv == null) {
            $("#interval").attr('class', 'invalid');
            jGrowl_to_notyfy('Interval is  null', {theme: 'jmsg-alert'});
            flag = false;
        }
        else
        {
            $("#interval").attr('class', 'input width220');
        }


        if (duration == '' || duration == null) {
            $("#duration").attr('class', 'invalid');
            jGrowl_to_notyfy('duration is  null', {theme: 'jmsg-alert'});
            flag = false;
        }
        else
        {
            $("#duration").attr('class', 'input width220');
        }

        if (flag == true) {
            $("#result").html('command sended,please wait......');
            jQuery.post("<?php echo $this->webroot ?>testdevices/save_device", {host: host, ani: ani, dnis: dnis, ivr_path: ivr_path, codecs: codecs, itv: itv, duration: duration}, function(d) {
                //jGrowl_to_notyfy(d,{theme:'jmsg-alert'});
                $("#result").html(d);
            });
        } else {

            return;
        }


    }
</script>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Ingress Trunk Simulation') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Ingress Trunk Simulation') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">


        <div class="widget-body">
            <table class="form footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <tbody>
                    <col width="40%">
                    <col width="60%">
                    <tr>
                        <td class="right"><?php echo __('ingress', true); ?></td>
                        <td>
                            <select id="res" name="res" class="input in-select" onchange="getIngress('<?php echo $this->webroot ?>', this);">
                                <?php
                                $loop = count($ingress);
                                for ($i = 0; $i < $loop; $i++) {
                                    ?>
                                    <option value="<?php echo $ingress[$i][0]['resource_id'] ?>"><?php echo $ingress[$i][0]['name'] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>



                    <tr>
                        <td class="right"><?php echo __('Ingress IP', true); ?></td>
                        <td>

                            <select id="host" name="host" onchange="changeIngress(this);" class="input in-select">

                                <?php
                                $loop = count($host);
                                for ($i = 0; $i < $loop; $i++) {
                                    ?>
                                    <option value="<?php echo $host[$i][0]['ip'] ?>"><?php echo $host[$i][0]['ip'] ?>:<?php echo $host[$i][0]['port'] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>


                    <tr>
                        <td></td>
                        <td><input type="hidden" id="ingress_ip" value="" name="ingress_ip" class="input width220"></td>
                    </tr>

                    <tr>
                        <td class="right"><?php echo __('ani', true); ?></td>
                        <td><input type="text" id="ani" value="" name="ani" class="input width220"></td>
                    </tr>
                    <tr>
                        <td class="right"><?php echo __('dnis', true); ?></td>
                        <td>
                            <input type="text" id="dnis" value="" name="dnis" class="input width220">
                        </td>
                    </tr>


                    <tr>
                        <td class="right"><?php echo __('Duration', true); ?></td>
                        <td>
                            <input type="text" id="duration" value="" name="duration" class="input width220">
                        </td>
                    </tr>



                    <tr>
                        <td class="right"><?php echo __('IVR Path', true); ?></td>
                        <td><input type="text" id="ivr_path" value="" name="ivr_path" class="input width220"></td>
                    </tr>

                    <tr>
                        <td class="right"><?php echo __('Codecs', true); ?></td>
                        <td >

                            <?php echo $form->input('status', array('id' => 'codecs', 'name' => 'codecs', 'options' => array('PCMU' => 'PCMU', 'PCMA' => 'PCMA', 'G729' => 'G729'), 'style' => 'float:left;width:220px;', 'label' => false, 'div' => false, 'type' => 'select')) ?>


                        </td>

                    </tr>

<!--<tr>
  <td>Interval:</td>
  <td>-->
                <input type="hidden" id="interval" value="1" name="interval" class="input width220">
                <!--</td>
              </tr>
              
                --></tbody></table>
            <div id="result">
            </div>
<?php if ($_SESSION['role_menu']['Tools']['testdevices']['model_r'] && $_SESSION['role_menu']['Tools']['testdevices']['model_x']) { ?>
            <div id="form_footer" class="center">
                    <input type="button" value="<?php echo __('submit') ?>" onclick="save_device();" class="input in-submit btn btn-primary">
                </div>
<?php } ?>
        </div>
