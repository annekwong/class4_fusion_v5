<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('CID Blocking') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('CID Blocking') ?></h4>
</div>
<div class="clearfix"></div>
<div class="innerLR">
<?php $login_type = $_SESSION['login_type'];  ?>
    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
        <div class="filter-bar">
            <form method="get">
            <?php if($login_type != '3'):?>
                <div>
                    <label><?php __('Carriers')?>:</label>
					<?php
					$client_id = isset($_GET['data']['client_id']) && $_GET['data']['client_id'] ? $_GET['data']['client_id'] : '';
					echo $form->input('client_id', array('style' => 'width: 130px;','options' => $carriers, 'value' => $client_id, 'empty' => '', 'label' => false,'class' => 'select select2', 'div' => false, 'type' => 'select')); ?>
                </div>
            <?php endif; ?>
                <div>
                    <label><?php __('Ingress Trunk')?>:</label>
					<?php
                     $ingress_id = isset($_GET['data']['ingress_id']) && $_GET['data']['ingress_id']? $_GET['data']['ingress_id'] : '';
					 echo $form->input('ingress_id', array('style' => 'width: 130px;','options' => $ingress, 'value' => $ingress_id, 'empty' => '', 'label' => false,'class' => 'select select2', 'div' => false, 'type' => 'select')); ?>
                </div>
             <?php if($login_type != '3'):?>
                <div>
                    <label><?php __('Egress Trunk')?>:</label>
					<?php
			        $egress_id = isset($_GET['data']['egress_id']) && $_GET['data']['egress_id'] ? $_GET['data']['egress_id'] : '';
					echo $form->input('egress_id', array('style' => 'width: 130px;','options' => $egress, 'value' => $egress_id, 'empty' => '', 'label' => false,'class' => 'select select2', 'div' => false, 'type' => 'select')); ?>
                </div>
             <?php endif;?>
                <div>
                    <?php
                    	$start_time = isset($_GET['data']['start_time']) && $_GET['data']['start_time'] ? $_GET['data']['start_time']: '';
                    	$end_time = isset($_GET['data']['end_time']) && $_GET['data']['end_time']? $_GET['data']['end_time'] : '';
                    ?>
                    <label><?php __('Time')?>:</label>
                    <input id="start_time" style="height:25px; width:130px;" class="input in-text wdate " value="<?php if ($start_time) { echo $start_time;} ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="data[start_time]">--
                    <input id="end_time" style="height:25px; width:130px;" class="wdate input in-text" type="text" value="<?php
                    if ($end_time)
                    {
                        echo $end_time;
                    }
                    ?>" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="data[end_time]">
                </div>
                <div>
                    <label><?php __('Order By')?>:</label>
					<?php
				    $order = isset($_GET['data']['order_by']) && $_GET['data']['order_by'] ? $_GET['data']['order_by'] : '';
					echo $form->input('order_by', array('style' => 'height:29px; width: 130px;','options' => $order_by, 'value' => $order, 'empty' => '', 'label' => false,'class' => 'select', 'div' => false, 'type' => 'select')); ?>
                </div>
                <div>
                    <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                </div>
            </form>
            </div>
            <div class="clearfix"></div>
            <?php if (empty($this->data)): ?>
                <h2 class="msg center"><br/><?php echo __('no_data_found', true); ?></h2>
            <?php else: ?>
                <div class="overflow_x">
                    <table class="list footable table table-striped tableTools dynamicTable table-bordered  table-white table-primary">
                        <thead>
                        <tr>
                           <th rowspan='2'><?php __('Carrier')?></th>
                           <th rowspan='2'><?php __('Trunk')?></th>
                           <th colspan='4'><?php __('Account Limits')?></th>
                           <th colspan='7'><?php __('Stats')?></th>
                           <th colspan='2'><?php __('Historical')?></th>
                        </tr>
                        <tr>
                           <th><?php __('Enforce')?></th>
                           <th><?php __('ASR')?></th>
                           <th><?php __('ACD')?></th>
                           <th><?php __('SDP')?></th>

                           <th><?php __('Start')?></th>
                           <th><?php __('End')?></th>
                           <th><?php __('Caller ID')?></th>
                           <th><?php __('ASR')?></th>
                           <th><?php __('ACD')?></th>
                           <th><?php __('SDP')?></th>
                           <th><?php __('Call Attempts')?></th>

                           <th><?php __('First Block')?></th>
                           <th><?php __('Last Block')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($this->data as $item):?>
                              <tr>
                                  <td><?php echo $item['Client']['name']; ?></td>
                                  <td><?php echo $item['Resource']['alias']; ?></td>

                                  <td><?php echo $item['AlertRules']['auto_define'] ? 'Yes' : 'No'; ?></td>
                                  <td><?php echo $item['AlertRules']['asr_value'];?></td>
                                  <td><?php echo $item['AlertRules']['acd_value'];?></td>
                                  <td><?php echo $item['Resource']['cid_max_sdp'];?></td>

                                  <td><?php echo date("Y-m-d H:i:s", strtotime($item[0]['create_on']));?></td>
                                  <td><?php echo date("Y-m-d H:i:s", strtotime($item[0]['finish_time']));?></td>
                                  <td><?php echo $item[0]['code'];?></td>
                                  <td><?php echo $item[0]['asr'];?></td>
                                  <td><?php echo $item[0]['acd'];?></td>
                                  <td><?php echo $item[0]['call_attempt'];?></td>
                                  <td><?php echo $item['AlertRulesLogDetail']['sdp_value'];?></td>

                                  <td><?php echo date("Y-m-d H:i:s", strtotime($item['AlertRules']['first_block']));?></td>
                                  <td><?php echo date("Y-m-d H:i:s", strtotime($item['AlertRules']['last_block']));?></td>
                              </tr>
                        <?php endforeach;?>

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
<script>
$(document).ready(function(){
   $('.select2').select2();
});
</script>