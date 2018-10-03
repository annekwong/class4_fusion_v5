 <div id="s-rate_tables">
           
            <div class="cb_select input">
            
            <?php

           
            foreach ($all_rate_table  as $key=>$value){
            
            	?>
           <div><input type="checkbox"    name="query[rate_tables][]" value="<?php echo  $key?>"   
           
           <?php if(!empty($_GET['query']['rate_tables'])){ if(in_array($key,$_GET['query']['rate_tables'])){   echo  "checked=checked"; } }?>
           
           
           id="cb_rt_<?php echo $key?>" class="input in-checkbox"> 
           <label for="cb_rt_<?php echo $key?>"><?php echo  $value?></label></div>
                                    
                          
                          <?php }?>
                          
                            </div>
        </div>

<script type="text/javascript">
$(function() {
    $('input[name="query[rate_tables][]"]').click(
        function() {
            var temp = 0;
            $('input[name="query[rate_tables][]"]').each(function(index) {
                if($(this).attr('checked')) {
                    temp++;
                }
            });
            if(temp > 5) {
                return false;
            }
        }
    );
});
</script>