<?php

App::import("Controller", "AppController");
App::import("Model", "Cdr");

class CommonHelper extends AppHelper {
    
    function set_get_value($field = '', $default = '')
    {
        if ( ! isset($_GET[$field]))
        {
                return $default;
        }
        return $_GET[$field];
    }
    
    function getSymbolByQuantity($bytes) {
        $symbols = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
        $exp = floor(log($bytes)/log(1024));

        return sprintf('%.2f '.$symbols[$exp], ($bytes/pow(1024, floor($exp))));
    }
    
    function set_get_select($field = '', $value = '', $default = FALSE)
    {

        if ( ! isset($_GET[$field]))
        {
                if (count($_GET) === 1 AND $default == TRUE)
                {
                        return ' selected="selected"';
                }
                return '';
        }

        $field = $_GET[$field];

        if (is_array($field))
        {
                if ( ! in_array($value, $field))
                {
                        return '';
                }
        }
        else
        {
                if (($field == '' or $value == '') or ($field != $value))
                {
                        return '';
                }
        }

        return ' selected="selected"';

    }
    
    function set_get_select_mul($field = '', $value = '', $key='', $default = FALSE)
    {

        if ( ! isset($_GET[$field][$key]))
        {
                if (count($_GET) === 1 AND $default == TRUE)
                {
                        return ' selected="selected"';
                }
                return '';
        }

        $field = $_GET[$field][$key];
        if (is_array($field))
        {
                if ( ! in_array($value, $field))
                {
                        return '';
                }
        }
        else
        {
                if (($field == '' or $value == '') or ($field != $value))
                {
                        return '';
                }
        }

        return ' selected="selected"';

    }

    function array_avg($array,$precision=2)
    {
        if(!is_array($array))
            return 'ERROR in function array_avg(): this is a not array';

        foreach($array as $value)
            if(!is_numeric($value))
                return 'ERROR in function array_avg(): the array contains one or more non-numeric values';

        $cuantos=count($array);
        return round(array_sum($array)/$cuantos,$precision);
    }
    
    public function get_trunk_count($type, $resource_id, $ip, $port) {
        $content = "";
        $cmd = "get_trunk_limit {$resource_id}";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if(@socket_connect($socket, $ip, $port)) {
                socket_write($socket, $cmd, strlen($cmd));
        }else {
            socket_close($socket);
            return 'error';
        }
        while ($out = socket_read($socket, 2048)) {
            $content .= $out;
            if(strpos($out, "~!@#$%^&*()") !== FALSE) {
                break;
            }
            unset($out);
        }
        $content = strstr($content, "~!@#$%^&*()", TRUE);
        socket_close($socket);
        $content_arr = explode("\n", $content);
        if($type == 'ingress') {
            $line = explode("=", $content_arr[3]);
        } elseif ($type == 'egress') {
            $line = explode("=", $content_arr[4]);
        }
        return trim($line[1]);
    }
    
    public function get_trunk_ip_count($type, $ip_id, $ip, $port) {
        $content = "";
        $cmd = "get_host_limit {$ip_id}";
        $socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
        if(@socket_connect($socket, $ip, $port)) {
                socket_write($socket, $cmd, strlen($cmd));
        } else {
            socket_close($socket);
            return array(0, 0);
        }
        while ($out = socket_read($socket, 2048)) {
            $content .= $out;
            if(strpos($out, "~!@#$%^&*()") !== FALSE) {
                break;
            }
            unset($out);
        }
        $content = strstr($content, "~!@#$%^&*()", TRUE);
        socket_close($socket);
        $content_arr = explode("\n", $content);
        if($type == 'ingress') {
            $line[] = array_pop(explode('=', $content_arr[3]));
            $line[] = array_pop(explode("=", $content_arr[7]));
        } elseif ($type == 'egress') {
            $line[] = array_pop(explode('=', $content_arr[4]));
            $line[] = array_pop(explode("=", $content_arr[8]));
        }
        return $line;
    }
    
    public function total_mutual_ingress_balance($invoice_sent, $payment_received, $credit_note_sent, $debit_note_sent, $reset, &$balance) {
        if($reset !== NULL)
            $balance = $reset;
        else
            $balance = -$invoice_sent + $payment_received + $credit_note_sent - $debit_note_sent + $balance;
        return $balance;
    }
    
    public function total_mutual_egress_balance($invoice_received, $payment_sent, $credit_note_received, $debit_note_received, $reset, &$balance) {
        if($reset !== NULL)
            $balance = $reset;
        else
            $balance = $invoice_received - $payment_sent -$credit_note_received + $debit_note_received + $balance;
        return $balance;
    }
    
    public function total_actual_ingress_balance($payment_received, $credit_note_sent, $debit_note_sent, $incoming_traffic, $reset, $short_charges,&$balance) {
        if($reset !== NULL)
            $balance = $reset;
        else
            $balance = $payment_received + $credit_note_sent - $debit_note_sent - $incoming_traffic - $short_charges + $balance;
        return $balance;
    }
    
    public function total_actual_egress_balance($payment_sent, $credit_note_received, $debit_note_received, $outgoing_traffic, $reset, &$balance) {
        
        if($reset !== NULL)
            $balance = $reset;
        else
            $balance = -$payment_sent - $credit_note_received + $debit_note_received + $outgoing_traffic + $balance;
        return $balance;
    }
    /*
     * total_client_balance($temp1, $temp2, $temp3, $temp4, $temp5, $temp6, $temp7, $temp8, $balance)
     */
    public function total_client_balance($payment_received, $credit_note_sent, $debit_note_sent, $incoming_traffic, $payment_sent, $credit_note_receiced,
            $debit_note_received, $outgoing_traffic, &$balance) {
        $balance = $payment_received + $credit_note_sent - $debit_note_sent - $incoming_traffic - $payment_sent - $credit_note_receiced + $debit_note_received
                         + $outgoing_traffic + $balance;
        return $balance;
    }
    
    public function total_exchange_client_balance($deposit, $withdraw, $ingress, $egress, &$balance) {
        $balance = $deposit - $withdraw + $egress - $ingress + $balance;
        return $balance;
    }
    
    
    public function total_balance_for_actual($val, $type, &$balance) {
        switch((int)$type) {
            case 1:
                $balance += $val;
                break;
            case 2:
                $balance -= $val;
                break;
            case 5:
                $balance -= $val;
                break;
            case 6:
                $balance += $val;
                break;
            case 7:
                $balance += $val;
                break;
            case 8:
                $balance -= $val;
                break;
            case 9:
                $balance = $val;
                break;
            case 10:
                $balance += $val;
                break;
            case 11:
                $balance -= $val;
                break;
            case 12:
                $balance -= $val;
                break;
        }
        return $balance;
    }
    
    public function total_balance_for_mutual($val, $type, &$balance) {
        switch((int)$type) {
            case 1:
                $balance += $val;
                break;
            case 2:
                $balance -= $val;
                break;
            case 3:
                $balance += $val;
                break;
            case 4:
                $balance -= $val;
                break;
            case 5:
                $balance -= $val;
                break;
            case 6:
                $balance += $val;
                break;
            case 7:
                $balance += $val;
                break;
            case 8:
                $balance -= $val;
                break;
            case 9:
                $balance = $val;
                break;
        }
        return $balance;
    }
    
    public function get_24_call_cps($res_id) {
        $model = new Cdr();
        $result = $model->query("select max(call) as call24, max(cps) as cps24 from qos_resource where 
res_id = $res_id and 
report_time between CURRENT_TIMESTAMP - interval '24 hours'  and CURRENT_TIMESTAMP");
        if(empty($result))
            return array(0, 0);
        else
            return array($result[0][0]['call24'], $result[0][0]['cps24']);
    }
    
    public function  get_rate_table($id) {
        $model = new Cdr();
        $sql = "SELECT name FROM rate_table WHERE rate_table_id = {$id}";
        $result = $model->query($sql);
        if($result)
        {
            return $result[0][0]['name'];
        }
        return '';
    }
    
}

?>
