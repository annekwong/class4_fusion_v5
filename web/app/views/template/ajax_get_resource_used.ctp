<ul>
    <?php foreach($data as $data_item): ?>
        <li>
            <a href="<?php echo $this->webroot; ?>prresource/gatewaygroups/<?php echo $function; ?>/<?php echo base64_encode($data_item[0]['resource_id']); ?>"
                target="_blank">
                <?php echo $data_item[0]['alias']; ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>