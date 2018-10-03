<?php

class DidPortalController extends DidAppController
{

    var $name = 'DidPortal';
    var $uses = array('did.OrigLog','did.Did','ProductItems');
    var $helpers = array('javascript', 'html', 'Common');

    function beforeFilter()
    {
        $this->checkSession("login_type"); //核查用户身份
        if ($this->Session->read('login_type') != 3)
            $this->redirect("/homes/logout");
        parent::beforeFilter();
    }

    public function index()
    {
        $this->redirect('my_did');
    }

    public function my_did()
    {
        $pageSize = isset($this->params['url']['size']) ? $this->params['url']['size'] : 100;
        $this->paginate = array(
            'limit' => $pageSize,
            'fields' => array('ProductItems.item_id','ProductItems.digits','ProductItems.update_at','Rate.country','Rate.code_name'),
            'order' => array(
                'ProductItems.digits' => 'asc',
            ),
            'joins' => array(
                array(
                    'table' => 'product_items_resource',
                    'alias' => "ProductItemsResource",
                    'type' => 'INNER',
                    'conditions' => array(
                        'ProductItemsResource.item_id = ProductItems.item_id',
                    ),
                ),
                array(
                    'table' => 'rate',
                    'alias' => "Rate",
                    'type' => 'INNER',
                    'conditions' => array(
                        'ProductItems.digits = Rate.code',
                    ),
                ),
            ),
            'conditions' => array(
                'ProductItemsResource.resource_id' => $this->Session->read('carrier_panel.Resource.0.resource_id'),
                'Rate.did_type' => 2,
            ),
        );

        $this->data = $this->paginate('ProductItems');
//        pr($this->data);die;
    }

}
