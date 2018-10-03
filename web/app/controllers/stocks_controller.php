<?php

class StocksController extends AppController {
    var $name = 'Stocks';
    var $uses = array('Monitor');
    var $helpers = array ('javascript', 'html');
    
    
    
    public function createfile($name, $proc_id) {
        switch($name) {
            case 'prefix_stats_call':
                $filename = $this->Monitor->create_prefix_csv(1, $proc_id);
                break;
            case 'prefix_stats_cps':
                $filename = $this->Monitor->create_prefix_csv(2, $proc_id);
                break;
            case 'prefix_stats_acd':
                $filename = $this->Monitor->create_prefix_csv(4, $proc_id);
                break;
            case 'prefix_stats_asr':
                $filename = $this->Monitor->create_prefix_csv(3, $proc_id);
                break;
            case 'prefix_stats_pdd':
                $filename = $this->Monitor->create_prefix_csv(5, $proc_id);
                break;
        }
        return $filename;
    }
    
    public function createfile2($name, $gress,$resource_id) {
        $ctype = 3;
        if($gress == 'ingress') {
            $ctype = 3;
        } elseif ($gress == 'egress') {
            $ctype = 4;
        }
        switch($name) {
            case 'carrier_stats_call':
                $filename = $this->Monitor->create_carrier_csv($resource_id, $ctype, 1);
                break;
            case 'carrier_stats_cps':
                $filename = $this->Monitor->create_carrier_csv($resource_id, $ctype, 2);
                break;
            case 'carrier_stats_acd':
                $filename = $this->Monitor->create_carrier_csv($resource_id, $ctype, 4);
                break;
            case 'carrier_stats_asr':
                $filename = $this->Monitor->create_carrier_csv($resource_id, $ctype, 3);
                break;
            case 'carrier_stats_pdd':
                $filename = $this->Monitor->create_carrier_csv($resource_id, $ctype, 5);
                break;
        }
        return $filename;
    }
    
     
    public function createxml2($name, $gress,$resource_id) {
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        Configure::write('debug', 0);
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        header("Content-type:text/xml");
        $filename = $this->createfile2($name, $gress,$resource_id);
echo <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<settings>
  <margins>12</margins>
  <number_format>
    <letters>
      <letter number="1000">K</letter>
      <letter number="1000000">M</letter>
      <letter number="10000000">B</letter>
    </letters>
  </number_format>
  <data_sets>
    <data_set>
      <title>Chart</title>
      <short>ES</short>
      <color>003399</color>
      <file_name>{$filename}</file_name>
      <csv>
        <reverse>1</reverse>
        <separator>,</separator>
        <date_format>YYYY-MM-DD hh-mm-ss</date_format>
        <columns>
          <column>date</column>
          <column>close</column>
        </columns>
      </csv>
    </data_set>
  </data_sets>
  <charts>
    <chart>
      <title>Value</title>
      <height>60</height>
      <column_width>100</column_width>
      <grid/>
      <values>
        <x>
          <bg_color>EEEEEE</bg_color>
        </x>
        <y_left>
          <bg_color>000000</bg_color>
          <unit></unit>
          <unit_position>left</unit_position>
          <digits_after_decimal>
            <data></data>
          </digits_after_decimal>
        </y_left>
      </values>
      <legend>
        <show_date>1</show_date>
      </legend>
      <comparing>
        <recalculate_from_start>0</recalculate_from_start>
        <use_open_value_as_base>0</use_open_value_as_base>
      </comparing>
      <events/>
      <trend_lines/>
      <graphs>
        <graph>
          <bullet>round_outline</bullet>
          <data_sources>
            <close>close</close>
          </data_sources>
          <legend>
            <date title="0" key="0">{close}</date>
            <period title="0" key="0"><![CDATA[open:<b>{open}</b> low:<b>{low}</b> high:<b>{high}</b> close:<b>{close}</b>]]></period>
          </legend>
        </graph>
      </graphs>
    </chart>
  </charts>
  <data_set_selector>
    <enabled>0</enabled>
    <drop_down>
      <scroller_color>C7C7C7</scroller_color>
    </drop_down>
  </data_set_selector>
  <period_selector>
    <periods_title>Zoom:</periods_title>
    <custom_period_title>Custom period:</custom_period_title>
    <periods>
      <period pid="0" type="mm" count="10">10m</period>
      <period pid="0" type="hh" count="1">1H</period>
      <period pid="0" type="DD" count="10">10D</period>
      <period pid="1" type="MM" count="1" selected="1">1M</period>
      <period pid="2" type="MM" count="3">3M</period>
      <period pid="3" type="YYYY" count="1">1Y</period>
      <period pid="4" type="YYYY" count="3">3Y</period>
      <period pid="5" type="YTD" count="0">YTD</period>
      <period pid="6" type="MAX" count="0">MAX</period>
    </periods>
  </period_selector>
  <header>
    <enabled>0</enabled>
  </header>
  <balloon>
    <border_color>B81D1B</border_color>
  </balloon>
  <background>
    <alpha>100</alpha>
  </background>
  <scroller>
    <graph_data_source>close</graph_data_source>
    <playback>
      <enabled>1</enabled>
      <speed>3</speed>
    </playback>
  </scroller>
  <context_menu>
    <default_items>
      <print>0</print>
    </default_items>
  </context_menu>
</settings>

EOT;
    }
    
    
    public function createxml($name,$proc_id) {
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        Configure::write('debug', 0);
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        header("Content-type:text/xml");
        $filename = $this->createfile($name, $proc_id);
echo <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<settings>
  <margins>12</margins>
  <number_format>
    <letters>
      <letter number="1000">K</letter>
      <letter number="1000000">M</letter>
      <letter number="10000000">B</letter>
    </letters>
  </number_format>
  <data_sets>
    <data_set>
      <title>Chart</title>
      <short>ES</short>
      <color>003399</color>
      <file_name>{$filename}</file_name>
      <csv>
        <reverse>1</reverse>
        <separator>,</separator>
        <date_format>YYYY-MM-DD hh-mm-ss</date_format>
        <columns>
          <column>date</column>
          <column>close</column>
        </columns>
      </csv>
    </data_set>
  </data_sets>
  <charts>
    <chart>
      <title>Value</title>
      <height>60</height>
      <column_width>100</column_width>
      <grid/>
      <values>
        <x>
          <bg_color>EEEEEE</bg_color>
        </x>
        <y_left>
          <bg_color>000000</bg_color>
          <unit></unit>
          <unit_position>left</unit_position>
          <digits_after_decimal>
            <data></data>
          </digits_after_decimal>
        </y_left>
      </values>
      <legend>
        <show_date>1</show_date>
      </legend>
      <comparing>
        <recalculate_from_start>0</recalculate_from_start>
        <use_open_value_as_base>0</use_open_value_as_base>
      </comparing>
      <events/>
      <trend_lines/>
      <graphs>
        <graph>
          <bullet>round_outline</bullet>
          <data_sources>
            <close>close</close>
          </data_sources>
          <legend>
            <date title="0" key="0">{close}</date>
            <period title="0" key="0"><![CDATA[open:<b>{open}</b> low:<b>{low}</b> high:<b>{high}</b> close:<b>{close}</b>]]></period>
          </legend>
        </graph>
      </graphs>
    </chart>
  </charts>
  <data_set_selector>
    <enabled>0</enabled>
    <drop_down>
      <scroller_color>C7C7C7</scroller_color>
    </drop_down>
  </data_set_selector>
  <period_selector>
    <periods_title>Zoom:</periods_title>
    <custom_period_title>Custom period:</custom_period_title>
    <periods>
      <period pid="0" type="mm" count="10">10m</period>
      <period pid="0" type="hh" count="1">1H</period>
      <period pid="0" type="DD" count="10">10D</period>
      <period pid="1" type="MM" count="1" selected="1">1M</period>
      <period pid="2" type="MM" count="3">3M</period>
      <period pid="3" type="YYYY" count="1">1Y</period>
      <period pid="4" type="YYYY" count="3">3Y</period>
      <period pid="5" type="YTD" count="0">YTD</period>
      <period pid="6" type="MAX" count="0">MAX</period>
    </periods>
  </period_selector>
  <header>
    <enabled>0</enabled>
  </header>
  <balloon>
    <border_color>B81D1B</border_color>
  </balloon>
  <background>
    <alpha>100</alpha>
  </background>
  <scroller>
    <graph_data_source>close</graph_data_source>
    <playback>
      <enabled>1</enabled>
      <speed>3</speed>
    </playback>
  </scroller>
  <context_menu>
    <default_items>
      <print>0</print>
    </default_items>
  </context_menu>
</settings>

EOT;
    }
    
    public function createfile3($name, $ip_id,$res_ctype) {
        $ctype = 5;
        if($res_ctype == 'ingress') {
            $ctype = 5;
        } elseif ($res_ctype == 'egress') {
            $ctype = 6;
        }
        switch($name) {
            case 'carrier_stats_call':
                $filename = $this->Monitor->create_carrier_csv($ip_id, $ctype, 1);
                break;
            case 'carrier_stats_cps':
                $filename = $this->Monitor->create_carrier_csv($ip_id, $ctype, 2);
                break;
            case 'carrier_stats_acd':
                $filename = $this->Monitor->create_carrier_csv($ip_id, $ctype, 4);
                break;
            case 'carrier_stats_asr':
                $filename = $this->Monitor->create_carrier_csv($ip_id, $ctype, 3);
                break;
            case 'carrier_stats_pdd':
                $filename = $this->Monitor->create_carrier_csv($ip_id, $ctype, 5);
                break;
        }
        return $filename;
    }
    
    
    public function createxml3($name, $ip_id, $res_ctype) {
        $this->autoRender = FALSE;
        $this->autoLayout = FALSE;
        Configure::write('debug', 0);
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        header("Content-type:text/xml");
        $filename = $this->createfile3($name, $ip_id, $res_ctype);
echo <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<settings>
  <margins>12</margins>
  <number_format>
    <letters>
      <letter number="1000">K</letter>
      <letter number="1000000">M</letter>
      <letter number="10000000">B</letter>
    </letters>
  </number_format>
  <data_sets>
    <data_set>
      <title>Chart</title>
      <short>ES</short>
      <color>003399</color>
      <file_name>{$filename}</file_name>
      <csv>
        <reverse>1</reverse>
        <separator>,</separator>
        <date_format>YYYY-MM-DD hh-mm-ss</date_format>
        <columns>
          <column>date</column>
          <column>close</column>
        </columns>
      </csv>
    </data_set>
  </data_sets>
  <charts>
    <chart>
      <title>Value</title>
      <height>60</height>
      <column_width>100</column_width>
      <grid/>
      <values>
        <x>
          <bg_color>EEEEEE</bg_color>
        </x>
        <y_left>
          <bg_color>000000</bg_color>
          <unit></unit>
          <unit_position>left</unit_position>
          <digits_after_decimal>
            <data></data>
          </digits_after_decimal>
        </y_left>
      </values>
      <legend>
        <show_date>1</show_date>
      </legend>
      <comparing>
        <recalculate_from_start>0</recalculate_from_start>
        <use_open_value_as_base>0</use_open_value_as_base>
      </comparing>
      <events/>
      <trend_lines/>
      <graphs>
        <graph>
          <bullet>round_outline</bullet>
          <data_sources>
            <close>close</close>
          </data_sources>
          <legend>
            <date title="0" key="0">{close}</date>
            <period title="0" key="0"><![CDATA[open:<b>{open}</b> low:<b>{low}</b> high:<b>{high}</b> close:<b>{close}</b>]]></period>
          </legend>
        </graph>
      </graphs>
    </chart>
  </charts>
  <data_set_selector>
    <enabled>0</enabled>
    <drop_down>
      <scroller_color>C7C7C7</scroller_color>
    </drop_down>
  </data_set_selector>
  <period_selector>
    <periods_title>Zoom:</periods_title>
    <custom_period_title>Custom period:</custom_period_title>
    <periods>
      <period pid="0" type="mm" count="10">10m</period>
      <period pid="0" type="hh" count="1">1H</period>
      <period pid="0" type="DD" count="10">10D</period>
      <period pid="1" type="MM" count="1" selected="1">1M</period>
      <period pid="2" type="MM" count="3">3M</period>
      <period pid="3" type="YYYY" count="1">1Y</period>
      <period pid="4" type="YYYY" count="3">3Y</period>
      <period pid="5" type="YTD" count="0">YTD</period>
      <period pid="6" type="MAX" count="0">MAX</period>
    </periods>
  </period_selector>
  <header>
    <enabled>0</enabled>
  </header>
  <balloon>
    <border_color>B81D1B</border_color>
  </balloon>
  <background>
    <alpha>100</alpha>
  </background>
  <scroller>
    <graph_data_source>close</graph_data_source>
    <playback>
      <enabled>1</enabled>
      <speed>3</speed>
    </playback>
  </scroller>
  <context_menu>
    <default_items>
      <print>0</print>
    </default_items>
  </context_menu>
</settings>

EOT;
    }
    
}

?>
