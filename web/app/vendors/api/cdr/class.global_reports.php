<?php

require_once 'class.base.php';

class GlobalReports extends Base
{
    public function __construct()
    {
        parent::__construct();
        Configure::load('myconf');
        $this->url = Configure::read('cdr_url') . ":" . Configure::read('cdr_api.agg_port');
    }

    public function process($data, $useTime = false) {
        $result = array(
            'code' => 200,
            'msg' => ''
        );

        if ($this->token) {
            $headers = array(
                "Authorization: Token {$this->token}"
            );

            if ($data['fields']) {
                $fields = array();

                foreach ($data['fields'] as $field => $method) {
                    array_push($fields, $field);
                }
                unset($data['fields']);
                unset($data['format']);
                $data['method'] = 'total';
                $data['field'] = implode(',', $fields);
                $temp = $this->_request($this->url, $data, $headers);
                $temp = json_decode($temp, true);

                if ($temp['code'] == 200) {
                    $result = $temp['data'];
                    unset($temp);

                    if (!$useTime) {
                        foreach ($result as $key => $item) {
                            unset($result[$key]['time']);
                        }
                    }
                    if (empty($result[count($result) - 1])) {
                        unset($result[count($result) - 1]);
                    }
                    $result = array(
                        'code' => 200,
                        'data' => $result,
                        'msg' => ''
                    );
                } else {
                    $result = $temp;
                }
            }
        } else {
            $result['code'] = 100;
            $result['msg'] = 'Can\'t establish connection';
        }

        return $result;
    }
}