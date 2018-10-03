<?php
class CdrArchiveController extends AppController
{
		public $name = 'CdrArchive';
		public $uses = array('Cdr');
		public $components = array('PhpDownload');
		
		public function backup()
		{
			if (!empty($_POST['sel_date']))
			{
				foreach($_POST['sel_date'] as $k=>$v)
				{
					$this->_backup_impl($v);
				}
			}
			/*$this->pageTitle="Configuration/CDR Archive";
			if (isset($this->params['url']['filter_range_ti_start_time_of_date_start'])) 
			{				
				//$this->_catch_exception_msg(array('CdrArchiveController','_backup_impl'));
			}			*/
			$this->redirect('view');
		}
		
		public function rotate()
		{
			if (!empty($_POST['sel_date_1']))
			{
				foreach($_POST['sel_date_1'] as $k=>$v)
				{
					$this->_restore_sql($v);
				}
			}
			/*$this->pageTitle="Configuration/CDR Archive";
			if (isset($this->params['url']['filter_range_ti_start_time_of_date_start'])) 
			{				
				//$this->_catch_exception_msg(array('CdrArchiveController','_backup_impl'));
			}			*/
			$this->redirect('view');
		}
		
		private function _backup_impl($date)
		{
			Configure::write('debug',0);
			App::import("Core","Folder");
			$db_file = CONFIGS . 'database.php';//APP . 'config' . DS . 'database.php';
			require_once($db_file);
			$db_config_class = new DATABASE_CONFIG();
			$db_config = $db_config_class->default;
			$path = APP.'tmp'.DS.'upload'.DS.$this->name;
			if (!is_dir($path))
			{
				mkdir($path);
			}
			$path = $path . DS . date("Ym", strtotime($date));
			if (!is_dir($path))
			{
				mkdir($path);
			}
			$descriptorspec = array(
			   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
			   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
			   2 => array("file", "/tmp/error-output.txt", "a") // stderr is a file to write to
			);
			
			$cwd = '/tmp';
			//$comm = 'pg_dump -h 192.168.1.115 -p 5432 -u us_class4 --table=client_cdr20101201 > /tmp/pgsql/pgsql_20101201.sql -i';
			$time_str = ''.date("Ymd", strtotime($date));
			$comm = "pg_dump -h {$db_config['host']} -p {$db_config['port']} -U{$db_config['login']} {$db_config['database']} -F c -i --table=client_cdr{$time_str} > {$path}/pgsql_{$time_str}.sql -i";
			//echo "<p>";
			$process = proc_open($comm, $descriptorspec, $pipes, $cwd);//, $env);
			
			if (is_resource($process)) {
			    // $pipes now looks like this:
			    // 0 => writeable handle connected to child stdin
			    // 1 => readable handle connected to child stdout
			    // Any error output will be appended to /tmp/error-output.txt
			
						//fwrite($pipes[0], $db_config['login']);
				   fwrite($pipes[0], $db_config['password']);
				   fclose($pipes[0]);
			
			    //echo stream_get_contents($pipes[1]);
			    fclose($pipes[1]);
			
			    // It is important that you close any pipes before calling
			    // proc_close in order to avoid a deadlock
			    $return_value = proc_close($process);
			
			    //echo "command returned $return_value\n";
			}
		}
		
		private function _restore_sql($date)
		{
			Configure::write('debug',0);
			App::import("Core","Folder");
			$db_file = CONFIGS . 'database.php';//APP . 'config' . DS . 'database.php';
			require_once($db_file);
			$db_config_class = new DATABASE_CONFIG();
			$db_config = $db_config_class->default;
			$path = APP.'tmp'.DS.'upload'.DS.$this->name.DS.date("Ym", strtotime($date));
			$descriptorspec = array(
			   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
			   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
			   2 => array("file", "/tmp/error-output.txt", "a") // stderr is a file to write to
			);
			$cwd = '/tmp';
			//$comm = 'pg_restore -h 192.168.1.115 -p 5432 -u -d class4_pr /tmp/pgsql/pgsql_20110310.sql -i';
			$time_str = ''.date("Ymd", strtotime($date));
			$comm = "pg_restore -h {$db_config['host']} -p {$db_config['port']} -U{$db_config['login']} -d {$db_config['database']} {$path}/pgsql_{$time_str}.sql -i";
			//echo "<br />";
			$process = proc_open($comm, $descriptorspec, $pipes, $cwd);//, $env);
			
			if (is_resource($process)) {
			    // $pipes now looks like this:
			    // 0 => writeable handle connected to child stdin
			    // 1 => readable handle connected to child stdout
			    // Any error output will be appended to /tmp/error-output.txt
			
						//fwrite($pipes[0], $db_config['login']);
				   fwrite($pipes[0], $db_config['password']);
				   fclose($pipes[0]);
			
			    echo stream_get_contents($pipes[1]);
			    fclose($pipes[1]);
			
			    // It is important that you close any pipes before calling
			    // proc_close in order to avoid a deadlock
			    $return_value = proc_close($process);
			
			    //echo "command returned $return_value\n";
			}
		}
		
		public function view()
		{
			App::import("Core","Folder");
			$path = APP.'tmp'.DS.'upload'.DS.$this->name;
			$this->pageTitle="Cdr Archive";
			$this->loadModel('Cdr');
//			$currPage = empty($_REQUEST['page'])?1:$_REQUEST['page'];
//			$pageSize = empty($_REQUEST['size'])?2:$_REQUEST['size'];
//		
//			//分页信息
//			require_once 'MyPage.php';
//			$page = new MyPage();
//			
//			$totalrecords = 0;
//			$sql = "SELECT count(table_name) as c FROM INFORMATION_SCHEMA.tables WHERE table_schema = 'public' and table_name != 'client_cdr' and table_name like 'client_cdr%'";
//			$totalrecords = $this->Cdr->query($sql);
//	 	
//			$page->setTotalRecords($totalrecords[0][0]['c']);//总记录数
//			$page->setCurrPage($currPage);//当前页
//			$page->setPageSize($pageSize);//页大小
//			
//			
//			$currPage = $page->getCurrPage()-1;
//			$pageSize = $page->getPageSize();
//			$offset = $currPage * $pageSize;
			
			$sql = "SELECT table_name as name FROM INFORMATION_SCHEMA.tables WHERE table_schema = 'public' and table_name != 'client_cdr' and table_name like 'client_cdr%'";
			$sql .= " order by name desc";
			//$sql .= " limit '$pageSize' offset '$offset'";
			
			$results = $this->Cdr->query($sql);
			$month_results = array();
			if (!empty($results))
			{
				foreach ($results as $k=>$v)
				{
					$cur_date = substr($v[0]['name'],10);
					$mon = substr($v[0]['name'],10, -2);
//					if (!isset($month_results[$mon][0]))
//					{
//						$month_results[$mon][0] = array('name'=>$mon, 'filepath'=>'');
//					}
					$month_results[$mon][$cur_date]['name'] = date("M d,Y", strtotime(str_replace('client_cdr', '', $v[0]['name'])) );
					$month_results[$mon][$cur_date]['filepath'] = '';
					$file_tmp = $path . "/$mon/pgsql_" . str_replace('client_cdr', '', $v[0]['name']) . ".sql";
					if (is_file($file_tmp))
					{
						$month_results[$mon][$cur_date]['filepath'] = $file_tmp;
					}
				}
			}
			
			//$page->setDataArray($results);//Save Data into $page
			
			//$this->set('p',$page);
			//$result = $this->Cdr->query("SELECT table_name as name FROM INFORMATION_SCHEMA.tables WHERE table_schema = 'public' and table_name != 'client_cdr' and table_name like 'client_cdr%'");
			//$this->set('cdr_table', $results);
			$this->set('cdr_table', $month_results);
			//var_dump($result);
			$files = $this->_view_backup_file();
			foreach ($files as $k=>$v)
			{
				if (!is_array($v))
				{
					$files[$k]=null;
					unset($files[$k]);
				}
			}
			//pr($files);
			$this->set('files', $files);
		}
		
		private function _view_backup_file()
		{
			$return = array();
			App::import("Core","Folder");
			$path = APP.'tmp'.DS.'upload'.DS.$this->name;
			return $files = $this->filelist($path);
		}
		
		function filelist($path)
		{	
				$return = array();	
				if(false!=$handle=@opendir($path)){			
			     while($file=readdir($handle)){   //进行循环读取		
			        //限制不能读取本目录和上一个目录，只能读取下一个目录			
			         if($file!="."&&$file!=".."){ 
			         		 if (is_file($path."/".$file))
			         		 				  { 		
			         		 		$return[] = $file;
			         		 				  }
			         		 else
			         		 				 {
			         		 		//$base_path = basename($path);			             		
			             		//echo $path.":".$file."<br>"; //对路径加文件进行输出，实际上也是路径		
			             		$return[$file] = $this->filelist($path."/".$file);   //循环递归调用	
			         						 }	
			          				}		
			         else{		
			//如果读取的是本目录，也就是说没有下一级目录，则文件全部读完		
			              //$return[$path][] = $file;
			              //echo $path.":".$file."<br>";		
									}		
						}				
				}					
			return $return;	
		}
		
}
?>