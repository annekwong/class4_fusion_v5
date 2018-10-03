<?php

require_once 'class.base.php';

class ScheduledSearch extends Base
{
    public function __construct()
    {
        parent::__construct();

        $configUrl = Configure::read('cdr_url');
        $this->url = $configUrl . ":8892";
    }

    public function process($data) {
        $result = false;
        if ($this->token) {
            $headers = array(
                "Authorization: Token {$this->token}"
            );
            $result = $this->_request($this->url, $data, $headers, 1);
        }

        return $result;
    }
}