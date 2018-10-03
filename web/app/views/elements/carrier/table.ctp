<div style="width: 100%;; margin: 0px ">
    <fieldset>
        <legend  style='color:#7D858E;font-size:1.1em;font-weight:bold;'> <?php __('Hide Inactive Items')?> :
            <input  type="checkbox"   name="hidden_data"  <?php if (isset($_GET['where']) && $_GET['where'] == 'active') {
    echo "checked='checked'";
} ?>
                    onclick="($(this).attr('checked') == true) ? (location = location.toString().split('?')[0] + '?where=active') : (location = location.toString().split('?')[0] + '?where=hidden')">
        </legend>
        <?php
        $mydata = $p->getDataArray();
        if (empty($mydata)) {
            ?>
        <div class="msg center" style="font-weight: 600; font-size: 16px;"><?php echo __('no_data_found', true); ?></div>
                <?php } else { ?>
            <div class="clearfix"></div>
            <table class="list list-form   footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th width="10%" rowspan="2" style="padding-bottom: 20px;" rel="0"><?php echo __('Resource', true); ?></th>
                        <th class="cset-1" colspan="4">15 <?php echo __('minutes', true) ?></th>
                        <th colspan="4" class="cset-2">1 <?php echo __('hour', true) ?></th>
                        <th colspan="4" class="cset-3"><span id="ht-100002" class="helptip" rel="helptip">24 <?php echo __('hour', true) ?>s</span><span id="ht-100002-tooltip" class="tooltip">Average successful rate (percent of successful calls)</span></th>
                    </tr>
                    <tr>
                        <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('acd15m', __('avgduration', true)) ?>(s)&nbsp;</th>
                        <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('asr15m', __('ABR', true)) ?>&nbsp;</th>
                        <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('ca15m', __('calls', true)) ?>&nbsp;</th>
                        <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('pdd15m', __('calldelay', true)) ?>&nbsp;</th>
                        <!-- 
                             <th width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</th> 
                               
                        -->
                        <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('acd1h', __('avgduration', true)) ?>(s)&nbsp;</th>
                        <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('asr1h', __('ABR', true)) ?>&nbsp;</th>
                        <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('ca11h', __('calls', true)) ?>&nbsp;</th>
                        <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('pdd1h', __('calldelay', true)) ?>&nbsp;</th>
                        <!--    
                              <th width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</th> 
                              
                              
                        -->
                        <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('acd24h', __('avgduration', true)) ?>(s)&nbsp;</th>
                        <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('asr24h', __('ABR', true)) ?>&nbsp;</th>
                        <th width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('ca124h', __('calls', true)) ?>&nbsp;</th>
                        <th width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('pdd24h', __('calldelay', true)) ?>&nbsp;</th>
                        <!--
                             <th width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</th> 
                        --></tr>
                </thead>
                <?php
                $mydata = $p->getDataArray();
                $loop = count($mydata);
                for ($i = 0; $i < $loop; $i++) {


                    if ($i % 2 == 0) {
                        ?>
                        <tbody    id='tbodyOfShowTable'>
                            <tr>
                                <td class="in-decimal"><strong   > <span id="ht-100019" class="helptip" rel="helptip"><a class=" monitor_product_style_19"
                                                                                                                         href="<?php echo $this->webroot ?>monitorsreports/filterip/<?php echo $this->params['pass']['0'] ?>/<?php echo $mydata[$i][0]['qos_name'] ?>"  style='color:#4B9100'>
            <?php echo $mydata[$i][0]['name']; ?>
                                            </a></span> </strong></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['acd1'], 2) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['asr1'], 2) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['ca1'], 0) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['pdd1'], 0) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['acd2'], 2) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['asr2'], 2) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['ca2'], 0) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['pdd2'], 0) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['acd3'], 2) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['asr3'], 2) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['ca3'], 0) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['pdd3'], 0) ?></td>

                            </tr>
                        </tbody>
        <?php } else { ?>
                        <tbody >
                            <tr class="row-2">
                                <td class="in-decimal"><strong   > <span id="ht-100019" class="helptip" rel="helptip"><a class=" monitor_product_style_19"
                                                                                                                         href="<?php echo $this->webroot ?>monitorsreports/filterip/<?php echo $this->params['pass']['0'] ?>/<?php echo $mydata[$i][0]['qos_name'] ?>"  style='color:#4B9100'>
            <?php echo $mydata[$i][0]['name']; ?>
                                            </a></span> </strong></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['acd1'], 2) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['asr1'], 2) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['ca1'], 0) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['pdd1'], 0) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['acd2'], 2) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['asr2'], 2) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['ca2'], 0) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['pdd2'], 0) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['acd3'], 2) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['asr3'], 2) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['ca3'], 0) ?></td>
                                <td class="in-decimal"><?php echo number_format($mydata[$i][0]['pdd3'], 0) ?></td>
                            </tr>
                        </tbody>
        <?php }
    } ?>
            </table>
            <div class="separator bottom row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
    <?php echo $this->element('page'); ?>
                </div> 
            </div>
    </div>
<?php } ?>
</fieldset>
</div>