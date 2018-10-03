<?php

class ResourcePrefix extends AppModel
{

    var $name = 'ResourcePrefix';
    var $useTable = 'resource_prefix';
    var $primaryKey = 'id';

    public function get_list_by_resource($resource_id)
    {
        return $this->find('all',array(
            'fields' => 'ResourcePrefix.tech_prefix,RateTable.name,RouteStrategy.name,ProductRouteRateTable.product_name,
            RateTable.rate_table_id,ProductRouteRateTable.id,ResourcePrefix.id,Resource.client_id,Client.name,Client.rate_email',
            'joins' => array(
                array(
                    'table' => 'rate_table',
                    'alias' => 'RateTable',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'RateTable.rate_table_id = ResourcePrefix.rate_table_id',
                    )
                ),
                array(
                    'table' => 'route_strategy',
                    'alias' => 'RouteStrategy',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'RouteStrategy.route_strategy_id = ResourcePrefix.route_strategy_id',
                    )
                ),
                array(
                    'table' => 'product_route_rate_table',
                    'alias' => 'ProductRouteRateTable',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'ProductRouteRateTable.id = ResourcePrefix.product_id',
                    )
                ),
                array(
                    'table' => 'resource',
                    'alias' => 'Resource',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Resource.resource_id = ResourcePrefix.resource_id',
                    )
                ),
                array(
                    'table' => 'client',
                    'alias' => 'Client',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Client.client_id = Resource.client_id',
                    )
                )
            ),
            'order' => array(
                //'id' => 'desc',
            ),
            'conditions' => array('ResourcePrefix.resource_id' => $resource_id),
        ));
    }

    //
    public function get_all_product_and_rate_table($resource_id_list){
        return  $this->find('all',array(
            'fields' => 'ResourcePrefix.resource_id,ResourcePrefix.tech_prefix,RateTable.name,ProductRouteRateTable.product_name,
            RateTable.rate_table_id,ProductRouteRateTable.id,ResourcePrefix.id',
            'joins' => array(
                array(
                    'table' => 'rate_table',
                    'alias' => 'RateTable',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'RateTable.rate_table_id = ResourcePrefix.rate_table_id',
                    )
                ),
                array(
                    'table' => 'product_route_rate_table',
                    'alias' => 'ProductRouteRateTable',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'ProductRouteRateTable.id = ResourcePrefix.product_id',
                    )
                )
            ),

            'conditions' => array('resource_id' => $resource_id_list),
        ));
    }

}
