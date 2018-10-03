<?php
class RegistrationLog extends AppModel{
	var $name = 'RegistrationLog';
	var $useTable = 'register_of_record';
	var $primaryKey = 'id';
        
        public function totalrecords($search_sql = "")
        {
            $sql = "SELECT count(*) as sum FROM register_of_record $search_sql";
            $data = $this->query($sql);
            return $data[0][0]['sum'];
        }
        
        
        
}