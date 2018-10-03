<style>
    #container input {
        width:100px;
    }
</style>
<div id="title">
 <h1><?php  __('Management')?> &gt;&gt;<?php echo __('Login Log')?></h1>
 <ul id="title-search">
        <form method="get" id="myform1">
                <li>
                <input type="text" id="query-start_date-wDt1" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:00:00'});" class="input in-text" value="<?php echo date('Y-m-d 00:00:00');?>" readonly="readonly" name="start_date" style="width:140px;" />
                         &mdash;&nbsp;
                            <input type="text" id="query-stop_date-wDt1"  onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:00:00'});" class="input in-text" value="<?php echo date('Y-m-d 23:59:59');?>" readonly="readonly"   name="stop_date" style="width:140px;" />
                          in:
                            <select id="tz" name="tz" class="input in-select">
                                <option value="-1200">GMT -12:00</option>
                                <option value="-1100">GMT -11:00</option>
                                <option value="-1000">GMT -10:00</option>
                                <option value="-0900">GMT -09:00</option>
                                <option value="-0800">GMT -08:00</option>
                                <option value="-0700">GMT -07:00</option>
                                <option value="-0600">GMT -06:00</option>
                                <option value="-0500">GMT -05:00</option>
                                <option value="-0400">GMT -04:00</option>
                                <option value="-0300">GMT -03:00</option>
                                <option value="-0200">GMT -02:00</option>
                                <option value="-0100">GMT -01:00</option>
                                <option selected="selected" value="+0000">GMT +00:00</option>
                                <option value="+0100">GMT +01:00</option>
                                <option value="+0200">GMT +02:00</option>
                                <option value="+0300">GMT +03:00</option>
                                <option value="+0330">GMT +03:30</option>
                                <option value="+0400">GMT +04:00</option>
                                <option value="+0500">GMT +05:00</option>
                                <option value="+0600">GMT +06:00</option>
                                <option value="+0700">GMT +07:00</option>
                                <option value="+0800">GMT +08:00</option>
                                <option value="+0900">GMT +09:00</option>
                                <option value="+1000">GMT +10:00</option>
                                <option value="+1100">GMT +11:00</option>
                                <option value="+1200">GMT +12:00</option>
                            </select>
            </li>
            
            <li> 
                <span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;">Login Name:</span>
                <input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="Search" value="Search" name="search">
            </li>
            
            <input type="submit" id="submit" class="search_submit input in-submit" value="" name="submit">
        </form>
     
    </ul>
 
    <!--<ul id="title-menu">
        <li>
        <a id="add" class="link_btn" href="javascript:void(0);" onclick="add();">
            <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png">
            Create New
        </a>
        </li>
    </ul>-->
 
</div>
<?php
    $status = array(
        0=>'',
        1=>'Exchange',
        2=>'Agent',
        3=>'Partition'
    );

?>
<div id="container">
     <?php
        $data = $p->getDataArray();
    ?>
    <div id="toppage"></div>
    <?php
        if(count($data) == 0){
    ?>
        <div class="msg"><?php echo __('no_data_found')?></div>
    <?php    
        }else{
    ?>
    <table class="list" id="key_list" >
        <thead>
           
            <tr>
                <td>Login Name</td>
                <td>Login Time</td>
                <td>Login Ip</td>
                <td>Browser Type</td>
                <td>Account Type</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['login_name'];?></td>
                <td><?php echo $item[0]['login_time'];?></td>
                <td><?php echo $item[0]['login_ip'];?></td>
                <td><?php echo $item[0]['login_browser'];?></td>
                <td><?php echo $status[$item[0]['login_type']];  ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
    <?php }?>
</div>

<script>
   
    
</script>

