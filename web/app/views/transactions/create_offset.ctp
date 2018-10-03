<?php echo $this->element('magic_css_three');?>
<div id="title">
  <h1>
    <?php  __('Finance')?>
    &gt;&gt;
    <?php __('Offset')?>
  </h1>
  <?php  $action=isset($_SESSION['sst_statis_smslog'])?$_SESSION['sst_statis_smslog']:'';
    $w=isset($action['writable'])?$action['writable']:'';?>
</div>

<style type="text/css">
.invoce_control {display:none;}
.form .label2{width:130px;}
.form .value2{width:auto;}
</style>

<div id="container">
    <div style="text-align:center;">
    <form method="post" name="myform" id="myform">
        <?php __('Carrier')?>:
        <select name="client">
            <?php foreach($clients as $client): ?>
            <option value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
            <?php endforeach; ?>
        </select>
        <?php __('Offset Value')?>:
        <input type="text" name="amount" />
        <input type="submit" value="<?php __('Submit')?>" />
    </form>
    </div>
</div>

<script type="text/javascript">
$(function() {
    
});
</script>