<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('OCN LATA', true); ?> [<?php echo $table_name; ?>]</li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('OCN LATA', true); ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>



<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">

            <ul class="tabs">
                <li class="active" ><a href="<?php echo $this->webroot ?>clientrates/view/<?php echo $table_id ?>/" class="glyphicons justify" ><i></i> <?php echo __('Rates', true); ?></a></li>

                <li><a href="<?php echo $this->webroot ?>clientrates/view/<?php echo $table_id ?>/<?php echo $currency ?>/npan" class="glyphicons notes_2"><i></i><?php echo __('NPANXX Rate', true); ?> </a></li>
                <li><a href="<?php echo $this->webroot ?>clientrates/simulate/<?php echo $table_id ?>/" class="glyphicons nails"><i></i> <?php echo __('Simulate', true); ?></a></li>   
                <li><a href="<?php echo $this->webroot ?>clientrates/import/<?php echo $table_id ?>"   class="glyphicons upload"><i></i> <?php echo __('Import', true); ?></a></li> 
                <li><a href="<?php echo $this->webroot ?>downloads/rate/<?php echo $table_id ?>"   class="glyphicons download"><i></i> <?php echo __('Export', true); ?></a></li>   


            </ul>


        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <form id="like_form" method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search')?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>

                </form>
                <div class="clearfix"></div>
            </div>

            <div class="separator bottom"></div>

                <?php
                if (empty($this->data)):
                    ?>
                <h3 class="center"><?php echo __('no_data_found', true); ?></h3>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary footable-loaded" id="mylist" style="display:none;">

                        <thead>
                            <tr>
                                <th><?php __('OCN')?></th>
                                <th><?php __('LATA')?></th>
                                <th><?php __('Intra Rate')?></th>
                                <th><?php __('Inter Rate')?></th>
                                <th><?php __('Rate')?></th>
                                <th><?php __('Effective Date')?></th>
                                <th><?php __('Interval')?></th>
                                <th><?php __('Min Time')?></th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="separator bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('xpage'); ?>
                        </div> 
                    </div>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary footable-loaded" id="mylist">
                        <thead>
                            <tr>
                                <th><?php __('OCN')?></th>
                                <th><?php __('LATA')?></th>
                                <th><?php __('Intra Rate')?></th>
                                <th><?php __('Inter Rate')?></th>
                                <th><?php __('Rate')?></th>
                                <th><?php __('Effective Date')?></th>
                                <th><?php __('Interval')?></th>
                                <th><?php __('Min Time')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($this->data as $item):
                                ?>
                                <tr>
                                    <td><?php echo $item['OcnLata']['ocn']; ?></td>
                                    <td><?php echo $item['OcnLata']['lata']; ?></td>
                                    <td><?php echo round($item['OcnLata']['intra_rate'], 5); ?></td>
                                    <td><?php echo round($item['OcnLata']['inter_rate'], 5); ?></td>
                                    <td><?php echo round($item['OcnLata']['rate'], 5); ?></td>
                                    <td><?php echo $item['OcnLata']['effective_date']; ?></td>
                                    <td><?php echo $item['OcnLata']['interval']; ?></td>
                                    <td><?php echo $item['OcnLata']['min_time']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="separator bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('xpage'); ?>
                        </div> 
                    </div>
                <?php endif; ?>
        </div>
    </div>
</div>
