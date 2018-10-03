<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>


<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Condition',true);?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Condition',true);?></h4>
    <div class="buttons pull-right">
        <?php if (isset($edit_return)) {?>
            <a class="link_back btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot;?>alerts/condition">
                <i></i>
                &nbsp;<?php echo __('goback')?>
            </a>
        <?php }?>
        <?php  if ($_SESSION['role_menu']['Monitoring']['alerts:condition']['model_w']) {?>
            <a class="link_btn btn btn-primary btn-icon glyphicons circle_plus" title="<?php echo __('createcondition')?>"  href="<?php echo $this->webroot?>alerts/add_condition">
                <i></i><?php echo __('createnew')?>
            </a>
        <?php }?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
    <ul class="tabs">
        <li >
            <a class="glyphicons no-js paperclip" href="<?php echo $this->webroot; ?>alerts/rule">
                <i></i><?php __('Rule'); ?>			
            </a>
        </li>
        <li>
            <a class="glyphicons no-js tag" href="<?php echo $this->webroot; ?>alerts/action">
                <i></i><?php __('Action'); ?>			
            </a>
        </li>
        <li class="active">
            <a class="glyphicons no-js projector" href="<?php echo $this->webroot; ?>alerts/condition">
                <i></i><?php __('Condition'); ?>			
            </a>
        </li>
        <li>
            <a class="glyphicons no-js tint" href="<?php echo $this->webroot; ?>alerts/block_ani">
                <i></i><?php __('Block'); ?>			
            </a>
        </li>
        <li>
            <a class="glyphicons no-js vector_path_all" href="<?php echo $this->webroot; ?>alerts/trouble_tickets">
                <i></i><?php __('Trouble Tickets'); ?>			
            </a>
        </li>
        <li>
            <a class="glyphicons no-js cargo" href="<?php echo $this->webroot; ?>alerts/trouble_tickets_template">
                <i></i><?php __('Trouble Tickets Mail Template'); ?>			
            </a>
        </li>
    </ul> 
        </div>
        <div class="widget-body">
            
             <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search'); ?>:</label>
                        <input type="text" id="search-_q_j" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
                    </div>
                   
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query'); ?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            
<?php 			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
<h2 class="msg center"><?php echo __('no_data_found',true);?></h2>
<?php }else{

?>
<div class="separator bottom row-fluid">
    <div class="pagination pagination-large pagination-right margin-none">
        <?php echo $this->element('page'); ?>
    </div> 
</div>
<table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

<thead>
<tr>
                <th ><?php echo __('Condition Name'); ?></th>
		 <th > <?php echo __('ACD'); ?>  </th>
		 <th > <?php echo __('ASR'); ?>	</th>
		 <th><?php echo __('Margin');?></th>
                 <th > <?php echo __('ABR'); ?>	</th>
                 <th><?php echo __('Occurence Of A specific ANI'); ?></th>
                 <?php
                 /*
		 <th><?php echo __('Combination');?></th> */?>
		 <th style="width:200px;"><?php echo __('OR / AND');?></th>
                 <th><?php echo __('Update By'); ?></th>
                 <th><?php echo __('Update At'); ?></th>
		 <?php  if ($_SESSION['role_menu']['Monitoring']['alerts:condition']['model_w']) {?><th style="width:100px;"> <?php echo __('action',true);?> </th>
         <?php }?>
		</tr>
	</thead>
	<tbody>
		<?php 

		for ($i=0;$i<$loop;$i++){
		?>
		<tr class="row-1">
		  <td align="center">
			    
                <a title=""  class="link_width"  href="<?php echo $this->webroot?>alerts/add_condition/<?php echo $mydata[$i][0]['id']?>">

					<?php echo $mydata[$i][0]['name']?>
				</a>
                
			</td>
		  <td>
		    <?php
				if($mydata[$i][0]['acd_comparator']==0){
					echo "ACD LESS THAN ".$mydata[$i][0]['acd_value_min'].' min';
				}elseif ($mydata[$i][0]['acd_comparator']==1){
					echo  $mydata[$i][0]['acd_value_min']." LESS THAN ACD LESS THAN ".$mydata[$i][0]['acd_value_max'];
				} else {
                                    echo 'Ignore';
                                }
		    ?>
		</td>
		<td>
		    <?php
				if($mydata[$i][0]['asr_comparator']==0){
					echo "ASR <= ".($mydata[$i][0]['asr_value_min'] * 100).'%';
				}elseif ($mydata[$i][0]['asr_comparator']==1){
					echo  ( "ASR BETWEEN " .$mydata[$i][0]['asr_value_min'] * 100)." AND ".($mydata[$i][0]['asr_value_max'] * 100)."%";
				} else {
                                    echo 'Ignore';
                                }
		    ?>
		</td>
		<td>
		    <?php
				if($mydata[$i][0]['margin_comparator']==0){
					echo "Margin <= ".($mydata[$i][0]['margin_value_min'] * 100).'%';
				}elseif ($mydata[$i][0]['margin_comparator']==1){
					echo  ("Margin BETWEEN ".$mydata[$i][0]['margin_value_min'] * 100)." AND ".($mydata[$i][0]['margin_value_max'] * 100)."%";
				} else {
                                    echo 'Ignore';
                                }
		    ?>
		</td>
                <td>
		    <?php
				if($mydata[$i][0]['abr_comparator']==0){
					echo "ABR <= ".($mydata[$i][0]['abr_value_min'] * 100).'%';
				}elseif ($mydata[$i][0]['abr_comparator']==1){
					echo  ("ABR BETWEEN ".$mydata[$i][0]['abr_value_min'] * 100)." AND ".($mydata[$i][0]['abr_value_max'] * 100)."%";
				} else {
                                    echo 'Ignore';
                                }
		    ?>
		</td>
                <td>
		    <?php
				if($mydata[$i][0]['special_ani_comparator']==0){
					echo "ANI >= ".($mydata[$i][0]['special_ani_value'] );
				}elseif ($mydata[$i][0]['special_ani_comparator']==1){
					echo "ANI <= ".($mydata[$i][0]['special_ani_value'] );
				} else {
                                    echo 'Ignore';
                                }
		    ?>
		</td>
		<td>
		<?php 
   if ($mydata[$i][0]['for_all'] == 0)
   		{
   		if($mydata[$i][0]['acd_comparator']==0){
					echo "ACD LESS THAN ".$mydata[$i][0]['acd_value_min'].' min';
				}elseif($mydata[$i][0]['acd_comparator']==1){
					echo  "ACD BETWEEN ".$mydata[$i][0]['acd_value_min']."% AND ".$mydata[$i][0]['acd_value_max'];
				} else {
                                        echo 'Ignore';
                                }
				echo "<br />or<br />";
   		if($mydata[$i][0]['asr_comparator']==0){
					echo "ASR <= ".($mydata[$i][0]['asr_value_min'] * 100).'%';
				}elseif($mydata[$i][0]['asr_comparator']==1){
					echo  ("ASR BETWEEN ".$mydata[$i][0]['asr_value_min'] * 100)."% AND ".($mydata[$i][0]['asr_value_max'] * 100)."%";
				} else {
                                        echo 'Ignore';
                                }
				echo "<br />or<br />";
   		if($mydata[$i][0]['margin_comparator']==0){
					echo "Margin <= ".($mydata[$i][0]['margin_value_min'] * 100).'%';
				}elseif($mydata[$i][0]['margin_comparator']==1){
					echo  ("Margin BETWEEN ".$mydata[$i][0]['margin_value_min'] * 100)."% AND ".($mydata[$i][0]['margin_value_max'] * 100)."%";
				} else {
                                        echo 'Ignore';
                                }
   		}
   		?>
		</td>
                <td>
                    <?php echo $mydata[$i][0]['update_by'] ?>
                </td>
                <td>
                    <?php echo $mydata[$i][0]['update_at'] ?>
                </td>
        <?php  if ($_SESSION['role_menu']['Monitoring']['alerts:condition']['model_w']) {?>
		<td> 
	   <?php	#操作 ?>
	   <a  href="<?php echo $this->webroot?>alerts/add_condition/<?php echo $mydata[$i][0]['id'] ?> " title="Edit"  >
               <i class="icon-edit"></i>
	   </a>	
        <a  href="#" title="Delete" control="<?php echo $mydata[$i][0]['id']?>" class="delete">
           <i class="icon-remove"></i>
        </a>

		</td>
        <?php }?>
		</tr>
			<?php }?>
		</tbody>
		</table>
<div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
            <div class="clearfix"></div>
	</div>
<div>


<?php }?>
</div>
    </div>
</div>

<script type="text/javascript">
$(function() {
    $('.delete').click(function() {
        var $this = $(this);
        var condition_id = $this.attr('control');
        $.ajax({
            url : '<?php echo $this->webroot; ?>alerts/condition_used/' + condition_id,
            type : 'GET',
            dataType : 'json',
            success : function(data) {
                var result = false;
                if(data.length > 0) {
                    result = window.confirm("The Math Condition is being used by the following Rules:\n" + data.join(', ') + "\nDeleting this Match Condition will causes the above rules to be removed.");
                } else {
                    result = window.confirm("Are your sure?");
                }
                if(result) 
                    window.location.href = "<?php echo $this->webroot; ?>alerts/ex_dele_condititon/" + condition_id;
                else
                    return false;
            }
        });
    });
});
</script>