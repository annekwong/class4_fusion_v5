<?php echo $this->element("softswap/title")?>
<div id="container">
	<div id="order_list" class="container">	
		<table style="border: 1px solid rgb(128, 157, 186); height: 14px;" class="list">
			<thead>
				<tr>
    			<td>
    				<a href="javascript:void(0)">
    					<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png">
    				</a>
						<?php echo __('Soft swap Name',true);?>
    			 	<a href="javascript:void(0)">
    			 		<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
    			 	</a>
    			</td>
					<td>
						<a href="javascript:void(0)">  
							<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png">
						</a>
						<?php echo __('Adress',true);?>			
						<a href="javascript:void(0)" >
							<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
						</a>
					</td>
    			<td>
    				<a href="javascript:void(0)">
    					<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png">
    				</a>
     			<?php echo __('Capacity',true);?>   			
     			<a href="javascript:void(0)" onclick="my_sort('balance','desc')">
     				<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
     			</a>
     		</td>
    			<td> 
    				<a href="javascript:void(0)" onclick="my_sort('balance','asc')">
    					<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>
     				<?php echo __('cps',true);?>   			
     				<a href="javascript:void(0)" onclick="my_sort('balance','desc')">
     				<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
     			</a>
     		</td>
  				<td>
       		<a href="javascript:void(0)" onclick="my_sort('client_rate','asc')">
       			<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png">
       		</a>
    			<?php echo __('Total route',true);?>       
    				<a href="javascript:void(0)" onclick="my_sort('client_rate','desc')">
         	<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
         </a>
    			</td>
				  <td class="last"><?php echo __('active',true);?></td>
			</tr>
		 </thead>
			<tbody>
			
			</tbody>
		</table>
	</div>
</div>