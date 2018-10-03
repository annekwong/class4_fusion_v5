<div id="title">
  <h1>
    <?php __('Configuration')?>
    &gt;&gt;
    <?php echo __('Email SMTP Test')?>
  </h1>
</div>

<div id="container">
    <div>
        <?php echo $info; ?>
    </div>
    <div style="text-align:center;">
        <form method="post" id="myform1" name="myform1">
        <input type="text" name="email" />
        <input type="submit" value="<?php __('Testting')?>" />
        </form>
    </div>
</div>