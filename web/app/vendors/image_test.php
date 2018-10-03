<?php 
$file = empty($_REQUEST['image_file']) ? '' : basename($_REQUEST['image_file']);
if (!empty($file) && file_exists('/tmp/'.$file))
{
	// content="text/plain; charset=utf-8"
	require_once ('src/jpgraph.php');
	require_once ('src/jpgraph_line.php');
	 
	$info_arr = json_decode(file_get_contents('/tmp/'.$file));
	//var_dump($info_arr);exit;
	$field = empty($_REQUEST['field']) ? 'ca' : $_REQUEST['field'];
	$xdata = array();
	$ydata = array();
	if (!empty($info_arr))
	{
		$xdata[0] = $info_arr[0][0]->group_time;
		$num = count($info_arr);
		//echo $info_arr[$num-1][0]->group_time;echo $info_arr[0][0]->group_time;
		$max = ceil( (strtotime($info_arr[0][0]->group_time.":00:00") - strtotime($info_arr[$num-1][0]->group_time.":00:00"))/3600);
		for ($i = 0; $i <= $max; $i++)
		{
			$xdata[$i] = date("Y-m-d H", strtotime($info_arr[$num-1][0]->group_time.":00:00") + $i * 3600);
//		}
//		foreach ($xdata as $x_k=>$x_v)
//		{
			foreach ($info_arr as $k=>$v)
			{
				if ($v[0]->group_time == $xdata[$i])
				{
					//echo $v[0]->term_country, " ", $v[0]->group_time, " ", $v[0]->$field, "<br />";
					//$ydata[strtolower($v[0]->term_country)][] = empty($v[0]->$field) ? 0 : $v[0]->$field;
					switch ($field)
					{
						case 'total_cost':
							$ydata[$v[0]->term_country.' '][] = floatval($v[0]->ingress_cost) + floatval($v[0]->egress_cost);
							break;
						default:
							$ydata[$v[0]->term_country.' '][] = empty($v[0]->$field) ? 0 : $v[0]->$field;
					}
					
				}
//				else
//				{
//					$ydata[$v[0]->term_country][] = 0;
//				}
			}
		}
	}
	$country = empty($_REQUEST['country']) ? array() : $_REQUEST['country'];
//	$xdata = array('2010-06-01 00', '2010-06-01 01', '2010-06-01 02', '2010-06-01 03', '2010-06-01 04', '2010-06-01 05', '2010-06-01 06', '2010-06-01 07', '2010-06-01 08', '2010-06-01 09');
//	$ydata = array(11,3,8,120,150,100,98,113,5,75);
//	$y2data = array(354,200,265,99,111,91,198,225,293,251);
	// var_dump($ydata);exit;
	
	// Create the graph and specify the scale for both Y-axis
	$graph = new Graph(1400, 600);    
	$graph->SetScale("intlin");
	
	// Adjust the margin
	$graph->img->SetMargin(80,40,20,70);
	 
	// Create the two linear plot

	if (!empty($country))
	{
		foreach ($country as $k=>$v)
		{
			$v = trim($v, "'").' ';
			if (!empty($ydata[$v]))
			{
					$lineplot_tmp = 'lineplot_'.$k;
					$$lineplot_tmp=new LinePlot($ydata[$v]);
					//$$lineplot_tmp->SetColor("blue");
					//$$lineplot_tmp->SetWeight(2);
					$graph->Add($$lineplot_tmp);	
					$$lineplot_tmp->SetLegend($v);
			}
		}
	}
	else
	{
		$j=0;
		foreach ($ydata as $k=>$v)
		{
			if ($j > 10)
			{
				break;
			}
			$j++;
			
			//$lineplot_tmp = 'lineplot_'.$j;
			$lineplot_tmp = 'lineplot';
			//$ydata_tmp = implode(",", $v); 
			//var_dump($v);
			${$lineplot_tmp}=new LinePlot($v);
			//$color_tmp = "#".dechex(rand(0, 255)).dechex(rand(0, 255)).dechex(rand(0, 255));
			//$$lineplot_tmp->barcenter = true;
			//${$lineplot_tmp}->SetColor($color_tmp);
			//$$lineplot_tmp->SetWeight(4);		
			$graph->Add($$lineplot_tmp);
			$$lineplot_tmp->SetLegend($k);
		}
	}
	
//	$lineplot2=new LinePlot($y2data);
//	$lineplot2->SetColor("orange");
//	$lineplot2->SetWeight(2);
//	$graph->Add($lineplot2);
	//var_dump($xdata);
	$graph->xaxis->SetTickLabels($xdata);
	$graph->xaxis->SetTextLabelInterval(1);
	$graph->xaxis->SetTickSide(SIDE_BOTTOM);
	
	$graph->title->Set("Report");
	$graph->xaxis->title->Set("Time");
	$graph->yaxis->SetTitleMargin(45);
	$graph->yaxis->title->Set($field);
	 
	$graph->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	 
	// Set the colors for the plots 
//	$lineplot->SetColor("#FF0000");
//	$lineplot->SetWeight(2);
//	$lineplot2->SetColor("#00FFFF");
//	$lineplot2->SetWeight(2);
	 
	// Set the legends for the plots
//	$lineplot->SetLegend($country);
//	$lineplot2->SetLegend("America");
//	 
	// Adjust the legend position
	//$graph->legend->SetLayout(LEGEND_HOR);
	$graph->legend->SetLayout(LEGEND_HOR);
	$graph->legend->Pos(0.4,0.95,"center","bottom");
	//$graph->legend->SetColor("red");
	 
	//字体旋转
	//$graph->xaxis->SetLabelAngle(rad2deg(asin(1)));
	$graph->xaxis->SetLabelAngle(90);
	
	// Display the graph
	$graph->Stroke();
}
?>
