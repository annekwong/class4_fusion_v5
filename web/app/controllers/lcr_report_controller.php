<?php

class LcrReportController extends AppController
{
    var $name = 'LcrReport';
    var $uses = array("Rate", "LcrReport");
    var $helper = array('html', 'javascript', 'RequestHandler');
    
    public function beforeFilter()
    {
        parent::beforeFilter();
    }
    
    public function get_routing_plan($rate_table_id)
    {
        Configure::write("debug", 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $return = array();
        $sql = "select distinct resource_prefix.resource_id, resource_prefix.route_strategy_id,  route_strategy.name from resource_prefix left join route_strategy on resource_prefix.route_strategy_id = route_strategy.route_strategy_id where resource_prefix.rate_table_id = $rate_table_id";
        $result = $this->Rate->query($sql);
        foreach ($result as $item) {
            $return[] = $item[0];
        }
        echo json_encode($return);
    }
    
    public function search()
    {
        $this->pageTitle = "Statistics/LCR Report";
        
        $rate_tables = $this->Rate->query("select distinct rate_table.rate_table_id, rate_table.name from resource_prefix inner join rate_table on resource_prefix.rate_table_id = rate_table.rate_table_id order by rate_table.name
");
        $this->set("rate_tables", $rate_tables);
    }
    
    public function index()
    {
        $this->pageTitle = "Statistics/LCR Report";
        $this->paginate = array(
            'limit' => 100,
            'order' => array(
                'id' => 'desc',
            )
        );
        $this->data = $this->paginate('LcrReport');
    }
    
    public function download($id)
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $lcr_report = $this->LcrReport->findById($id);
        $file_path = $lcr_report['LcrReport']['file_path'];
        header("Content-type: application/octet-stream");
        $filename = basename($file_path);
        //处理中文文件名
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = rawurlencode($filename);
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } else if (preg_match("/Firefox/", $ua)) {
            header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }
        readfile($file_path);
    }
}