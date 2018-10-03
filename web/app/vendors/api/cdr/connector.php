<?php

require_once 'class.base.php';

class ApiConnector extends Base
{
    private $connected = false;

    public function __construct($url = false)
    {
        parent::__construct();

        $authData = base64_encode($_SESSION['sst_user_name'] . '_' . $_SESSION['sst_password']);
        $headers = array(
            "Authorization: Basic {$authData}"
        );

        $this->connected = $this->_request($url, array(), $headers, 2) ? true : false;
    }

    public function process($data, $useTime = false)
    {

    }

    public function isConnected()
    {
        return $this->connected;
    }
}