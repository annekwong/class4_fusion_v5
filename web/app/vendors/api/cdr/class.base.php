<?php

/**
 * Class Base
 * Abstract class for working with CDR Search Engine.
 */
abstract class Base
{
    /**
     * Token to getting access to API methods. Contains token - if all is ok, FALSE - if there is a problem to getting token.
     * @var mixed
     */
    protected $token;

    /**
     * Contains url for API method.
     * @var string
     */
    protected $url;

    /**
     * Object of class ApiLog.
     * @var ApiLog
     */
    protected $ApiLog;

    /**
     * API url for getting token.
     * @var
     */
    private $cdrUrl;

    /**
     * Base constructor.
     */
    public function __construct()
    {
        App::import('Model', 'ApiLog');
        Configure::load('myconf');

        $url = Configure::read('cdr_url');
        $port = Configure::read('cdr_api.auth_port');

        $this->ApiLog = new ApiLog;
        $this->cdrUrl = $url . ":" . $port;
        $this->token = $this->_getToken();
    }

    /**
     * Function which contains main code to getting/sending data.
     * @param $data
     * @return mixed
     */
    abstract public function process($data);

    /**
     * Getting token
     * @return mixed
     */
    private function _getToken()
    {
        $token = false;

        if ($this->_checkToken()) {
            $token = $_SESSION['cdr_token'];
        } else {
            $url = $this->cdrUrl;
            $res = $this->_request($url, array(), array(), 2);

            if ($res !== false) {
                $res = json_decode($res, true);
            }

            if (isset($res['code']) && $res['code'] == 200) {
                $token =  $res['token'];
                $_SESSION['cdr_token'] = $token;
            }
        }

        return $token;
    }

    /**
     * Check existing token and expiration time
     * @return bool
     */
    private function _checkToken()
    {
        $result = false;

        if (isset($_SESSION['cdr_token'])) {
            $url = "{$this->cdrUrl}/{$_SESSION['cdr_token']}";

            $res = $this->_request($url, array(), array(), 2);

            if ($res !== false) {
                $res = json_decode($res, true);
            }

            if (isset($res['code']) && $res['code'] == 200) {
                $result = date('Y-m-d H:i:s', $res['expiration_time']) > date('Y-m-d H:i:s');
            }
        }

        return $result;
    }

    /**
     * Send CURL request
     * @param $url
     * @param array $data
     * @param array $headers
     * @param int $method - (1 - GET, 2 - POST)
     * @param boolean $redirect
     * @return mixed
     */
    protected function _request($url, $data = array(), $headers = array(), $method = 1, $redirect = false)
    {
        $baseUrl = $url;

        if ($method == 1 && !empty($data)) {
            $url .= "?" . http_build_query($data);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($redirect) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        } else {
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        }
        if ($method == 2) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $res = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->ApiLog->addRequest($baseUrl, $data, $headers, $method, $httpCode);

        return $res;
    }

    /**
     * Writes requests/responses to log file.
     * @param $array
     */
    protected function writeLog($array)
    {
        $type = $array['type'] == 1 ? 'Request' : 'Response';
        $url = $array['url'];
        $dataJson = $array['data'];
        $this->ApiLog->write($type . ': ' . json_encode($dataJson) . $url, 6);
    }
}