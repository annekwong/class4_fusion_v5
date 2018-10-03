<?php

/**
 * 冲值策略
 * @author root
 *
 */
class SalestrategsController extends AppController{
	
	var $name = 'Salestrategs';
	var $helpers = array('javascript','html');
	
	
	
	//读取该模块的执行和修改权限
	public function beforeFilter(){
		$this->checkSession ( "login_type" );//核查用户身份
		$login_type = $this->Session->read('login_type');
							if($login_type==1){
						//admin
		$this->Session->write('executable',true);
		$this->Session->write('writable',true);
					}else{
		$limit = $this->Session->read('sst_retail_salestra');
		$this->Session->write('executable',$limit['executable']);
		$this->Session->write('writable',$limit['writable']);
					}
					parent::beforeFilter();
	}
	
	
	/**
	 * 初始化信息
	 */
	function init_info(){
				$this->set('ratetable',$this->Salestrateg->findRateTable());

		
	}
	
	/**
	 * 编辑客户信息
	 */
	function edit() {
		if (! empty ( $this->data ['Salestrateg'] )) {
			$flag = $this->Salestrateg->saveOrUpdate ( $this->data, $_POST ); //保存
			if (empty ( $flag )) {
				$this->set ( 'm', Salestrateg::set_validator ()); //向界面设置验证信息
				$this->set ( 'post', $this->data );
				$this->init_info ();
				$this->Salestrateg->create_json_array('',101,__('update_fail',true));
				$this->Session->write('m',Salestrateg::set_validator());
			} else {
				$this->Salestrateg->create_json_array('',201,__('update_suc',true));
				$this->Session->write('m',Salestrateg::set_validator());
				$this->redirect ('/salestrategs/view?edit_id='.$this->params['form']['sales_strategy_id'] ); // succ
			}
		} else {
			$this->Salestrateg->sales_strategy_id = $this->params ['pass'][0];
			$post=$this->Salestrateg->read ();
	 
			$this->set('post', $post);
				$this->set('postcharges', $this->Salestrateg->findAll_charges($this->params ['pass'][0]));
				
				$this->set('post', $post);
				$this->set('postpoints', $this->Salestrateg->findAll_points($this->params ['pass'][0]));
		
			$this->init_info ();
		
		}
	}
	
	
	

	
	
	/**
	 * 添加
	 */
	function add() {
			if (! empty ($this->data ['Salestrateg'] )) {
			$flag = $this->Salestrateg->saveOrUpdate ($this->data, $_POST); //保存
			if (empty ($flag)) {
				//添加失败
				$this->set ('m', Salestrateg::set_validator () ); //向界面设置验证信息
				$this->set ( 'post', $this->data);
				$this->init_info ();
				$this->Salestrateg->create_json_array('',101,__('add_fail',true));
				$this->Session->write('m',Salestrateg::set_validator());
			} else {
				$this->Salestrateg->create_json_array('',201,__('add_suc',true));
				$this->Session->write('m',Salestrateg::set_validator());
				$this->redirect ( array ('controller' => 'salestrategs', 'action' => 'view' )); // succ
			}
		} else {
			$this->init_info();
		}
    
	
	}

	function del(){
		$id=$this->params['pass'][0];
		$name=$this->params['pass'][1];
		$this->Salestrateg->query("delete from sales_strategy_charges where sales_strategy_id=$id");
		$this->Salestrateg->query("delete from sales_strategy_points where sales_strategy_id=$id");
		$this->Salestrateg->query("delete from sales_strategy where sales_strategy_id=$id");
	
		
		$this->Session->write('m',$this->Salestrateg->create_json(101,'策略名称为'.$name.'的充值策略已经被删除'));
		$this->redirect (array ('action' => 'view' ) );
	}
	

	
	/**
	 * 查询客户
	 */
	public function view(){
		
		$this->init_info();
	  empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 empty($_GET['size'])?$pageSize = 100:	$pageSize = $_GET['size'];
		 //模糊搜索
		 if(isset($_POST['searchkey'])){
		 	  	$results = $this->Blocklist->likequery($_POST['searchkey'],$currPage,$pageSize);
		 	  		$this->set('searchkey',$_POST['searchkey']);
		 	  	$this->set('p',$results);
		 	  	return ;
		 }
		 
		//高级搜索 
		if(!empty($this->data['Blocklist'])){
			
		
			$results = $this->Blocklist->Advancedquery($this->data,$currPage,$pageSize);
			$this->set('search','search');//搜索设置
		}
		else{
			if (!empty($_REQUEST['edit_id'])){
				$sql = "select sales_strategy.sales_strategy_id,sales_strategy.name,a.charges_cnt,c.card_cnt,rate.rate_table_name,regular_customer,new_customer,
		extended_days,p.bonus_cnt,reseller.reseller_name
		    from  sales_strategy 
		   left join (select count(*)as  charges_cnt,sales_strategy_id from sales_strategy_charges    group by  sales_strategy_id) a   
		   on  a.sales_strategy_id=sales_strategy.sales_strategy_id
		   
		    left join (select count(*)as  bonus_cnt,sales_strategy_id from sales_strategy_points    group by  sales_strategy_id) p   
		   on  p.sales_strategy_id=sales_strategy.sales_strategy_id
		   
		      left join (select count(*)as  card_cnt,sales_strategy_id from card_series    group by  sales_strategy_id) c   
		   on  c.sales_strategy_id=sales_strategy.sales_strategy_id
		   
		   left join (select name  as rate_table_name, rate_table_id  from rate_table) rate 
		   
		   on  rate.rate_table_id=sales_strategy.new_rate_table_id
		     left join (select name  as reseller_name, reseller_id  from reseller) reseller     
		   on  reseller.reseller_id=sales_strategy.reseller_id where  sales_strategy.sales_strategy_id = {$_REQUEST['edit_id']}
	  		";
			$result = $this->Salestrateg->query ( $sql );
			//分页信息
				require_once 'MyPage.php';
				$results = new MyPage ();
				$results->setTotalRecords ( 1 ); //总记录数
				$results->setCurrPage ( 1 ); //当前页
				$results->setPageSize ( 1 ); //页大小
				$results->setDataArray ( $result );
			$this->set('edit_return',true);
			} else {
			//
				$results = $this->Salestrateg->findAll($currPage,$pageSize);
			}
		}
		

	

		$this->set('p',$results);
			
	}
	
	
	
	
/**
 * 查看充值送话费规则
 */
	public function findgift_amount(){
		$id=$this->params['pass'][0];//充值策略
				$this->set('id',$id);
		$this->init_info();
	  empty($_GET['page'])?$currPage = 1:$currPage = $_GET['page'];
		 empty($_GET['size'])?$pageSize = 100:	$pageSize = $_GET['size'];
		 //模糊搜索
		 if(isset($_POST['searchkey'])){
		 	  	$results = $this->Blocklist->likequery($_POST['searchkey'],$currPage,$pageSize);
		 	  		$this->set('searchkey',$_POST['searchkey']);
		 	  	$this->set('p',$results);
		 	  	return ;
		 }
		 
		//高级搜索 
		if(!empty($this->data['Blocklist'])){
			
		
			$results = $this->Blocklist->Advancedquery($this->data,$currPage,$pageSize);
			$this->set('search','search');//搜索设置
		}
		else{
			
			//
				$results = $this->Salestrateg->findgift_amount($currPage,$pageSize,$id);
		}
		

	

		$this->set('p',$results);
			
	}
	
	



}
	


?>
