<?php
class GlobalFailover extends AppModel {
	var $name = 'GlobalFailover';
	var $useTable = 'global_failover';
	var $primaryKey = 'id';
	var $order = "id DESC";
}