
<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>credit_managements">
        <?php __('Management') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>credit_managements">
        <?php echo __('Credit Management') ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Credit Management') ?></h4>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get" id="myform1">
                    <div>
                        <label><?php __('Client Type')?>:</label>
                        <select id="filter_client_type" name="filter_client_type" class="input in-select select">
                            <option value="0" <?php echo $common->set_get_select('filter_client_type', 0) ?>><?php __('All')?></option>
                            <option value="1" <?php echo $common->set_get_select('filter_client_type', 1, TRUE) ?>><?php __('All Active Clients')?></option>
                            <option value="2" <?php echo $common->set_get_select('filter_client_type', 2) ?>>All Inactive Clients</option>
                        </select>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="<?php __('Search')?>" value="Search" name="search">
                    </div>

                    <div>
                        <button name="submit" class="btn query_btn search_submit input in-submit"><?php __('Query')?></button>
                    </div>
                </form>
            </div>

            <div id="container">
                <?php
                $is_exchange = Configure::read('system.type') === 2 ? TRUE : FALSE;
                $data = $p->getDataArray();
                ?>
                <div class="clearfix"></div>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" id="key_list" >
                    <thead>
                        <tr>
                            <th><?php echo $appCommon->show_order('name', __('Name', true)); ?></th>
                            <th><?php echo $appCommon->show_order('allowed_credit', __('Credit Limit', true)) ?></th>
                            <th><?php echo $appCommon->show_order('cps_limit', __('CPS Limit', true)); ?></th>
                            <th><?php echo $appCommon->show_order('call_limit', __('Call Limit', true)); ?></th>
                            <th><?php __('Terms'); ?></th>
                            <th><?php __('Payment Terms'); ?></th>
                            <th><?php __('Last Paid On'); ?></th>
                            <th><?php __('Last Payment'); ?></th>
                            <th><?php __('Warning Limit'); ?></th>
                            <th><?php echo $appCommon->show_order('update_at', __('Update At', true)); ?></th>
                            <th><?php echo $appCommon->show_order('update_by', __('Update By', true)); ?></th>
                            <th class="last"><?php echo __('action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $item): ?>
                            <?php
                            $warning_limit = "--";
                            if($item[0]['is_daily_balance_notification']){
                                if($item[0]['notify_client_balance_type']){//percent
                                    if($item[0]['mode'] == 2 && !$item[0]['unlimited_credit'] && $item[0]['notify_client_balance'])
                                        $warning_limit = round($item[0]['notify_client_balance']*0.01*$item[0]['allowed_credit'],2);
                                }else{
                                    $warning_limit = $item[0]['notify_client_balance'];
                                }
                                $warning_limit = abs($warning_limit);
                            } ?>
                            <tr style="<?php if ($item[0]['status'] == 0) echo 'background:#ccc;'; ?>">
                                <td><a  href="<?php echo $this->webroot ?>clients/edit/<?php echo base64_encode($item[0]['client_id']) ?>" style="width:100%;display:block;"><?php echo $item[0]['name']; ?></a></td>
                                <td>
                                    <?php
                                    if($item[0]['unlimited_credit'] && $item[0]['mode'] == 2)
                                        __('Unlimited');
                                    elseif ($item[0]['mode'] == 1)
                                        echo "--";
                                    else
                                        echo number_format((float)abs($item[0]['allowed_credit']), 2, '.','');
                                    ?>
                                </td>
                                <td><?php echo $item[0]['cps_limit'] ?></td>
                                <td><?php echo $item[0]['call_limit'] ?></td>
                                <td><?php echo $item[0]['client_terms'] ?></td> 
                                <td><?php echo $item[0]['payment_term_name'] ?></td> 
                                <td><?php echo $item[0]['payment_time'] ?></td>
                                <td><?php echo number_format((float)$item[0]['amount'], 2, '.', '') ?></td>
                                <td><?php echo $warning_limit; ?></td>
                                <td><?php echo $item[0]['update_at'] ?></td>
                                <td><?php echo $item[0]['update_by'] ?></td>
                                <td>
                                    <?php if ($item[0]['status'] == 1): ?>
                                        <a title="<?php __('Inactive the client')?>" onclick="return myconfirm('Are you sure to deactivate the client [<?php echo $item[0]['name'] ?>] ?', this)" href="<?php echo $this->webroot ?>clients/dis_able/<?php echo base64_encode($item[0]['client_id']) ?>/true/?<?php echo $$hel->getParams('getUrl') ?>"><i class="icon-check"></i></a>
                                    <?php else: ?>
                                        <a title="Activate The Client" onclick="return myconfirm('Are you sure to activate the client [ <?php echo $item[0]['name'] ?>] ?', this)" href="<?php echo $this->webroot ?>clients/active/<?php echo base64_encode($item[0]['client_id']) ?>/true/?<?php echo $$hel->getParams('getUrl') ?>"><i class="icon-check-empty"></i></a>
                                    <?php endif; ?>
                                    <a title="<?php __('Edit')?>" class="edit_item" href="javascript:void(0);" control="<?php echo $item[0]['client_id'] ?>" >
                                        <i class="icon-edit"></i>
                                    </a>
                                    <a target="_blank" title="Send Low Balance Alert" href="<?php echo $this->webroot; ?>clients/low_balance_alert/<?php echo $item[0]['client_id']; ?>">
                                        <i class="icon-envelope"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(function() {

        jQuery('a.edit_item').click(function() {
            jQuery(this).parent().parent().trAdd({
                action: '<?php echo $this->webroot ?>credit_managements/action_edit_panel/' + jQuery(this).attr('control') + '?get_back_url=<?php echo base64_encode($this->params['getUrl']) ?>' ,
                ajax: '<?php echo $this->webroot ?>credit_managements/action_edit_panel/' + jQuery(this).attr('control'),
                saveType: 'edit',
                onsubmit: function(options)
                {
                    var credit_limit = $("#ClientAllowedCredit").val();
                    var cps_limit = $("#ClientCpsLimit").val();
                    var call_limit = $("#ClientCallLimit").val();
                    if (credit_limit != '' && typeof(credit_limit) != "undefined")
                    {
                        if (/^[\-\+]?((([0-9]{1,3})([,][0-9]{3})*)|([0-9]+))?([\.]([0-9]+))?$/.test(credit_limit) == false)
                        {
                            jGrowl_to_notyfy('CPS Limit must contain numeric characters only!', {theme: 'jmsg-error'});
                            return false;
                        }
                    }
                    if (cps_limit != '') {
                        if (/\D+|\./.test(cps_limit)) {
                            jGrowl_to_notyfy('CPS Limit must contain numeric characters only!', {theme: 'jmsg-error'});
                            return false;
                        }
                    }
                    if (call_limit != '') {
                        if (/\D+|\./.test(call_limit)) {
                            jGrowl_to_notyfy('Call Limit must contain numeric characters only!', {theme: 'jmsg-error'});
                            return false;
                        }
                    }
                    return true;
                }
            });
        });
    });

</script>