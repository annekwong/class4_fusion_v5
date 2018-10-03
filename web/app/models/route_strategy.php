<?php
class RouteStrategy extends AppModel {
	var $name = 'RouteStrategy';
	var $useTable = 'route_strategy';
	var $primaryKey = 'route_strategy_id';
	var $order = "route_strategy_id DESC";
	
	function find_all_valid(){
		return $this->findAll();
	}

	public function getOriginStaticRoute()
    {
        $result = $this->find('first', array(
            'fields' => array('route_strategy_id'),
            'conditions' => array(
                'name' => 'ORIGINATION_ROUTING_PLAN'
            )
        ));

        if (empty($result)) {
            $saveResult = $this->save(array(
                'name' => 'ORIGINATION_ROUTING_PLAN'
            ));

            if ($saveResult == false) {
                $result = false;
            } else {
                $result = $this->getLastInsertId();
            }
        }

        return $result;
    }
}
?>