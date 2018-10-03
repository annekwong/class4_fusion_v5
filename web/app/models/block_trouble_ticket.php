<?php
class BlockTroubleTicket extends AppModel{
//	var $name = 'BlockTroubleTicket';
	var $useTable = false;
//	var $primaryKey = 'id';
	
	public function data_count()
        {
//            $sql = "SELECT count(*) as sum FROM block_trouble_ticket";
//            $result = $this->query($sql);
//            return $result[0][0]['sum'];
        }
        
        public function data_list($order_sql,$pageSize,$offset)
        {
//            $sql = "SELECT * FROM block_trouble_ticket $order_sql";
//            $sql .= "  limit '$pageSize' offset '$offset'";
//            $result = $this->query($sql);
//            return $result;
        }
	
        
}
?>