<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tool') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Disk Space Monitor',true);?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Disk Space Monitor',true);?></h4>
    <div class="buttons pull-right">
        
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
        <thead>
            <tr>
                <th>Purpose</th>
                <th>Path</th>
                <th>Total Space</th>
                <th>Available Space</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <?php foreach($item as $value): ?>
                <td><?php echo $value; ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
    </div>
</div>