<ul class="tabs">
<li><a href="<?php echo $this->webroot ?>codedecks/codes_list/<?php echo $id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/list.png"> Code Deck <?php echo __('List',true);?></a></li>      
<!--<li><a href="<?php echo $this->webroot ?>codedecks/add_code/<?php echo $id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/add.png"> Add Code</a></li>-->
<li><a href="<?php echo $this->webroot ?>uploads/code_deck/<?php echo $id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/import.png"> <?php echo __('import',true);?></a></li>
<li class="active"><a href="<?php echo $this->webroot ?>downloads/code_deck/<?php echo $id?>"><img width="16" height="16" src="<?php echo $this->webroot ?>images/export.png"><?php echo __('export',true);?></a></li>
</ul>