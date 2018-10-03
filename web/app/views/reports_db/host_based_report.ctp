<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>reports_db/host_based_report">
        <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php echo __('Host Based Report') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Host Based Report')?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li <?php if ($type == 1) echo 'class="active"'; ?>>
                    <a href="<?php echo $this->webroot; ?>reports_db/host_based_report/1"class="glyphicons left_arrow">
                        <i></i>
                        <?php __('Origination')?>
                    </a>
                </li>
                

                <li <?php if ($type == 2) echo 'class="active"'; ?>>
                    <a href="<?php echo $this->webroot; ?>reports_db/host_based_report/2"class="glyphicons right_arrow">
                        <i></i>
                        <?php __('Termination')?>
                    </a>
                </li>

            </ul>
        </div>
        <div class="widget-body">

            <h1 style="font-size:14px;"><?php __('Report Period')?> <?php echo $start_time ?> — <?php echo $end_time ?></h1>
            <?php if (empty($data)): ?>
                <div class="msg"><h2><?php  echo __('no_data_found') ?></h2></div>
            <?php else: ?>
                <div>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="color:#4B9100;">
                        <thead>
                            <tr>
                                <th  ><?php __('Carrier')?></th>
                                <th  ><?php __('Host')?></th>
                                <th  ><?php __('Call Attempt')?></th>
                                <th  ><?php __('Non Zero')?></th>
                                <th  ><?php __('ASR')?></th>
                                <th  ><?php __('ACD')?></th>
                                <th  ><?php __('PDD')?></th>
                                <th  ><?php __('Avg Rate')?></th>
                                <th ><?php __('Cost')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($data as $item):
//                                $item['client_id'] = '';
                                ?>
                                <tr>
                                    <td><?php echo isset($clients[$item['client_id']]) ? $clients[$item['client_id']] : ''; ?></td>
                                    <td><?php echo $item['ip'] ?></td>
                                    <td><?php echo $item['total_call']; ?></td>
                                    <td><?php echo $item['not_zero_calls']; ?></td>
                                    <td><?php echo $item['asr']; ?></td>
                                    <td><?php echo $item['acd']; ?></td>
                                    <td><?php echo $item['pdd']; ?></td>
                                    <td><?php echo $item['avg_rate']; ?></td>
                                    <td><?php echo $item['cost']; ?></td>

                                </tr>
                                <?php
                            endforeach;
                            ?>

                        </tbody>
                    </table>
                <div class="separator"></div>
                    <div class="pagination pagination-large pagination-right margin-none pull-right">

                        <?php echo $this->element('page'); ?>

                    </div>
                </div>
            <?php endif; ?>
            <?php echo $form->create('Cdr', array('class'=>'scheduled_report_form','type' => 'get', 'url' => "/reports_db/host_based_report/{$type}", 'onsubmit' => "if($('select[name=show_type]').val() == 0) loading();")); ?>
            <fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
                <h4 class="heading glyphicons search"><i></i> <?php __('Search')?></h4>
                <div style="margin:0px auto; text-align:center;">

                        <?php __('Carrier')?>:
                        <select name="client">
                            <option value=""><?php __('All')?></option>
                            <?php foreach($clients as $k => $client): ?>
                                <option <?php if(isset($_GET['client']) && $_GET['client'] == $k) echo 'selected="selected"'; ?> value="<?php echo $k ?>"><?php echo $client ?></option>
                            <?php endforeach; ?>
                        </select>

                        <?php __('Time')?>:
                        <input type="text" value="<?php echo $start_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="start_time" class="input in-text in-input">
                        ~
                        <input type="text" value="<?php echo $end_time; ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="end_time" class="input in-text in-input">
                        <input type="submit" value="<?php __('Submit')?>" class="input in-submit btn btn-primary margin-bottom10">

                </div>
            </fieldset>
            <?php echo $form->end(); ?>

        </div>
    </div>
</div>
<script type="text/javascript">


</script>
