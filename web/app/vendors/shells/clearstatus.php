<?php

class ClearstatusShell extends Shell {
	
	var $uses = array('RateTable');
	
	function main() {
		$rate_table_id = (int)$this->args[0];
		$sql = "update rate_table set is_uploading = false where rate_table_id = {$rate_table_id}";
		$this->RateTable->query($sql);
                echo $sql ."\n";
	}
	
}