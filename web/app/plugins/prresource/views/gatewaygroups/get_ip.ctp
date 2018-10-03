<select name="data[sip_ip][]">
    <option value="any">Any</option>
    <option value="none">None</option>
    <?php
        foreach($ips as $ip){
            if($ip[0]['id'] == $ip_id){
    ?>
    <option selected value="<?php echo $ip[0]['sip_ip'].":".$ip[0]['sip_port']?>"><?php echo $ip[0]['sip_ip'].":".$ip[0]['sip_port']?></option>
    <?php            
            }else{
    ?>
    <option value="<?php echo $ip[0]['sip_ip'].":".$ip[0]['sip_port']?>"><?php echo $ip[0]['sip_ip'].":".$ip[0]['sip_port']?></option>
    <?php        
        }
        }
    ?>
</select>