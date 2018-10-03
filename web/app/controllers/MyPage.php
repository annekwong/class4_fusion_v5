<?php
class MyPage{
	var $currPage = 1;//当前页
	var $pageSize = 100;//页大小
	var $totalRecords = 0;//总记录数
	var $totalPages = 0;//总页数
	var $dataArray = null;//记录
	
public function beforeFilter(){
	$this->checkSession ( "login_type" );//核查用户身份
   parent::beforeFilter();//调用父类方法
}
	
	
	public function setCurrPage($currPage=null){
		if (!empty($currPage)) {
			$this->currPage = $currPage;
		} else {
			$this->currPage = 1;
		}
	}
	
	public function getCurrPage(){
		return $this->currPage;
	}
	
	public function setPageSize($pageSize=null){
		if (!empty($pageSize)) {
			$this->pageSize = $pageSize;
		} else {
			$this->pageSize = 100;
		}
	}
	
	public function getPageSize(){
		return $this->pageSize;
	}
	
	public function setTotalRecords($totalrecords=null){
		if (!empty($totalrecords)) {
			$this->totalRecords = $totalrecords;
		}
	}
	
	public function getTotalRecords(){
		return $this->totalRecords;
	}
	
	public function getTotalPages(){
		return (int)($this->totalRecords+$this->pageSize-1)/$this->pageSize;
	}
	
	public function setDataArray($dataArray=array()){
		$this->dataArray = $dataArray;
	}
	
	public function getDataArray(){
		return $this->dataArray;
	}
	
	public function checkRange($page=null){
		if (!empty($page)) {
			if ($page->getCurrPage()<1) {
				$page->setCurrPage(1);
			}
			
			if ($page->getCurrPage() > $page->getTotalPages()) {
				$page->setCurrPage($page->getTotalPages());
			}
		}
		return $page;
	}
}