<?php

class Capture extends AppModel
{

//    var $name = 'Capture';
    var $useTable = false;
//    var $primaryKey = 'capture_id';
//    var $order = "capture_id DESC";
    var $belongsTo = array('User');

    function get_all_capture($order = null, $options = array())
    {
        if (empty($order))
        {
            $order = "capture_id  desc";
        }
        empty($_GET['page']) ? $currPage = 1 : $currPage = $_GET['page'];
        empty($_GET['size']) ? $pageSize = 10 : $pageSize = $_GET['size'];
        require_once 'MyPage.php';
        $page = new MyPage();
        $login_type = $_SESSION['login_type'];
        $privilege = ''; //权限条件
        if ($login_type == 3)
        {
            $privilege = "  and(user_id={$_SESSION['sst_user_id']}) ";
        }


//        $sqlStr = "select count(*) as c from capture  where  1=1   $privilege";
        //pr($sqlStr);
//        $totalrecords = $this->query($sqlStr);
//        $page->setTotalRecords($totalrecords[0][0]['c']); //总记录数
        //pr($totalrecords[0][0]['c']);
        $page->setCurrPage($currPage); //当前页
        $page->setPageSize($pageSize); //页大小
        //$page = $page->checkRange($page);//检查当前页范围




        $currPage = $page->getCurrPage() - 1;
        $pageSize = $page->getPageSize();
        $offset = $currPage * $pageSize;
//        $sql = "select  capture.*,users.name as user_name from  capture left join users  on users.user_id=capture.user_id  where
//		1=1  $privilege  order by $order  ";
//        $sql .= "   	limit '$pageSize' offset '$offset'";
//        //pr($sql);
//        $results = $this->query($sql);
        $results = array();
        $page->setDataArray($results);
        return $page;
    }

    /**
     * 
     * @param unknown_type $data
     * 	@return  model
     */
    function new_capture($data)
    {

        $c = $this->save($data);
        if ($c)
        {
            $c['Capture']['capture_id'] = $this->getLastInsertID();
        }

        return $c;
    }
    
    
    public function create_new($data)
    {
        $capture = array(
            'Capture' => $data,
        );
        
        $data['src_ip'] =  empty($data['src_ip'])  ? 'NULL' : "'" . $data['src_ip']  . "'";
        $data['dest_ip'] = empty($data['dest_ip']) ? 'NULL' : "'" . $data['dest_ip']  . "'";
        $data['server_ip'] = empty($data['server_ip']) ? 'NULL' : "'" . $data['server_ip']  . "'";
        $data['view'] = $data['view'] ? 'true' : 'false';
        
//        $sql = "insert into capture(ani, dnis, time_val, user_id, src_ip, src_port,
//            dest_ip, dest_port, key_word, view,
//        server_ip, server_port) values ('{$data['ani']}', '{$data['dnis']}', '{$data['time_val']}', '{$data['user_id']}',
//        {$data['src_ip']}, '{$data['src_port']}', {$data['dest_ip']}, '{$data['dest_port']}', '{$data['key_word']}',
//        '{$data['view']}', {$data['server_ip']}, '{$data['server_port']}') returning capture_id";
//        $result = $this->query($sql);
//        //$data = array('Capture'=>array('capture_id' => $result[0][0]['capture_id']));
//        $capture['Capture']['capture_id'] = $result[0][0]['capture_id'];
        return $capture;
    }

    function is_ready_to_view($size, $flag)
    {
        //	return !empty($data) && $data['Capture']['pid'] <= 0 && (int)($data['Capture']['file_size']) > 0;
        return $size > 24 && $flag == 1;
    }

    function is_ready_to_stop($flag)
    {
        //	return !empty($data) && $data['Capture']['pid'] > 0 && time($data['Capture']['capture_time']) + 60 >= time();
        return $flag == 2;
    }

}

?>