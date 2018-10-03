<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <?php if($_SESSION['login_type'] == 3):?>
        <li><?php __('Client Portal') ?></li>
    <?php else:?>
        <li><?php __('Management') ?></li>
    <?php endif;?>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Payment Record') ?></li>
</ul>
<?php $data = $p->getDataArray(); ?>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Payment Record') ?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                    <form method="get" name="myform">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Period')?>:</label>
                        <input type="text" value="<?php echo $start_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})"  name="start_time" class="input in-text in-input">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label>~</label>
                        <input type="text" value="<?php echo $end_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" name="end_time" class="input in-text in-input">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
                <div class="clearfix"></div>
            </div>
            <div class="separator bottom"></div>
            <?php if (empty($data)): ?>
                <h2 class="msg center"><?php __('No Data is Available')?>.</h2>
            <?php else: ?>
                <table class="list footable table table-striped tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th><?php __('Paid On') ?></th>
                            <th><?php __('Paid Amount') ?></th>
                            <th><?php __('Fee') ?></th>
                            <th><?php __('Service Charge') ?></th>
                            <th><?php __('Received Amount') ?></th>
                            <th><?php __('Method') ?></th>
                            <th><?php __('Transaction ID') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $item): ?>
                            <tr>
                                <td><?=$item[0]['paid_on']?></td>
                                <td><?=number_format($item[0]['amount'],2)?></td>
                                <td><?=$item[0]['fee']?></td>
                                <td><?=$item[0]['charge_amount']?></td>
                                <td><?=number_format($item[0]['amount'] - $item[0]['charge_amount'] - $item[0]['fee'],2)?></td>
                                <td><?=isset($method[$item[0]['method']]) ? $method[$item[0]['method']] : '';?></td>
                                <td><?=$item[0]['transaction_id']?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>
            <!--
            <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
                <div class="search_title">
                    <img src="<?php echo $this->webroot; ?>images/search_title_icon.png">
                  Search  
                </div>
                <div style="margin:0px auto; text-align:center;">
                <form method="get" name="myform">
                    Period:
                    <input type="text" value="<?php echo $start_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="start_time" class="input in-text in-input">
                    ~
                    <input type="text" value="<?php echo $end_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="end_time" class="input in-text in-input">
                    <input type="submit" value="Submit" class="input in-submit">
                </form>
                </div>
           </fieldset>
            -->
        </div>
    </div>
</div>