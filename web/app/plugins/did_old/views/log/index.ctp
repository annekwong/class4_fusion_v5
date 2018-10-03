<style type="text/css">
    .list tbody tr span {margin:0 10px;}
</style>


<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Log') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Origination Log', true); ?></li>
</ul>

<div class="heading-buttons">
    <h1><?php echo __('Origination Log', true); ?></h1>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>



<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">

            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Time')?>:</label>
                        <input id="start_time" class="input in-text wdate " value="<?php
                        if (isset($get_data['start_time']))
                        {
                            echo $get_data['start_time'];
                        }
                        ?>" type="text" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="start_time">
                        -- 
                        <input id="end_time" class="wdate input in-text" type="text" value="<?php
                        if (isset($get_data['end_time']))
                        {
                            echo $get_data['end_time'];
                        }
                        ?>" readonly="" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});" name="end_time">
                    </div>
                    <div>
                        <label><?php __('Detail')?>:</label>
                        <input type="text" name="detail" value="<?php echo $get_data['detail'] ?>" title="Search" class="in-search input in-text in-input input-small">
                    </div>
                    <div>
                        <label><?php __('Operator')?>:</label>
                        <select name="operator" style="width:100px;" >
                            <option value=""></option>
                            <?php foreach ($all_operator as $op_name): ?>
                                <option value="<?php echo $op_name ?>" <?php if (!strcmp($op_name, $get_data['operator'])): ?>selected="selected"<?php endif; ?>><?php echo $op_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label><?php __('Action')?>:</label>
                        <select name="action" style="width:100px;" >
                            <option value=""></option>
                            <option value="0" <?php if (!strcmp(0, $get_data['action'])): ?>selected="selected"<?php endif; ?>><?php __('Creation')?></option>
                            <option value="1" <?php if (!strcmp(1, $get_data['action'])): ?>selected="selected"<?php endif; ?>><?php __('Deletion')?></option>
                            <option value="2" <?php if (!strcmp(2, $get_data['action'])): ?>selected="selected"<?php endif; ?>><?php __('Modification')?></option>
                        </select>
                    </div>
                    <div>
                        <div>
                            <input type="hidden" name="search" />
                            <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                        </div>
                        <!-- // Filter END -->
                </form>
            </div>
        </div>

        <?php
        if (empty($this->data)):
            ?>
            <div class="separator bottom row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                </div> 
            </div>
            <h2 class="msg center"><?php echo __('no_data_found', true); ?></h2>
        <?php else: ?>
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th><?php echo $appCommon->show_order('update_on', __('Time', true)) ?></th>
                        <th><?php __('Module')?></th>
                        <th><?php echo $appCommon->show_order('update_by', __('Operator', true)) ?></th>
                        <th><?php __('Action')?></th>
                        <th><?php __('Detail')?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($this->data as $item): ?>
                        <tr>
                            <td><?php echo $item['OrigLog']['update_on'] ?></td>
                            <td><?php echo $item['OrigLog']['module']; ?></td>
                            <td><?php echo $item['OrigLog']['update_by']; ?></td>
                            <td><?php echo isset($type[$item['OrigLog']['type']]) ? $type[$item['OrigLog']['type']] : "--"; ?></td>
                            <td>
                                <?php
                                if (strlen($item['OrigLog']['detail']) < 20)
                                {
                                    echo $item['OrigLog']['detail'];
                                }
                                else
                                {  
                                    $a_title = str_replace(";", "<br />", $item['OrigLog']['detail']);
                                    ?>
                                <a title="<?php echo $a_title; ?>" href="javascript:void(0)"><?php  echo substr($item['OrigLog']['detail'], 0, 17)."..."; ?></a>
                                <?php }
                                ?>
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
            <div class="clearfix"></div>
<?php endif; ?>
    </div>
</div>
</div>

<script type="text/javascript">
</script>
