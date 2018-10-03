<style type="text/css">
#key {
    color:red;
    font-weight:bold;
    font-size:16px;
}
</style>
<div id="title">
    <h1><?php __('Tools')?>&gt;&gt;<?php echo __('Vendor Test',true);?></h1>
</div>

<div class="container">
    <p style="text-align:center;padding:20px;">
        <?php __('Your activation key is')?>:<span id="key"><?php echo $key; ?></span> . <?php __('We strongly adviese you to keep it for next usage')?>!
        <br />
        <br />
        <input type="button" id="login" value="login" />
    </p>
</div>

<script type="text/javascript">
$(function() {
   $('#login').click(function() {
        window.location = "<?php echo $this->webroot ?>vendortests/index";
    });
});
</script>