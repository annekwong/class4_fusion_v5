
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tool') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rerating List', true); ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Condition', true); ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a href="<?php echo $this->webroot ?>cdrreports/rerating" class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left"> <i></i>&nbsp;<?php __('Back')?> </a>

    </div>
    <div class="clearfix"></div>

<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul class="tabs">
                <li>
                    <a class="glyphicons no-js cogwheel" href="<?php echo $this->webroot ?>cdrreports/rerating">
                        <i></i><?php __('Rerate CDR') ?>		
                    </a>
                </li>
                <li class="active">
                    <a class="glyphicons no-js list" href="<?php echo $this->webroot ?>cdrreports/rerating_list">
                        <i></i><?php __('Rerate Result') ?> 		
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">
            <?php
            $data = $p->getDataArray();
            ?>
            <?php
            if (empty($data)):
                ?>
                <div id="list" class="second_tab" style="">
                    <h2 class="msg center"><?php  echo __('no_data_found') ?></h2>
                </div>
            <?php else: ?>
                <table id="list" class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                    <thead>
                        <tr>
                            <th rowspan="2"><?php __('ID'); ?></th>
                            <th colspan="2"><?php __('Rerate Period')?></th>
                            <th colspan="2"><?php __('Processing Time')?></th>
                            <th rowspan="2"><?php __('Status'); ?></th>
                            <th rowspan="2"><?php __('Rerate Type'); ?></th>
                            <th rowspan="2"><?php __('Rerate Rate Time'); ?></th>
                            <th rowspan="2"><?php __('Rate table'); ?></th>
                            <!--<th rowspan="2"><?php __('CDR Backup File'); ?></th>-->
                            <th rowspan="2"><?php __('Download'); ?></th>
                            <th rowspan="2"><?php __('Action'); ?></th>
                        </tr>
                        
                        <tr>
                            <th><?php __('Create Time'); ?></th>
                            <th><?php __('Finish Time'); ?></th>
                            <th><?php __('Start Time'); ?></th>
                            <th><?php __('End Time'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $item): ?>
                            <tr>
                                <td><?php echo $item[0]['id']; ?></td>
                                <td><?php echo $item[0]['create_time']; ?></td>
                                <td><?php echo $item[0]['finish_time']; ?></td>
                                
                                <td><?php echo $item[0]['start_time']; ?></td>
                                <td><?php echo $item[0]['end_time']; ?></td>
                                
                                <td><?php echo $status[$item[0]['status']]; ?></td>
                                <td><?php echo $item[0]['rerate_type'] == '1' ? 'Origination' : 'Termination'; ?></td>
                                <td><?php echo $item[0]['rerate_rate_time']; ?></td>
                                <td><?php echo $item[0]['rate_table_name']; ?></td>
                                <!--<td><?php echo $item[0]['cdr_backup_file']; ?></td>-->
                                
                                <td>
                                    <?php
                                    if (!empty($item[0]['cdr_backup_file'])) {
                                        echo "<a href='" . $this->webroot . "cdrreports/download?file=" . $item[0]['cdr_backup_file'] . "'><i class='icon-download'></i></a>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if($item[0]['pid']){ ?>
                                    <a class="delete" href="<?php echo $this->webroot; ?>cdrreports/kill_rerating/<?php echo $item[0]['pid']; ?>/<?php echo $item[0]['id']; ?>" onclick="return confirm('Are you sure do this?');" data-hasqtip="185" oldtitle="Deleted" title="" aria-describedby="qtip-185">
                                        <i class="icon-remove"></i>
                                    </a>
                                    <?php } ?>
                                </td>
                                <!--<td><?php //echo $item[0]['where_condition'];  ?></td>-->
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>
        </div>
    </div>
</div>