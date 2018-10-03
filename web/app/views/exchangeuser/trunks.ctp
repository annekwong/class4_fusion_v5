<style>
    #container input {
        width:100px;
    }
</style>
<div id="title">
 <h1><?php  __('Management')?> &gt;&gt;<?php echo __('Agents Egress List')?></h1>
 <ul id="title-search">
        <form method="get" id="myform1">
                <!--<li> 
                    <span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;">Trunk Name:</span>
                    <input type="text" id="search-_q" class="in-input" title="Search" value="Search" name="search">
                </li>
                
                <li> 
                    <span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;">Client Name:</span>
                    <input type="text" id="search-_q" class="in-input"  name="client">
                </li>
                
                
                <li> 
                    <span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;">Agent Name:</span>
                    <input type="text" id="search-_q" class="in-input"  name="agent">
                </li>
                -->
                <li> 
                    <span style="float:left; height:25px; line-height:25px; font-size:14px; color:#EEEEEE; font-weight:bold;">Trunk Name:</span>
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
        0=>'Inactive',
        1=>'Active'
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
                <td>Id</td>
                <td>Trunk Name</td>
                <td>Client Name</td>
                <td>Agent</td>
                <td>Status</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['resource_id'];?></td>
                <td><?php echo $item[0]['alias'];?></td>
                <td><?php echo $item[0]['client_name'];?></td>
                <td><?php echo empty($item[0]['agent_name'])?$default_agent_name:$item[0]['agent_name'];?></td>
                <td><?php echo $item[0]['active'] == 't'?"Active":"Inactive";  ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
    <?php }?>
</div>

<script>
   
    
</script>

