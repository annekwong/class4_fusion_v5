<?php


class DATABASE_CONFIG
{

    var $default = array( );
    var $common = array();
    var $mem_mysql = array();
    
    public function __construct()
    {

        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
        foreach ($sections as $section_key => &$section)
        {
            foreach ($section as $item_key => &$item) {
                while (strpos($item, '$') !== FALSE && strpos($item, '$') == 3) {
                    list($key, $value) = explode('.', $item);
                    $item = @$sections[$key][trim($value, '$')];
                }
            }
        }

        $this->default = array(
            'driver' => 'postgres',
            'persistent' => false,
            'host' => $sections['web_db']['host'],
            'login' => $sections['web_db']['user'],
            'password' => $sections['web_db']['password'],
            'database' => isset($sections['db']['dbname1'])?$sections['db']['dbname1']:$sections['web_db']['dbname'],
            'prefix' => '',
            'port' => $sections['web_db']['port']
        );
        
        $this->common = array(
            'driver' => 'postgres',
            'persistent' => false,
            'host' => $sections['web_db2']['host'],
            'login' => $sections['web_db2']['user'],
            'password' => $sections['web_db2']['password'],
            'database' => $sections['web_db2']['dbname'],
            'prefix' => '',
            'port' => $sections['web_db2']['port']
        );
    }

}

?>
