 <div id="s-rate_tables_orig_host">  
            <div style="padding:0px 2px 3px 6px;">
      <label for="s-rate_tables-all">  <?php echo __('Orig Host',true);?></label></div>
            <div class="cb_select input">
            
            <?php
           
            foreach ($all_host  as $key=>$value){
            
            	?>
           <div><input type="checkbox"    name="query[supp_orig_host][]" value="<?php echo  $key?>"   
           <?php if(!empty($_GET['query']['supp_orig_host'])){ if(in_array($key,$_GET['query']['supp_orig_host'])){   echo  "checked=checked"; } }?>
           id="cb_rt_<?php echo $key?>" class="input in-checkbox"> 
           <label for="cb_rt_<?php echo $key?>"><?php echo  $value?></label></div>
                                <?php }?>
                          
                            </div>
        </div>