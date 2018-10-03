<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>credit_logs"><?php __('Log') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>credit_logs"><?php __('Credit Mondification Log')?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __(' Credit Mondification Log')?></h4>
   
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>




<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
            <form method="get">
                <!-- Filter -->
                <div>
                    <label><?php __('Client')?>:</label>
                    <select name="client">
                        <option value=""><?php __('All')?></option>
                        <?php foreach($clients as $client): ?>
                        <option <?php if(isset($_GET['client']) && $_GET['client'] == $client[0]['name']) echo 'selected="selected"'; ?> value="<?php echo $client[0]['name'] ?>"><?php echo $client[0]['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- // Filter END -->
                <!-- Filter -->
                <div>
                    <label><?php __('Start Date')?>:</label>
                    <input type="text" value="<?php echo $start_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" style="width:120px;" name="start_time" class="input in-text in-input">
                </div>

                <div>
                    <label><?php __('End Date')?>:</label>
                    <input type="text" value="<?php echo $end_time; ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en'})" style="width:120px;" name="end_time" class="input in-text in-input">
                </div>
                <div>
                    <button class="btn query_btn" name="submit"><?php __('Query')?></button>
                </div>
                <!-- // Filter END -->

            </form>
        </div>
            <div class="clearfix"></div>
            <?php if (empty($this->data)): ?>
                <h2 class="msg center"><br /><?php echo __('no_data_found', true); ?></h2>
            <?php else: ?>
             <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                 <thead>
                     <tr>
                        <th><?php __('Modified By')?></th>
                        <th><?php __('Modified From')?></th>
                        <th><?php __('Modified To')?></th>
                        <th><?php __('Modified On')?></th>
                        <th><?php __('Carrier Name')?></th>
                    </tr>
                 </thead>
                 <tbody>
                            <?php foreach($this->data as $item): ?>
                            <tr>
                                <td><?php echo $item['CreditLog']['modified_by']; ?></td>
                                <td><?php echo $item['CreditLog']['modified_from'] === NULL ? 'Unlimited' : abs($item['CreditLog']['modified_from']); ?></td>
                                <td><?php echo $item['CreditLog']['modified_to'] === NULL ? 'Unlimited' : abs($item['CreditLog']['modified_to']); ?></td>
                                <td><?php echo $item['CreditLog']['modified_on']; ?></td>
                                <td><?php echo $item['CreditLog']['carrier_name']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                 </tbody>
             </table>
            <div class="row-fluid separator">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('xpage'); ?>
                </div> 
            </div>
            <?php endif; ?>
            <div class="clearfix"></div>
        </div>
        
    </div>
    
</div>

