<style type="text/css">
    .form .label2 {
        font-size: 12px;
        width: 40%;
    }
</style>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>transactions/actual">
        <?php __('Finance') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>transactions/actual">
        <?php echo __('Actual Transaction',true);?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Actual Transaction',true);?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <?php
            if(!empty($data)):
                $type_total = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
                ?>
                <table class="list footable table table-striped  tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php echo __('Begin Date',true);?></th>
                        <th><?php echo $startdate.$gmt; ?></th>
                        <th><?php echo __('End Date',true);?></th>
                        <th><?php echo $enddate.$gmt; ?></th>
                        <th><?php __('Begin Balance');?></th>
                        <th><?php echo round($begin_balance, 2)?></th>
                    </tr>
                    </thead>
                </table>
                <br />
                <table class="list footable table table-striped  tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php echo __('Date',true);?></th>
                        <th><?php echo __('Type',true);?></th>
                        <th><?php echo __('Carrier',true);?></th>
                        <th><?php echo __('Amount',true);?></th>
                        <th><?php __('Balance'); ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach($data as $item): ?>
                        <tr>
                            <td><?php echo date("Y-m-d H:i:s".$_GET['query']['tz'], strtotime($item[0]['a'])); ?></td>
                            <td><?php echo $all_type[$item[0]['b']]; ?></td>
                            <td><?php echo $item[0]['c'] ?></td>
                            <td><?php echo round($item[0]['d'], 2);$type_total[$item[0]['b']] += $item[0]['d'];  ?></td>
                            <td><?php echo round($common->total_balance_for_actual($item[0]['d'], $item[0]['b'], $begin_balance), 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <table class="list footable table table-striped  tableTools table-bordered  table-white table-primary">
                    <thead></thead>
                    <tr>
                        <td>payment received total:</td>
                        <td><?php echo round($totalValues[0][0]['payment_received'], 2); ?></td>
                        <td>payment sent total:</td>
                        <td><?php echo round($totalValues[0][0]['payment_sent'], 2); ?></td>
                        <td>credit note received:</td>
                        <td><?php echo round($totalValues[0][0]['credit_note_received'], 2); ?></td>
                        <td>credit note sent:</td>
                        <td><?php echo round($totalValues[0][0]['credit_note_sent'], 2); ?></td>
                        <td>ingress reset:</td>
                        <td><?php echo round($totalValues[0][0]['ingress_reset'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>debit note received:</td>
                        <td><?php echo round($totalValues[0][0]['debit_note_received'], 2); ?></td>
                        <td>debit note sent:</td>
                        <td><?php echo round($totalValues[0][0]['debit_note_sent'], 2); ?></td>
                        <td>egress actual usage:</td>
                        <td><?php echo round($totalValues[0][0]['egress_call_cost'], 2); ?></td>
                        <td>ingress actual usage:</td>
                        <td><?php echo round($totalValues[0][0]['ingress_call_cost'], 2); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
                <?php
            endif;
            ?>
            <br />

            <form name="myform" method="get" id="myform">
                <input type="hidden" name="search" value="1" />
                <input type="hidden" id="is_down" name="is_down" value="0" />
                <table class="form table  tableTools table-bordered  table-condensed">
                    <colgroup>
                        <col width="40%">
                        <col width="60%">
                    </colgroup>
                    <tr>
                        <td class="align_right"><?php __('Carrier'); ?> </td>
                        <td>
                            <select id="client" name="client_id" >
                                <?php foreach($clients as $client): ?>
                                    <option value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php __('Type'); ?> </td>
                        <td>
                            <select id="type" name="type" >
                                <option value="0"><?php __('All')?></option>
                                <option value="1"><?php __('Payment Received')?></option>
                                <option value="2"><?php __('Payment Sent')?></option>
                                <option value="5"><?php __('Credit Note Received')?></option>
                                <option value="6"><?php __('Credit Note Sent')?></option>
                                <option value="7"><?php __('Debit Note Received')?></option>
                                <option value="8"><?php __('Debit Note Sent')?></option>
                                <option value="9"><?php __('Revert')?></option>
                                <option value="10"><?php __('Egress Actual Usage')?></option>
                                <option value="11"><?php __('Ingress Actual Usage')?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php __('Time Zone'); ?> </td>
                        <td>
                            <select class="input in-select select" name="query[tz]" id="query-tz">
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
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php __('Period'); ?> <?php echo $startdate.' ~ '.$enddate; ?> </td>
                        <td>
                            <input type="text" class="required" name="start" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" value="<?php echo $startdate; ?>" />
                            ~
                            <input type="text" class="required" name="end" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" value="<?php echo $enddate; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="buttons-group center" colspan="2">
                            <input type="submit" id="btnsub" class="btn btn-primary" value="<?php echo __('submit',true);?>" />
                            <input type="button" value="<?php __('Download')  ?>" id="download" class="btn btn-default" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {

        <?php if(empty($data) && $flg){ ?>
        jGrowl_to_notyfy('The Data is not found!', {theme: 'jmsg-error'});

        <?php } ?>
        <?php
            if(isset($_GET['client_id']))
                echo "$('#client option[value={$_GET['client_id']}]').attr('selected', true);\n";
            if(isset($_GET['type']))
                echo "$('#type option[value={$_GET['type']}]').attr('selected', true);\n";
                if(isset($_GET['gmt']))
                echo "$('#query-tz option[value={$_GET['gmt']}]').attr('selected', true);\n";
        ?>

        $('#btnsub').click(function() {
            if(!$('#myform').find('input[name="start"]').val()){
                jGrowl_to_notyfy('Start date cannot be empty!', {theme: 'jmsg-error'});
                return false;
            }
            if(!$('#myform').find('input[name="end"]').val()){
                jGrowl_to_notyfy('End date cannot be empty!', {theme: 'jmsg-error'});
                return false;
            }
            $('#is_down').val('0');
            $('#myform').attr('target','').submit();
        });

        $('#download').click(function() {
            if(!$('#myform').find('input[name="start"]').val()){
                jGrowl_to_notyfy('Start date cannot be empty!', {theme: 'jmsg-error'});
                return false;
            }
            if(!$('#myform').find('input[name="end"]').val()){
                jGrowl_to_notyfy('End date cannot be empty!', {theme: 'jmsg-error'});
                return false;
            }
            $('#is_down').val('1');
            $('#myform').attr('target','_blank').submit();
        });


    });
</script>
