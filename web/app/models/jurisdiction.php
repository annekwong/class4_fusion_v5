<?php
	class Jurisdiction extends AppModel {
		var $name = 'Jurisdiction';
		var $useTable = 'jurisdiction';
		var $primaryKey = 'id';
	
		function check_name($id, $name) {
		
		$name = "'" . $name . "'";
		empty ( $id ) ? $sql = "select count(*) from jurisdiction where name=$name " : $sql = "select count(*) from jurisdiction where name=$name  and id<>$id";
		$c = $this->query ( $sql );
		if (empty ( $c )) {
			return 0;
		} else {
			return $c [0] [0] ['count'];
		}
	}
	
	
	
	
function check_alias($id, $alias) {
		$alias = "'" . $alias . "'";
		empty ( $id ) ? $sql = "select count(*) from jurisdiction where alias=$alias " : $sql = "select count(*) from jurisdiction where alias=$alias  and id<>$id";
		$c = $this->query ($sql);
		if (empty ( $c )) {
			return 0;
		} else {
			return $c [0] [0] ['count'];
		}
	}
	}
?>