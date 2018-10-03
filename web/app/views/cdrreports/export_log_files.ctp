<div class="widget">
    <div class="widget-body list">
        <ul>
            <?php
            foreach ($files as $file):
                if ($file == '.' || $file == '..')
                    continue;
                ?>
                <li>
                    <span><?php echo $file; ?></span>
                    <span class="count">
                        <a title="Download" href="<?php echo $this->webroot ?>cdrreports/export_log_down_file?key=<?php echo $key; ?>&file=<?php echo urlencode($file); ?>">
                            <i class="icon-download-alt"></i>
                        </a>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>