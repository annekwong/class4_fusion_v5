 <div id="s-rate_tables_ingress" >
            <div style="padding:0px 2px 3px 6px;">
           <label for="s-rate_tables-all">  <?php echo __('Ingress',true);?></label></div>
            <div class="cb_select input">
            
            <?php

           
            foreach ($ingress  as $key=>$value){
            
            	?>
           <div><input type="checkbox"    name="query[supp_ingress][]" value="<?php echo  $key?>"   
           <?php if(!empty($_GET['query']['supp_ingress'])){ if(in_array($key,$_GET['query']['supp_ingress'])){   echo  "checked=checked"; } }?>
           id="cb_rt_<?php echo $key?>" class="input in-checkbox"> 
           <label for="cb_rt_<?php echo $key?>"><?php echo  $value?></label></div>
             <?php }?>
                          
                            </div>
        </div>