<?php

require_once 'class.base.php';

/**
 * Class CallDetailReport
 * Created for getting CDR from API.
 */
class CallDetailReport extends Base
{
    /**
     * CallDetailReport constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->url = Configure::read('cdr_url') . ":" . Configure::read('cdr_api.sync_port');
    }

    /**
     * Getting data
     * @param $data
     * @return bool
     */
    public function process($data) {
        $result = false;

        if ($this->token) {
            $headers = array(
                "Authorization: Token {$this->token}"
            );
            $result = $this->_request($this->url, $data, $headers);
        }

        return $result;
    }
}