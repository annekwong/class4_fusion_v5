<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>clientrates/rate_mass_edit_log">
        <?php echo __('Log', true); ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>clientrates/rate_mass_edit_log">
        <?php echo __('Rate Mass Edit Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rate Mass Edit Log',true);?></h4>
    <div class="buttons pull-right">
       
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="clearfix"></div>
            <?php if (!count($this->data)): ?>
            <div class="msg center">
                <br />
                <h2>
                    <?php echo __('no_data_found', true); ?>
                </h2>
            </div>
            <?php else: ?>
    <table class="dynamicTable colVis list footable table table-striped tableTools table-bordered  table-white table-primary">
        <thead>
            <tr>
                <th><?php echo $appCommon->show_order('RateMassEditLog.action_time', __('Edited On', true)) ?></th>
                <th><?php echo $appCommon->show_order('Client.name', __('Edited By', true)) ?></th>
                <th><?php echo $appCommon->show_order('RateTable.name', __('Rate Table', true)) ?></th>
                <th><?php echo $appCommon->show_order('RateMassEditLog.action_type', __('Mass Type', true)) ?></th>
                <th><?php echo $appCommon->show_order('RateMassEditLog.action_rate_rows', __('Action Rate Rows', true)) ?></th>
                <th><?php echo  __('Action', true) ?></th>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($this->data as $item): ?>
            <tr>
                <td><?php echo $item['RateMassEditLog']['action_time']; ?></td>
                <td>
                    <?php
                            echo $item['Client']['name'];

                    ?>
                </td>
                <td>
                    <?php
                    echo $item['RateTable']['name'];

                    ?>
                </td>
                <td>
                    <?php
                    echo array_key_exists($item['RateMassEditLog']['action_type'], $types) ? $types[$item['RateMassEditLog']['action_type']] : '';
                    ?>
                </td>
                <td><?php echo ($item['RateMassEditLog']['action_rate_rows'] == -1) ? 'all' : $item['RateMassEditLog']['action_rate_rows']; ?></td>
                <td>
                    <a target="_blank" href="<?php echo $this->webroot . 'clientrates/download_rate_log_file/' . base64_encode($item['RateMassEditLog']['down_file_path']) . '/' . base64_encode($item['RateMassEditLog']['rate_table_id'] . ';' . $item['RateMassEditLog']['action_type'])?>" title="download"><i class="icon-download"></i></a>


                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('xpage'); ?>
                </div> 
            </div>
            <?php endif ?>
            <div class="clearfix"></div>


<fieldset class="query-box" style=" clear:both;overflow:hidden;margin-top:10px;">
        <h4 class="heading glyphicons search"><i></i> <?php __('Search')?></h4>
        <div style="margin:0px auto; text-align:center;">
        <form method="get" name="myform">
            <?php __('Type')?>
            <select name="type">
                <option value=""><?php __('All')?></option>
                <?php foreach ($types as $key=>$value): ?>
                <option value="<?php echo $key; ?>" <?php if(isset($_GET['type']) && $_GET['type'] === (string) $key) echo 'selected="selected"'; ?>><?php echo $value; ?></option>
                <?php endforeach; ?>
            </select>
            <?php __('Time')?>:
            <input type="text" value="<?php echo $start_time ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="start_time" class="input in-text in-input">
            ~
            <input type="text" value="<?php echo $end_time; ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" style="width:120px;" name="end_time" class="input in-text in-input">
            <input type="submit" value="<?php __('Submit')?>" class="input in-submit btn btn-primary margin-bottom10">
        </form>
        </div>
   </fieldset>

            </div>
    </div>
</div>


