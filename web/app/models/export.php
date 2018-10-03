<?php
class Export extends AppModel
{
    var $useTable = false;
    const LIMIT = 10000;

    private function getArray($sql, $offset)
    {
        $limit_str = " LIMIT ".self::LIMIT." OFFSET ".$offset;
        $result = $this->query($sql.$limit_str);
        $return = array(
            'header' => array(),
            'content' => array()
        );

        if ($result != false && !empty($result)) {
            $header = array_keys($result[0][0]);
            $return['header'] = $header;
            $return['content'] = $result;
        }

        return $return;
    }
    private function getTotal($sql)
    {
        if($sql){
            $sql = <<<SQL
SELECT count(temp.*) from ({$sql}) as temp
SQL;
            $res = $this->query($sql);

            return isset($res[0][0]['count']) ? $res[0][0]['count'] : 0;
        }
        return false;
    }

    public function csv($sql, $filename, $header = true, $delimiter = ',', $headerText = '', $footerText = '', $table = '')
    {
        $count = $this->getTotal($sql);
        if (!$count) {
            $result ['error'] = 1;
            $result ['msg'] = "No data found.";
            return $result;
        }

//        ini_set('memory_limit', '-1');
        $result = array(
            'error' => 0,
            'msg' => 0
        );
        $offset = 0;
        $handle = fopen($filename, 'w+');
        while ($offset < $count){

            $array = $this->getArray($sql, $offset);

            if(!$offset){
                if ($headerText) {
                    fputs($handle, $headerText . "\r\n");
                }

                if ($header) {
                    fputcsv($handle, $array['header'], $delimiter);
                }
            }

            if (!empty($array['content'])) {
                foreach ($array['content'] as $item) {
                    fputcsv($handle, $item[0], $delimiter);
                }
            }
            $offset += count($array['content']);
        }

        if ($footerText) {
            fputs($handle, $footerText . "\r\n");
        }
        fclose($handle);

        return $result;

    }
}