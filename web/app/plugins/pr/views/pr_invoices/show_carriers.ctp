<style type="text/css">
    #select_all,#inverse{
        float:right; 
        margin-right:10px; 
        text-align: center; 
        padding-top:5px ;  
        width: 80px; 
        font-size: 14px;
        font-weight: 600; 
        height: 26px; 
        cursor: pointer; 
        color: #FFFFFF; 
        background-image:-moz-linear-gradient(center top , #FF2709, #B50400);
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#FF2709), to(#B50400));
        background-image: -webkit-linear-gradient(center top, #FF2709, #B50400);
        background-image: -o-linear-gradient(center top, #FF2709, #B50400);
        background-image: linear-gradient(to bottom, #FF2709, #B50400);
        border-radius: 5px;
    }  
</style>
<div class="dialog_form">
    <div style="height: 20px;"></div>
    <input type="text" id="search_name" class="in-text input in-input" style="margin-left: 8px;" value="<?php echo $name; ?>">
    <span id="select_all" style="display:bolck;"><?php __('Select All')?></span>
    <span id="inverse" style="display:bolck;"><?php __('Inverse')?></span>
<table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary footable-loaded default">
    <thead>
        <tr>
            <th style="background-color: rgb(129,176,0);"><?php __('Name')?></th>
            <th style="background-color: rgb(129,176,0);"><?php __('Active')?></th>
            <th style="background-color: rgb(129,176,0);"><?php __('Action')?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($clients as $client): ?>
        <tr>
            <td><?php echo $client[0]['name'] ?></td>
            <td><?php echo $client[0]['status'] == 1 ? 'Yes' : 'No' ?></td>
            <td>
<!--                <a class="assign_client" client_id="<?php echo $client[0]['client_id'] ?>" client_name="<?php echo $client[0]['name'] ?>" href="###">
                    <img src="<?php echo $this->webroot ?>images/add.png">
                </a>-->
                <input type="checkbox" name="check_single" class="assign_client" client_id="<?php echo $client[0]['client_id'] ?>" client_name="<?php echo $client[0]['name'] ?>" >
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>