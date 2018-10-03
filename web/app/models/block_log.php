<?php
class BlockLog extends AppModel{
	var $name = 'BlockLog';
	var $useTable = 'block_log';
	var $primaryKey = 'log_id';
	
    public function log_count_invalid_number()
        {
            $sql = "SELECT count(*) as sum FROM block_log where type = 1";
            $result = $this->query($sql);
            return $result[0][0]['sum'];
        }
        
        public function log_list_invalid_number($order_sql,$pageSize,$offset)
        {
            $sql = "SELECT *,(select count(ingress_res_id) from resource_block where block_log_id = block_log.log_id  group by ingress_res_id  ) as ingress_count FROM block_log  where type = 1 $order_sql ";
            $sql .= "  limit '$pageSize' offset '$offset'";
            $result = $this->query($sql);
            return $result;
        }
        
        public function log_count()
        {
            $sql = "SELECT count(*) as sum FROM block_log where type =  0 ";
            $result = $this->query($sql);
            return $result[0][0]['sum'];
        }
        
        public function log_list($order_sql,$pageSize,$offset)
        {
            $sql = "SELECT * FROM block_log  where type =  0 $order_sql ";
            $sql .= "  limit '$pageSize' offset '$offset'";
            $result = $this->query($sql);
            return $result;
        }
        
}
?>