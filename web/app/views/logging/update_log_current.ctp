<div id="title">
    <h1><?php  __("Current Update Log"); ?></h1>
    <ul id="title-menu">
        <li>
            <a href="###" onclick="window.location.reload();" class="link_btn">
                <img width="16" height="16" alt="" src="<?php echo $this->webroot ?>images/refresh.png"><?php  __("Refresh"); ?>
            </a>
        </li>
    </ul>
</div>

<div id="container">
    <ul class="tabs">
        <li class="active">
            <a href="<?php echo $this->webroot ?>logging/update_log_current">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/current.png">
                <?php  __("Current Update Log"); ?>
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>logging/update_log_history">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/history.png">
                <?php  __("History Update Log"); ?>
            </a>
        </li>
    </ul>
    <div class="window" style="max-height:800px;overflow-y: auto;">
        <?php
        echo $data;
        ?>
    </div>
</div>