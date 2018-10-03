<?php
class Lrnsetting extends AppModel{
	var $name = 'Lrnsetting';
	var $useTable = "lrn";
	var $primaryKey = "lrn_id";
	
	



		/**
	 * 查询
	 */
	function findlrn(){
		$r= $this->query("select * from lrn  offset 0  limit 1");
		return $r;
	}	
	





}