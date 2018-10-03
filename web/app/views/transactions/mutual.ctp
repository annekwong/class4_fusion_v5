<style type="text/css">
    .form .label2 {
        font-size: 12px;
        width: 40%;
    }
    select, textarea, input[type="text"]{margin-bottom: 0;}
</style>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>transactions/mutual">
        <?php __('Finance') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>transactions/mutual">
        <?php echo __('Mutual Transaction', true); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Mutual Transaction', true); ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <form name="myform" method="get" id="myform">
                <input type="hidden" name="search" value="1" />
                <input type="hidden" id="is_down" name="is_down" value="0" />
                <div class="row-fluid">
                    <div class="span2 offset1">
                        <span style="position: relative;right: 9px;"><?php __('Carrier'); ?>:</span>
                        <select id="client" name="client_id" style="width:150px;">
                            <?php foreach($clients as $client): ?>
                                <option value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="span2">
                        <span class="padding-r10"><?php __('Type'); ?>:</span>
                        <select id="type" name="type" style="width:150px;">
                            <option value="0"><?php __('All'); ?></option>
                            <option value="1"><?php __('Payment Received'); ?></option>
                            <option value="2"><?php __('Payment Sent'); ?></option>
                            <option value="3"><?php __('Invoice Received'); ?></option>
                            <option value="4"><?php __('Invoice Sent'); ?></option>
                            <option value="5"><?php __('Credit Note Received'); ?></option>
                            <option value="6"><?php __('Credit Note Sent'); ?></option>
                            <option value="7"><?php __('Debit Note Received'); ?></option>
                            <option value="8"><?php __('Debit Note Sent'); ?></option>
                            <option value="9"><?php __('Reset'); ?></option>
                        </select>
                    </div>
                    <div class="span5">
                        <span class="padding-r10"><?php __('Period'); ?>:</span>
                        <input type="text" name="start" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" value="<?php echo $startdate; ?>" />
                        ~
                        <input type="text" name="end" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" value="<?php echo $enddate; ?>" />
                        <select class="input in-select select width120" name="gmt" id="query-tz">
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
                    <div class="span1">
                        <input type="submit" id="btnsub" class="btn btn-primary" value="<?php echo __('submit',true);?>" />
                    </div>
                </div>
            </form>
            <div class="separator"></div>
            <?php
            if(!empty($data)):
                $type_total = array(0,0,0,0,0,0,0,0,0,0,0,0);
                ?>

                <div class="clearfix"></div>
                <table class="list footable table table-striped  tableTools table-bordered  table-white table-primary">
                    <thead>
                    <tr>
                        <th><?php echo __('Begin Date',true);?></th>
                        <th><?php echo $startdate.$gmt; ?></th>
                        <th><?php __('Begin Balance');?></th>
                        <th><?php echo round($begin_balance, 2)?></th>
                    </tr>
                    </thead>
                </table>
                <div class="separator"></div>
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
                            <td><?php echo date("Y-m-d H:i:sO", strtotime($item[0]['a'])); ?></td>
                            <td><?php echo $all_type[$item[0]['b']]; ?></td>
                            <td><?php echo $item[0]['c'] ?></td>
                            <td><?php echo round($item[0]['d'], 2);$type_total[$item[0]['b']] += $item[0]['d'];  ?></td>
                            <td><?php echo round($common->total_balance_for_mutual($item[0]['d'], $item[0]['b'], $begin_balance), 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="separator"></div>
                <table class="list table  tableTools table-bordered  table-primary">
                    <thead></thead>
                    <tr>
                        <td><?php __('Payment Received Total')?>:</td>
                        <td><?php echo round($type_total[1], 2); ?></td>
                        <td><?php __('Payment Sent Total')?>:</td>
                        <td><?php echo round($type_total[2], 2); ?></td>
                        <!--        <td>invoice received:</td>
            <td><?php echo $type_total[3]; ?></td>
            <td>invoice sent:</td>
            <td><?php echo $type_total[4]; ?></td>   -->
                        <td><?php __('Credit Note Received')?>:</td>
                        <td><?php echo round($type_total[5], 2); ?></td>
                        <td><?php __('Credit Note Sent')?>:</td>
                        <td><?php echo round($type_total[6], 2); ?></td>
                        <td><?php __('Ingress Reset')?>:</td>
                        <td><?php echo round($type_total[9], 2); ?></td>
                    </tr>
                    <tr>
                        <td><?php __('Debit Note Received')?>:</td>
                        <td><?php echo round($type_total[7], 2); ?></td>
                        <td><?php __('Debit Note Sent')?>:</td>
                        <td><?php echo round($type_total[8], 2); ?></td>
                        <td><?php __('Invoice Received')?>:</td>
                        <td><?php echo round($type_total[3], 2); ?></td>
                        <td><?php __('Invoice Sent')?>:</td>
                        <td><?php echo round($type_total[4], 2); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
                <div class="separator center">
                    <input type="button" value="<?php __('Download')?>" id="download" class="btn btn-default" />
                </div>
            <?php
            endif;
            ?>
            <br />


            <!--
    <br />
    <fieldset style=" clear:both;overflow:hidden;margin-top:10px;" class="query-box">
        <div class="search_title">
          <img src="<?php echo $this->webroot; ?>images/search_title_icon.png">
          <?php echo __('Search',true);?>  
        </div>
        <div style="margin:0px auto; text-align:center;">
        <form name="myform" method="get" id="myform">
            <input type="hidden" name="search" value="1" />
            <input type="hidden" id="is_down" name="is_down" value="0" />
            Period:
            <input type="text" name="start" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" value="<?php echo $startdate; ?>" />
            ~
            <input type="text" name="end" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" value="<?php echo $enddate; ?>" />
            <?php __('in'); ?>
            <select style="width:100px;" class="input in-select select" name="gmt" id="query-tz">
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
            <?php echo __('carrier',true);?>:
            <select id="client" name="client_id">
                <?php foreach($clients as $client): ?>
                <option value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <?php echo __('type',true);?>:
            <select id="type" name="type">
                <option value="0">All</option>
                <option value="1">payment received</option>
                <option value="2">payment sent</option>
                <option value="3">invoice received</option>
                <option value="4">invoice sent</option>
                <option value="5">credit note received</option>
                <option value="6">credit note sent</option>
                <option value="7">debit note received</option>
                <option value="8">debit note sent</option>
                <option value="9">reset</option>
            </select>
            <input type="submit" id="btnsub" value="<?php echo __('submit',true);?>" />
            <input type="button" value="Download" id="download" />
        </form>
        </div>
   </fieldset>
    -->
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        <?php if(empty($data) && $flg){ ?>
//        jGrowl_to_notyfy('Data not found!', {theme: 'jmsg-error'});

        <?php } ?>
        <?php
            if(isset($_GET['client_id']))
                echo "$('#client option[value={$_GET['client_id']}]').attr('selected', true);\n";
            if(isset($_GET['type']))
                echo "$('#type option[value={$_GET['type']}]').attr('selected', true);\n";
        ?>

        $('#btnsub').click(function() {
            $('#is_down').val('0');
            $('#myform').removeAttr('target').submit();
        });

        $('#download').click(function() {
            $('#is_down').val('1');
            $('#myform').attr('target','_block').submit();
        });


    });

</script>