<?php

class EnddateShell extends Shell {
	
	var $uses = array('Rate');
	
	function main() {
            
		$rate_table_id = (int)$this->args[0];
		$end_date      = $this->args[1];
		
		$sql = "update rate set end_date = '{$end_date}' where rate_table_id = {$rate_table_id} and (effective_date <= NOW() and (end_date is null or end_date >= NOW())) ";
		
		$this->Rate->query($sql);
	}
	
}