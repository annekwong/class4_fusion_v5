<?php

require_once 'class.base.php';

/**
 * Class CallDetailReportAsync
 * Created for working with Async CDR Search API
 */
class CallDetailReportAsync extends Base
{
    /**
     * CallDetailReportAsync constructor.
     * Init ip/port to Async CDR Search API
     */
    public function __construct()
    {
        parent::__construct();

        $this->url = Configure::read('cdr_url') . ":" . Configure::read('cdr_api.async_port');
    }

    /**
     * Basic function extended from parent class Base
     * @param $data
     * @return bool
     */
    public function process($data)
    {
        $result = false;

        if ($this->token) {
            $headers = array(
                "Authorization: Token {$this->token}"
            );

            $result = $this->_request($this->url, $data, $headers, 2);

            if ($result) {
                $decodedResult = json_decode($result, true);

                if ($decodedResult['code'] == 200) {
                    App::import('Model', 'CdrApiExportLog');

                    $model = new CdrApiExportLog();
                    $model->save(array(
                        'request_id' => $decodedResult['request_id'],
                        'user_id' => $_SESSION['sst_user_id'],
                        'filename' => date('Y-m-d__H_i_s', $data['start_time']) . '-' . date('Y-m-d__H_i_s', $data['end_time']) . '-search_results',
                        'start_time' => $data['start_time'],
                        'end_time' => $data['end_time'],
                        'type' => 1
                    ));
                }
            }
        }

        return $result;
    }

    /**
     * Check state of export request
     * @param $requestId
     * @return bool
     */
    public function checkStatus($requestId)
    {
        $result = false;

        if ($this->token) {
            $headers = array(
                "Authorization: Token {$this->token}"
            );
            $url = $this->url . DS . $requestId;
            $result = $this->_request($url, array(), $headers);
        }

        return $result;
    }

    /**
     * Download file from URL
     * @param $requestId
     * @return bool
     */
    public function download($requestId)
    {
        if ($this->token) {
            $headers = array(
                "Authorization: Token {$this->token}"
            );
            $url = $this->url . DS . $requestId . DS . 'download';
            $this->_request($url, array(), $headers, 1, true);
        }

        return false;
    }

    /**
     * Request creation from FTP Jobs page
     * @param $request
     * @return bool|string
     */
    public function ftpRequest($request)
    {
        $result = false;

        if ($this->token) {

            if (empty($request['ftp_dir'])) {
                $request['ftp_dir'] = '/';
            }

            if (
                !empty($request['ftp_url']) &&
                !empty($request['ftp_port']) &&
                !empty($request['ftp_user']) &&
                !empty($request['ftp_password'])
            ) {

                if (!isset($request['ftp_file_name'])) {
                    $request['ftp_file_name'] = date('Y-m-d__H_i_s', $request['start_time']) . '-' . date('Y-m-d__H_i_s', $request['end_time']) . '-search_results';
                }
                $request['human_readable'] = 1;

                $headers = array(
                    "Authorization: Token {$this->token}"
                );

                $result = $this->_request($this->url, $request, $headers, 2);

                if ($result) {
                    $decodedResult = json_decode($result, true);

                    if ($decodedResult['code'] == 200) {
                        App::import('Model', 'CdrApiExportLog');

                        $model = new CdrApiExportLog();
                        $model->save(array(
                            'request_id' => $decodedResult['request_id'],
                            'user_id' => $_SESSION['sst_user_id'],
                            'filename' => $request['ftp_file_name'],
                            'start_time' => $request['start_time'],
                            'end_time' => $request['end_time'],
                            'ftp_url' => $request['ftp_url'],
                            'ftp_port' => $request['ftp_port'],
                            'ftp_user' => $request['ftp_user'],
                            'ftp_password' => $request['ftp_password'],
                            'ftp_directory' => $request['ftp_dir'],
                            'type' => 2
                        ));
                    }
                }
            } else {
                $result = json_encode(array(
                    'code' => 101,
                    'msg' => "FTP is not configured. Please configure FTP Settings"
                ));
            }

        }

        return $result;
    }
}