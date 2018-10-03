<?php
class Amline{
	var $_index = 1;
	var $_series = array();
	var $_graphs = array();
	var $_colors = array();
	var $_titles = array();
	
	function __construct(){
		$this->_colors = array(1=> "3972cd" , 2=>"8cc540",3=>'ff9933',4=> '000000' , 5=> '000000');
		header ("content-type: text/xml");
	}
	function add_series($series){
		$this->_series = $series;
	}

	function add_graph($name,$graphs){
		if(empty($this->_series)){
			trigger_error("add series first.",E_USER_ERROR);
		}
		if(!empty($graphs)){
			$new_graph = array();		
			foreach( $graphs as $gk => $gv ){
				$sk = $this->_find_serie_id($gk);
				if(empty($sk)){
					trigger_error("$gk is not found in series",E_USER_ERROR);
					return false;
				}
				$new_graph[$sk] = $gv;
			}
			$this->_graphs[$this->_index] = $new_graph;
			$this->_titles[$this->_index] = $name;
			$this->_index = $this->_index + 1;
		}
		return true;
	}
	
	function _find_serie_id($value){
		foreach(array_keys($this->_series) as $sk){
			if($this->_series[$sk] == $value){
				return $sk;
			}
		}
		return null;
	}

	function to_xml(){
		$out = '<settings>';
		$out .= file_get_contents(dirname(__FILE__)."/amcolumn_setting.xml");
		$out .= '<data><chart>
<series>
';
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
