<style>
  
</style>
<div id="title">
 <h1><?php  __('Management')?> &gt;&gt;<?php echo __('Partition System Parameter')?></h1>
   
 
</div>
<?php
    
   
?>
<div id="container">
    
    <div id="toppage"></div>
    
    <form style="text-align:center;" method="post">
        <table id="key_list" style="width:30%;margin: auto;" >
                <tr>
                <td><?php __('USD/Month')?>:</td>
                <td><input type="text" name="ip_month_money" value="<?php if(!empty($_POST['ip_month_money'])){echo $_POST['ip_month_money']; }else{ echo $data['ip_month_money'];}?>" ></td>
                </tr>
        </table>
        <input type="submit" value="<?php __('Submit')?>" >
    </form>
</div>

<script>
    
   
</script>

