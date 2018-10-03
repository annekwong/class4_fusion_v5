<style type="text/css">
#shell_window { 
    overflow-y: auto;
    height:500px;
}
</style>

<div id="title">
    <h1><?php echo __('Tools'); ?> &gt;&gt; <?php echo __('Call Monitor'); ?></h1>
</div>

<div id="container">
    <div id="shell_window">
        <?php
            echo str_replace("\n", "<br />", $result);
        ?>
    </div>
</div>