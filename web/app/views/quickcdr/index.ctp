<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Simple CDR Export') ?></li>
</ul>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        
    <?php echo $this->element('quickcdr/tab', array('active' => 'form')); ?>
    <div class="widget-body">
    <form method="post">
        <table class="list table dynamicTable tableTools table-bordered  table-white form">
            <tbody>
            <col width="40%">
            <col width="60%">
                <?php if (isset($_SESSION ['sst_client_id'])): ?>
                <input type="hidden" name="type" value="0">
                <?php else: ?>
                <tr>
                    <td class="align_right"><?php __('Type')?></td>
                    <td>
                        <select name="type" id="type">
                            <option value="0"><?php __('Ingress')?></option>
                            <option value="1"><?php __('Egress')?></option>
                        </select>
                       
                    </td>
                </tr>
                 <?php endif; ?>
                <?php if (isset($_SESSION ['sst_client_id'])): ?>
                <input type="hidden" name="client_id" value="<?php echo $_SESSION ['sst_client_id']; ?>">
                <?php else: ?>
                <tr>
                    <td class="align_right"><?php __('Client')?></td>
                    <td>
                        <select name="client_id">
                            <?php foreach ($clients as $client_id => $client_name): ?>
                            <option value="<?php echo $client_id ?>"><?php echo $client_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td class="align_right"><?php __('Start Date')?></td>
                    <td>
                        <input type="text" name="start_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" value="<?php echo date("Y-m-d", strtotime("-1 days")); ?>">
                    </td>
                </tr>
                <tr>
                    <td class="align_right"><?php __('End Date')?></td>
                    <td>
                        <input type="text" name="end_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" value="<?php echo date("Y-m-d", strtotime("-1 days")); ?>">
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="center">
                        <input type="submit" value="<?php __('Submit')?>" class="btn btn-primary">
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>
    </div>
        </div>
</div>