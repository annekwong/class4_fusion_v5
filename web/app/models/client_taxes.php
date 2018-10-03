<?php
class ClientTaxes extends AppModel {
    var $name = 'ClientTaxes';
    var $useTable = 'client_taxes';
    var $primaryKey = 'id';

    public function findByClientId($clientId) {
        $result = $this->find('all', array(
            'conditions' => array(
                'client_id' => $clientId
            ),
            'order' => array('id')
        ));

        return $result;
    }
}