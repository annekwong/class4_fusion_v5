<?php
class ApiLog extends AppModel
{
    var $useTable = 'api_log';
    var $primaryKey = 'id';

    private function checkHandleFile($path)
    {
        if (!file_exists($path)) {
            fopen($path, 'w');
        }
        if (!is_writable($path)) {
//            chmod($path, 0777);
        }
    }

    private function getPath($type)
    {
        $path = APP . 'log' . DS;

        switch ($type) {
            case 1:
                $path .= 'pcap.txt';
                break;
            case 2:
                $path .= 'invoice.txt';
                break;
            case 3:
                $path .= 'dashboard.txt';
                break;
            case 4:
                $path .= 'paypal.txt';
                break;
            case 5:
                $path .= 'import.txt';
                break;
            case 6:
                $path .= 'fast_cdr_api.txt';
                break;
        }

        $this->checkHandleFile($path);

        return $path;
    }

    public function write($string, $type)
    {
        $path = $this->getPath($type);
        $content = "#----" . date('Y-m-d H:i:s') . "----#\r\n\r\n";
        $content .= $string;
        $content .= "\r\n\r\n";
        
        if (is_writable($path)) {
            file_put_contents($path, $content, FILE_APPEND);
        }
    }

    public function addRequest($url, $data = array(), $headers = array(), $method = 1, $responseCode)
    {
        $this->create();

        if ($method == 1 && !empty($data)) {
            $url .= "?" . http_build_query($data);
        }

        $apiLogRequest = "";

        if ($method == 2) {
            $apiLogRequest .= "-X POST ";

            if (!empty($data)) {
                $encodedData = json_encode($data);
                $apiLogRequest .= "-d '{$encodedData}' ";
            }
        }
        if (!empty($headers)) {
            $implodedHeaders = implode(';', $headers);
            $apiLogRequest .= "-H '{$implodedHeaders}' ";
        }
        $apiLogRequest .= "'{$url}'";

        return $this->save(array(
            'time' => time(),
            'request' => $apiLogRequest,
            'status' => intval($responseCode)
        ), false);
    }
}