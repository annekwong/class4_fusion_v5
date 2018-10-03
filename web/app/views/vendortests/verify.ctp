<style type="text/css">
#key {
    color:red;
    font-weight:bold;
    font-size:14px;
}
</style>

<div id="title">
    <h1><?php __('Tools')?>&gt;&gt;<?php echo __('Vendor Test',true);?></h1>
</div>

<div class="container">
    <form action="###" method="post" id="myform" name="myform">
        <?php __('Please input your test engineering activation key')?>:
        <input type="text" name="vendor_key" />
        <input type="submit" value="<?php echo __('submit',true);?>" />
        <span><?php echo $error; ?></span>
    </form>
    <div id="generate">
    <?php __('Or you would create a new one')?>：
    <input type="button" name="generatebtn" id="generatebtn" value="Generate key" style="width:200px;" />
    </div>
</div>

<script type="text/javascript">
$(function() {
   $('#generatebtn').click(function() {
        window.location = "<?php echo $this->webroot ?>vendortests/generatekey";
    });
});
</script>