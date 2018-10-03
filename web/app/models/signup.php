<?php

class Signup extends Model
{

    var $name = 'Signup';
    var $useTable = "signup";
    var $primaryKey = "id";
    var $xvalidate_data = null;
    public $tip_info = array(); //定义静态属性


    public $xvalidatevar = Array(//验证
        'company' => Array(
            'noEmpty' => 'Company Name cannot be Null!',
            'onlyLetterNumberLineSpace' => 'Company Name contain illegal character'
        ),

        'login' => Array(
            'noEmpty' => 'Username cannot be Null!',
            'login' => 'Username must begin with letter or underline! Username must contain alphanumeric or underscore!',
            'length' => Array('length' => 16, 'message' => 'Username Prefix digits can not exceed 16 characters!'),
            'unique' => 'Username is unique!'
        ),
        'password' => Array(
            'noEmpty' => 'Password cannot be Null!',
            'en' => 'Password must contain alphanumeric characters only!',
            'minLength' => Array('length' => 6, 'message' => 'Password length must be greater than six!')
        ),
        'phone' => Array(

            'phone' => 'Phone must contain number or hyphen!'

        ),

        'email' => Array(
            'noEmpty' => 'Email cannot be Null!',
            'email' => 'Email Emails must be a valid format.  The following Email are not valid',
	    'unique' => 'Email is already registered!'
        ),
        'noc_email' => Array(
            'noEmpty' => 'NOC email cannot be Null!',
            'email' => 'NOC email Emails must be a valid format.  The following Email are not valid'
        ),
        'billing_email' => Array(
            'noEmpty' => 'Bill Email cannot be Null!',
            'email' => 'Bill Email must be a valid format.  The following Email are not valid'
        ),
        'rate_email' => Array(
            'noEmpty' => 'Rate Email cannot be Null!',
            'email' => 'Rate Email must be a valid format.  The following Email are not valid'
        ),
        /* 'rate_delivery_email' => Array(
            'noEmpty' => 'Rate Delivery Email cannot be Null!',
            'email' => 'Rate Delivery Email must be a valid format.  The following Email are not valid'
        ) */
    );

    public function xvalidate($data)
    {
        $Arr = $this->xvalidatevar;
        $this->xvalidate_data = $data;
        $re = true;
        foreach ($Arr as $key => $value)
        {
            $str = array_keys_value($data, $key);
            foreach ($value as $f => $options)
            {
                $message = $options;
                if (is_array($options))
                {
                    $message = $options['message'];
                }
                $fun = 'xvalidate_' . $f;
                if (!$this->$fun($str, $key, $options))
                {
                    $id = '#' . $this->name . ucfirst($key);
                    $this->create_json_array($id, 101, $message);
                    $re = false;
                    break;
                }
            }
        }
        return $re;
    }

    function xvalidate_noEmpty($str, $key, $options)
    {
        $id = array_keys_value($this->xvalidate_data, $this->primaryKey);
        if (!empty($id) && !array_keys_exists($this->xvalidate_data, $key))
        {
            pr($key);
            pr($this->xvalidate_data);
            return true;
        }
        return !empty($str);
    }

    function xvalidate_en($str, $key, $options)
    {
        if (empty($str))
        {
            return true;
        }
        return preg_match("/^[0-9a-zA-Z]*$/", $str);
    }

    function xvalidate_login($str, $key, $options)
    {
        if (empty($str))
        {
            return true;
        }
        return preg_match("/^[_a-zA-Z]+[_0-9a-zA-Z]*$/", $str);
    }

    function xvalidate_email($str, $key, $options)
    {
        if (empty($str))
        {
            return true;
        }
        return preg_match("/^[a-zA-Z0-9.-]+@[a-zA-Z0-9.-]+$/", $str);
    }

    function xvalidate_unique($str, $key, $options)
    {
        if (empty($str))
        {
            return true;
        }
        $id = array_keys_value($this->xvalidate_data, $this->primaryKey);
        $conditions = Array($key => $str);
        if (!empty($id))
        {
            $conditions[] = "{$this->primaryKey} <> $id";
        }
        $count = $this->find('count', Array('conditions' => $conditions));
        return !$count > 0;
    }

    function xvalidate_length($str, $key, $options)
    {
        if (empty($str))
        {
            return true;
        }
        $length = strlen($str);
        return (int) $length <= (int) $options['length'];
    }

    function xvalidate_minLength($str, $key, $options)
    {
        if (empty($str))
        {
            return true;
        }
        $length = strlen($str);
        return (int) $length >= (int) $options['length'];
    }

    function xvalidate_onlyLetterNumberLineSpace($str, $key, $options)
    {
        if (empty($str))
        {
            return true;
        }
        return preg_match('/^[0-9a-zA-Z_][0-9a-zA-Z_ \|\.\=\-]+[0-9a-zA-Z_]$/', $str);
    }

    function xvalidate_phone($str, $key, $options)
    {
        return preg_match('/^[0-9\-]*$/', $str);
    }



    /**
     * 第3版
     * 向界面设置验证信息并且返回用户输入的数据
     */
    public function set_validator_data()
    {
        $tmp = $this->tip_info;
        //pr($tmp);
        $len = count($tmp);
        //pr($len);
        $str = '';
        for ($i = 0; $i < $len; $i++)
        {
            $field = $tmp [$i] ['field'];
            $code = $tmp [$i] ['code'];
            $msg = $tmp [$i] ['msg'];
            $d = "{'field':'$field','code':'$code','msg':'$msg'},";
            if ($i == $len - 1)
            {
                $d = "{'field':'$field','code':'$code','msg':'$msg'}";
            }
            $str = $str . $d;
        }
        $_POST ['tip_info'] = '[' . $str . ']';
        // pr($_POST['tip_info']);
        return $_POST ['tip_info'];
    }

    /*     * (第2版)
     * 生成多条提示信息
     * @param 提示信息种类 $code
     * @param 提示信息 $msg
     * @param $field 前台界面form元素的id
     * @return 创建前台json数组
     */

    function create_json_array($field, $code, $msg)
    {
        $arr = array('field' => $field, 'code' => $code, 'msg' => $msg); //组装一个新数组
        array_push($this->tip_info, $arr); //向数组添加一个元素
    }

    public function get_client_ip_info($login){

        $sql = <<<SQL
SELECT SignupIP.ip, SignupIP.port, SignupIP.netmark as mask
FROM signup as Signup
left join signup_ip as SignupIP on SignupIP.signup_id  = Signup.id 
WHERE Signup.login = '$login'
SQL;
        return $this->query($sql);
    }

}
