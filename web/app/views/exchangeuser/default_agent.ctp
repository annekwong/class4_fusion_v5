<style>
  
</style>
<div id="title">
 <h1><?php  __('Management')?> &gt;&gt;<?php echo __('Default Agent')?></h1>
   
 
</div>
<?php
    
   
?>
<div id="container">
    
    <div id="toppage"></div>
    
    <form style="text-align:center;" method="post">
        <table id="key_list" style="width:60%;margin: auto;" >
                <tr>
                <td>Name</td>
                <td><input type="text" name="name" value="<?php if(!empty($_POST['name'])){echo $_POST['name']; }else{ echo $default['name'];}?>" ></td>
                <td>Email</td>
                <td><input type="text" name="email" value="<?php if(!empty($_POST['email'])){echo $_POST['email']; }else{ echo $default['email'];}?>" ></td>
                </tr>

                <tr>
                <td>Phone Number</td>
                <td><input type="text" name="phone_number" value="<?php if(!empty($_POST['phone_number'])){echo $_POST['phone_number']; }else{ echo $default['phone_number'];}?>" ></td>
                <td>Domain Name</td>
                <td><input type="text" name="domain_name" value="<?php if(!empty($_POST['domain_name'])){echo $_POST['domain_name']; }else{ echo $default['domain_name'];}?>" ></td>
                </tr>
                
                <tr>
                <td>Company</td>
                <td><input type="text" name="company_name" value="<?php if(!empty($_POST['company_name'])){echo $_POST['company_name']; }else{ echo $default['company_name'];}?>" ></td>
                <td>Ip</td>
                <td><input type="text" name="ip" value="<?php if(!empty($_POST['ip'])){echo $_POST['ip']; }else{ echo $default['ip'];}?>" ></td>
                </tr>

        </table>

        <input type="submit" value="Submit" >
    </form>
</div>

<script>
    
   
</script>

