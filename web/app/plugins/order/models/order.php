<?php
class Order extends OrderAppModel {
	var $useTable = false;
	const STATUS_READY = 0;
	const STATUS_CONTRACT = 1;
	const STATUS_HOLD = 2;
	const STATUS_SOLDOUT = 3;
	const STATUS_EXPIRED = 4;
	
	
}
