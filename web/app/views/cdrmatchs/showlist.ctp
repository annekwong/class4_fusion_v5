<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('CDR Reconciliation') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">CDR Reconciliation</h4>
    <div class="buttons pull-right">

        <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot; ?>cdrmatchs/index"><i></i> Create New</a>
        <a class="btn btn-default btn-icon glyphicons left_arrow" href="<?php echo $this->webroot; ?>cdrmatchs/index">
<i></i>Back</a>

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">

<div class="widget-body">


<?php
        $data =$p->getDataArray();
?>

<div id="container">
    <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
        <thead>
            <tr>
                <th><?php __('Create Time') ?></th>
                <th><?php __('Status') ?></th>
                <th><?php __('Finish Time') ?></th>
                <th><?php __('Format') ?></th>
                <!--<th><?php __('Rate') ?></th>
                <th><?php __('Duration Diff') ?></th>
                <th><?php __('Calltime Diff') ?></th>-->
                <th><?php __('Diff Report File') ?></th>
                <th><?php __('Diff CDR File') ?></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($data as $item) :?>
            <tr>
                <td><?php echo $item[0]['create_time']; ?></td>
                <td><?php echo $status[$item[0]['status']]; ?></td>
                <td><?php echo $item[0]['finish_time']; ?></td>
                <td><?php echo $item[0]['format'] == 0 ? 'Line-by-Line' : 'Aggregated Comparison'; ?></td>
                <!--<td><?php echo $item[0]['is_rate'] == 0 ? 'False' : 'True'; ?></td>
                <td><?php echo $item[0]['duration_diff']; ?></td>
                <td><?php echo $item[0]['calltime_diff']; ?></td>-->
                <td>
                    <?php
                        if(!empty($item[0]['diff_report_file'])) {
                            echo "<a href='" . $this->webroot . "cdrmatchs/download?file=" . $item[0]['diff_report_file'] . "'>Download</a>";
                        }
                    ?>
                </td>
                <td>
                    <?php
                        if(!empty($item[0]['diff_cdr_file'])) {
                            echo "<a href='" . $this->webroot . "cdrmatchs/download?file=" . $item[0]['diff_cdr_file'] . "'>Download</a>";
                        }
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
</div>