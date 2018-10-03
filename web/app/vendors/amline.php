<?php
class Amline{
	var $_index = 1;
	var $_series = array();// [147] => 2011-01-25 02:00:00  [148] => 2011-01-25 03:00:00
	var $_graphs = array();
	var $_colors = array();
	var $_titles = array();
	
	function __construct(){
		$this->_colors = array(1=> "3972cd" , 2=>"8cc540",3=>'ff9933',4=> '000000' , 5=> '000000');
		header ("content-type: text/xml");
	}
	
	#add横轴   [147] => 2011-01-25 02:00:00  [148] => 2011-01-25 03:00:00
	
	function add_series($series){
		$this->_series = $series;
	}
#add图表 $name='code'=86    $graphs [group_time] =>[call_duration]     [2011-01-25 02:00:00]=>'669'
	function add_graph($name,$graphs){
		
//$graphs   **Array
//                (
//                			   					 ['2011-03-11 00:00:00']	=>40.6500000000000000;

//												 ['2011-03-11 00:00:00']	=>40.6500000000000000;
//                   
//                )
		if(empty($this->_series)){
			trigger_error("add series first.",E_USER_ERROR);
		}
		if(!empty($graphs)){
			$new_graph = array();
			#	$graphs 每个时间段的数据	 $gk=2011-01-25 02:00:00  $gv=669
			foreach( $graphs as $gk => $gv ){
				#$sk=14   $gk=2011-01-25 02:00:00
				$sk = $this->_find_serie_id($gk);
				if(empty($sk)){
					trigger_error("$gk is not found in series",E_USER_ERROR);
					return false;
				}
				$new_graph[$sk] = $gv;
			}
			
			#$this->_graphs  $new_graph  14=>669
			$this->_graphs[$this->_index] = $new_graph;
			$this->_titles[$this->_index] = $name;
			$this->_index = $this->_index + 1;
		}
		return true;
	}
	
	#  $value=2011-01-25 02:00:00  $this->_series  [147] => 2011-01-25 02:00:00 [148] => 2011-01-25 03:00:00
	#返回时间对应的key
	function _find_serie_id($value){
		foreach(array_keys($this->_series) as $sk){
			if($this->_series[$sk] == $value){
				return $sk;
			}
		}
		return null;
	}

	
	#用数据生成xml
	function to_xml2(){
			$out = '<settings>';
	$out .= file_get_contents(dirname(__FILE__)."/setting.xml");
	$out .= '</settings>';
	return $out;
	}
	function to_xml(){
		$out = '<settings>';
		$out .= file_get_contents(dirname(__FILE__)."/setting.xml");
		$out .= '<data><chart>
<series>
';
		
		#$this->_series  [147] => 2011-01-25 02:00:00
		foreach($this->_series as $sk => $sv){
			$out .= "<value xid=\"$sk\">$sv</value>\n";
		}
	$out .="</series>
<graphs>
";
		foreach($this->_graphs as $gk => $gv){
			$out .= "<graph gid=\"$gk\" axis=\"left\"  bullet=\"round\" balloon_text=\"{description}\" bullet_color=\"\" bullet_size=\"4\" color=\"#{$this->_colors[$gk]}\" color_hover=\"#666666\" fill_alpha=\"0\" fill_color=\"\" line_width=\"1\" title=\"{$this->_titles[$gk]}\">\n";
				foreach($gv as $gvk => $gvv){
					$ft = number_format($gvv,3,'.',',');
					$out .= "<value description='$ft' xid=\"$gvk\">$gvv</value>\n";
				}
			$out .= "</graph>\n";
		}
	$out .= '</graphs>
</chart>
</data>';
	$out .= '</settings>';
	return $out;
	}

}

#$a = new AmlineData();
#$a->add_series(array(1 => 'a','b','c','d','e'));
#$a->add_graph(array('a' => 10, 'b' => 20, 'e' => 30));
#echo $a->to_xml();
?>
