<?php $active = isset($active) ? $active : 'upload'; ?>
<ul>
    <li <?php if(!strcmp($active,'list')){ echo "class='active'";} ?>>
        <a class="glyphicons justify" href="<?php echo $this->webroot ?>routestrategys/routes_list/<?php echo base64_encode($id); ?>">
            <i></i>
            <?php __('List')?>
        </a>
    </li>
    <?php if ($_SESSION['role_menu']['Routing']['routestrategys']['model_x']): ?>
        <li <?php if(!strcmp($active,'upload')){ echo "class='active'";} ?>>
            <a href="<?php echo $this->webroot ?>uploads/route_plan/<?php echo base64_encode($id); ?>"  class="glyphicons upload">
                <i></i>
                <?php __('Import')?>
            </a>
        </li>
        <li <?php if(!strcmp($active,'download')){ echo "class='active'";} ?>>
            <a href="<?php echo $this->webroot ?>down/routing_plan/<?php echo base64_encode($id); ?>" class="glyphicons download">
                <i></i>
                <?php __('Export')?>
            </a>
        </li>
    <?php endif; ?>
        <li <?php if(!strcmp($active,'block')){ echo "class='active'";} ?>>
            <a href="<?php echo $this->webroot ?>routestrategys/lrn_block/<?php echo base64_encode($id); ?>" class="glyphicons list">
                <i></i>
                <?php __('LRN Block')?>
            </a>
        </li>
</ul>