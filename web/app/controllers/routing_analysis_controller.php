<?php

class RoutingAnalysisController extends AppController {

    public $name = 'RoutingAnalysis';
    public $uses = array('RoutingAnalysis');
    public $components = array('RequestHandler');
    public $helper = array('Xml', 'JavaScript');

    public function beforeFilter() {
        $this->checkSession("login_type"); //核查用户身份exprot
        parent::beforeFilter(); //调用父类方法
    }

    public function index() {
        $carriers = $this->RoutingAnalysis->get_carriers();
        if (isset($_GET['submit'])) {

            if (! isset($_GET['ingress_prefix'])) {
                $this->Session->write('m', $this->RoutingAnalysis->create_json(101, 'The Information cannot be empty!'));
                $this->redirect("index");
            }

            $route_strategy_id = $_GET['ingress_prefix'];
            $code = $_GET['code'];
            $egress_ids_result = $this->RoutingAnalysis->get_route_egress($route_strategy_id, $code);
            $rate_table_ids = array();
            $egress_infos = array();
            foreach ($egress_ids_result as $egress_ids_item) {
                $egress_info = $this->RoutingAnalysis->get_egress_info($egress_ids_item[0]['egress_id']);
                if ($egress_info != NULL) {
                    array_push($rate_table_ids, $egress_info['rate_table_id']);
                    $egress_infos[$egress_info['rate_table_id']] = $egress_info;
                }
            }

            require_once 'MyPage.php';
            $temp = isset($_SESSION['pagging_row']) ? $_SESSION['pagging_row'] : 100;
            empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
            empty($_GET['size']) ? $pageSize = $temp : $pageSize = $_GET['size'];
            $_SESSION['pagging_row'] = $pageSize;
            $totalrecords = $this->RoutingAnalysis->get_rates_count($rate_table_ids, $code);
            $page = new MyPage();
            $page->setTotalRecords($totalrecords); //总记录数
            $page->setCurrPage($currPage); //当前页
            $page->setPageSize($pageSize); //页大小
            $currPage = $page->getCurrPage() - 1;
            $pageSize = $page->getPageSize();
            $offset = $currPage * $pageSize;

            $results = $this->RoutingAnalysis->get_rates($rate_table_ids, $code, $pageSize, $offset);
            $page->setDataArray($results);
            $this->set('p', $page);
            $this->set('egress_infos', $egress_infos);
        }
        $this->set('carriers', $carriers);
    }

    public function get_ingress_trunks() {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $client_id = $_POST['client_id'];
        $ingress_trunks = $this->RoutingAnalysis->get_ingress_trunks($client_id);
        echo json_encode($ingress_trunks);
    }

    public function get_ingress_prefixes() {
        Configure::write('debug', 0);
        $this->autoLayout = false;
        $this->autoRender = false;
        $ingress_id = $_POST['ingress_id'];
        $ingress_prefixes = $this->RoutingAnalysis->get_ingress_prefixes($ingress_id);
        echo json_encode($ingress_prefixes);
    }

}

?>
