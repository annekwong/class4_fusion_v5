<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('SIP PACKET Search') ?></li>
</ul>
<?php

    $user_id = $_SESSION['sst_user_id'];
    //$res = $cdr_db->query("select * from users where user_id = {$user_id} ");
    $data = $p->getDataArray();
?>
<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Premature Abandon') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">

                <?php if (empty($data)): ?>
                    <?php if ($show_nodata): ?><h2 class="msg center"><?php  echo __('no_data_found') ?></h2><?php endif; ?>
                <?php else: ?>
                    
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="color:#4B9100;">
                    <thead>
                        <tr>
                            <th><?php __('Time')?></th>
                            <th><?php __('Orig	 ANI')?></th>
                            <th><?php __('Orig DNIS') ?></th>
                            <th><?php __('Orig Call ID') ?></th>
                            <th><?php __('Term DNIS') ?></th>
                            <th><?php __('Duration') ?></th>
                            <th><?php __('Action') ?></th>
                        </tr>
                        
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php __('Connected Calls')?></td>
                            <td><?php __('Connected Calls')?></td>
                            <td><?php __('Connected Calls')?></td>
                            <td><?php __('Connected Calls')?></td>
                            <td><?php __('Connected Calls')?></td>
                            <td><?php __('Connected Calls')?></td>
                            <td><?php __('Connected Calls')?></td>
                        </tr>
                    </tbody>
                </table>
                <div class="row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
                <div class="clearfix"></div>
             <?php endif; ?>
            <?php echo $form->create('Cdr', array('class'=>'scheduled_report_form','type' => 'get', 'url' => "/check_route/sip_packet/")); ?>
            <fieldset class="query-box" style="clear:both;overflow:hidden;margin-top:10px;">
                <h4 class="heading glyphicons search" style="display: inline-block;"><i></i> <?php __('Search')?></h4>
                <div class="clearfix"></div>
                <?php echo $this->element('search_report/search_js'); ?> 
                <table class="form" style="width:100%">
                    <?php echo $this->element('report_db/form_period', array('group_time' => false, 'gettype' => false)) ?>
                </table>
            </fieldset>
<?php echo $form->end(); ?>
        </div>


        <script type="text/javascript">
            
        </script>
    </div>
