
    <link rel="shortcut Icon" type="image/x-icon" href="/static/favicon.ico" />
    
    <link type="text/css" rel="stylesheet" media="all" href="<?php echo $this->webroot?>css/base.css?v-3.0.6" />
    <link type="text/css" rel="stylesheet" media="all" href="<?php echo $this->webroot?>css/main.css?v-3.0.6" />
    <link type="text/css" rel="stylesheet" media="all" href="<?php echo $this->webroot?>css/shared.css?v-3.0.6" />

    <link type="text/css" rel="stylesheet" media="all" href="<?php echo $this->webroot?>css/jquery.jgrowl.css?v-3.0.6" />
    <link type="text/css" rel="stylesheet" media="print" href="<?php echo $this->webroot?>css/print.css?v-3.0.6" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>calendar/calendar.css" />
        <link type="text/css" rel="stylesheet" media="all" href="<?php echo $this->webroot?>css/styles.css?v-3.0.6" />
        
    <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery-1.4.1.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.jgrowl.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.tooltip.js"></script>

    <script type="text/javascript" src="<?php echo $this->webroot?>js/bb-functions.js?v-3.0.6"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/bb-interface.js?v-3.0.6"></script>
    
    <script type="text/javascript">
    //<![CDATA[
    var currentTime = 1274507074;
    var L = {"loadingPanel":"Please Wait...","deleteConfirm":"Are you sure you want to delete this item?","hide-all":"hide all"};
    //]]>
    </script>
    
    <script type="text/javascript" src="<?php echo $this->webroot?>calendar/calendar.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>calendar/calendar-setup.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>calendar/calendar-en.js"></script>
<?php  //**********************************************标题头 ***************************************************?>
<div id="title">
            <h1>
        <?php echo __('Management',true);?>&gt;&gt;
        <?php echo __('Clients',true);?>        
                                    <a 
href="http://yht.demo.denovolab.com/admin/_view/sbRemove?link=/clients/list"
 title="remove from smartbar"><img src="<?php echo $this->webroot?>images/qb-minus.png" alt="-"
 height="10" width="10"></a>
                        </h1>
        
    <ul id="title-search">
        <li><form><input name="search[_q]" value="Search..." 
title="Search..." class="in-search default-value input in-text 
defaultText" id="search-_q" type="text"></form></li>
        <li style="display: list-item;" id="title-search-adv" 
onclick="advSearchToggle();" title="advanced search »"></li>
    </ul>
    
        <ul id="title-menu">
                                        <li><a class="link_btn" 
href="http://yht.demo.denovolab.com/admin/clients/list"><img 
src="<?php echo $this->webroot?>images/list.png" alt="" height="16" width="16"> <?php __('List Items')?></a></li>
                                        <li><a class="link_btn" 
href="http://yht.demo.denovolab.com/admin/clients/csvExport"><img 
src="<?php echo $this->webroot?>images/export.png" alt="" height="16" width="16"> <?php __('Export to CSV')?></a></li>
                                        <li><a  class="link_btn"
href="http://yht.demo.denovolab.com/admin/clients/addForm" 
rel="popup"><img src="<?php echo $this->webroot?>images/add.png" alt="" height="16" width="16">
 <?php __('Create New')?></a></li>
        </ul>
  </div>
    
    
    
    
    
    
    
    
    
    
    
    
    <?php  //**********************************************页面主体***************************************************?>
<div id="container">
    <!-- DYNAMIC -->
    <script type="text/javascript">var smartSearch = 2;</script>

<fieldset id="advsearch" class="title-block"><form>
<table>
<tbody><tr>
    <td><label><?php echo __('name',true);?>:</label><input class="input in-text" 
name="search[c.name_any]" id="search-cname_any" type="text"></td>
    <td><label><?php echo __('Group',true);?>:</label><select class="input in-select" 
name="search[id_groups]" id="search-id_groups"><option 
selected="selected" value=""></option><option value="1">Customers</option><option
 value="2">Providers</option><option value="10">Cards</option><option 
value="-1">Call Shop</option><option value="13">Resellers</option></select></td>
    <td><label><?php echo __('ORIG Rate table',true);?>:</label><select 
name="search[c.orig_rate_table]" class="wide input in-select" 
id="search-corig_rate_table"><option selected="selected" value=""></option><option
 value="4">123</option><option value="112">AVC</option><option 
value="71">Compassglobal_OUT</option><option value="2">ORIG RT for 1234</option><option
 value="87">ORIG RT for Ahlopan Kardebaletkin</option><option value="41">ORIG
 RT for Billing System Owner / R</option><option value="62">ORIG RT for 
CW COR</option><option value="59">ORIG RT for Rede Sol Express</option><option
 value="92">ORIG RT for byz</option><option value="76">Premium</option><option
 value="64">Rama Tulik</option><option value="17">Rate Table 22</option><option
 value="22">Rate Table 23</option><option value="25">Rate Table 24</option><option
 value="33">Rate Table 25</option><option value="35">Rate Table 27</option><option
 value="16">Rate Table 34</option><option value="39">Rate Table 35</option><option
 value="40">Rate Table 36</option><option value="15">Rate Table 39</option><option
 value="44">Rate Table 40</option><option value="42">Rate Table 41</option><option
 value="46">Rate Table 42</option><option value="43">Rate Table 45</option><option
 value="34">Rate Table 51</option><option value="21">Rate Table 52</option><option
 value="20">Rate Table 53</option><option value="29">Rate Table 58</option><option
 value="30">Rate Table 59</option><option value="26">Rate Table 60</option><option
 value="31">Rate Table 61</option><option value="24">Rate Table 63</option><option
 value="54">Rate Table 67</option><option value="73">Roman TERM</option><option
 value="88">TERM RT for Ahlopan Kardebaletkin</option><option value="85">TERM
 RT for Alex Zhuk Navozni</option><option value="89">TERM RT for Babah 
Armagedon</option><option value="9">TERM RT for CW COR</option><option 
value="8">TERM RT for IGUANA SYSTEMS</option><option value="63">TERM RT 
for OK TEL</option><option value="60">TERM RT for Rede Sol Express</option><option
 value="91">TERM RT for byz</option><option value="65">TGA BLUE</option><option
 value="110">Test</option><option value="68">Wholesale</option><option 
value="105">aaa</option><option value="106">aaaf</option><option 
value="109">aabbb</option><option value="111">ccrate</option><option 
value="81">demo</option><option value="104">dsf</option><option 
value="103">jnm,</option><option value="107">jujki</option><option 
value="108">lll</option><option value="98">nico</option><option 
value="102">proba</option><option value="101">proba1</option><option 
value="97">test</option><option value="99">test1</option><option 
value="100">test2</option><option value="74">tlsq</option><option 
value="6">vipcall</option><option value="1">zone1</option></select></td>
    <td><label><?php echo __('Account',true);?>:</label><input class="input in-text" 
name="search[acc.name]" id="search-accname" type="text"></td>
    <td><label><?php echo __('DR Policy',true);?>:</label><select class="input in-select" 
name="search[c.dr_policy]" id="search-cdr_policy"><option 
selected="selected" value=""></option><option value="ASR">Simple Quality</option><option
 value="LCR">Simple LCR</option><option value="PROPORTIONAL">Proportional</option><option
 value="COMBINED_ASR">Complex Quality</option><option 
value="COMBINED_LCR">Complex LCR</option><option value="null">Default</option></select></td>
    <td class="buttons"><input class="input in-submit" value="Search" 
type="submit"></td>
</tr>
<tr>
    <td><label><?php echo __('Reseller',true);?>:</label><select class="input in-select" 
name="search[c.id_companies]" id="search-cid_companies"><option 
selected="selected" value=""></option><option value="24">»&nbsp;Excila</option><option
 value="11">&nbsp;&nbsp;&nbsp;»&nbsp;COATEL S.A.</option><option 
value="14">»&nbsp;GaGa 123</option><option value="19">»&nbsp;MIDI 
Telecom</option><option value="27">»&nbsp;Reseller Test</option><option 
value="1">»&nbsp;TGA Corporation</option><option value="5">&nbsp;&nbsp;&nbsp;»&nbsp;OK
 TEL</option><option value="8">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;»&nbsp;Billing
 System Owner</option><option value="4">&nbsp;&nbsp;&nbsp;»&nbsp;Rede 
Sol Express</option><option value="7">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;»&nbsp;Ephraim</option><option
 value="26">»&nbsp;Tikal</option><option value="28">»&nbsp;VOIP 
technologies</option><option value="10">»&nbsp;Vipcarrier</option><option
 value="15">»&nbsp;Voip Call</option><option value="41">»&nbsp;cctest</option><option
 value="42">»&nbsp;fdafeww</option><option value="22">»&nbsp;kkk</option><option
 value="21">»&nbsp;test12345</option><option value="32">»&nbsp;testest</option><option
 value="30">»&nbsp;wwrrhh</option></select></td>
    <td><label><?php echo __('status',true);?>:</label><select class="input in-select" 
name="search[c.status]" id="search-cstatus"><option selected="selected" 
value=""></option><option value="active">Active</option><option 
value="stopped">Stop</option><option value="deleted">Deleted</option></select></td>
    <td><label>TERM Rate table:</label><select 
name="search[c.term_rate_table]" class="wide input in-select" 
id="search-cterm_rate_table"><option selected="selected" value=""></option><option
 value="4">123</option><option value="112">AVC</option><option 
value="71">Compassglobal_OUT</option><option value="2">ORIG RT for 1234</option><option
 value="87">ORIG RT for Ahlopan Kardebaletkin</option><option value="41">ORIG
 RT for Billing System Owner / R</option><option value="62">ORIG RT for 
CW COR</option><option value="59">ORIG RT for Rede Sol Express</option><option
 value="92">ORIG RT for byz</option><option value="76">Premium</option><option
 value="64">Rama Tulik</option><option value="17">Rate Table 22</option><option
 value="22">Rate Table 23</option><option value="25">Rate Table 24</option><option
 value="33">Rate Table 25</option><option value="35">Rate Table 27</option><option
 value="16">Rate Table 34</option><option value="39">Rate Table 35</option><option
 value="40">Rate Table 36</option><option value="15">Rate Table 39</option><option
 value="44">Rate Table 40</option><option value="42">Rate Table 41</option><option
 value="46">Rate Table 42</option><option value="43">Rate Table 45</option><option
 value="34">Rate Table 51</option><option value="21">Rate Table 52</option><option
 value="20">Rate Table 53</option><option value="29">Rate Table 58</option><option
 value="30">Rate Table 59</option><option value="26">Rate Table 60</option><option
 value="31">Rate Table 61</option><option value="24">Rate Table 63</option><option
 value="54">Rate Table 67</option><option value="73">Roman TERM</option><option
 value="88">TERM RT for Ahlopan Kardebaletkin</option><option value="85">TERM
 RT for Alex Zhuk Navozni</option><option value="89">TERM RT for Babah 
Armagedon</option><option value="9">TERM RT for CW COR</option><option 
value="8">TERM RT for IGUANA SYSTEMS</option><option value="63">TERM RT 
for OK TEL</option><option value="60">TERM RT for Rede Sol Express</option><option
 value="91">TERM RT for byz</option><option value="65">TGA BLUE</option><option
 value="110">Test</option><option value="68">Wholesale</option><option 
value="105">aaa</option><option value="106">aaaf</option><option 
value="109">aabbb</option><option value="111">ccrate</option><option 
value="81">demo</option><option value="104">dsf</option><option 
value="103">jnm,</option><option value="107">jujki</option><option 
value="108">lll</option><option value="98">nico</option><option 
value="102">proba</option><option value="101">proba1</option><option 
value="97">test</option><option value="99">test1</option><option 
value="100">test2</option><option value="74">tlsq</option><option 
value="6">vipcall</option><option value="1">zone1</option></select></td>
    <td><label><?php echo __('Account IP',true);?>:</label><input class="input in-text" 
name="search[accip.address]" id="search-accipaddress" type="text"></td>
    <td><label><?php echo __('Route within groups',true);?>:</label><select class="input 
in-select" name="search[c.dr_ingroups]" id="search-cdr_ingroups"><option
 selected="selected" value=""></option><option value="1">Enabled</option><option
 value="0">Disabled</option></select></td>
</tr>
</tbody></table>
</form></fieldset>


<ul class="list-meta">
    <li class="list-meta-info">Rows 1 - 20 from 57</li>
        <li class="list-meta-plist"><b>1</b><a 
href="http://yht.demo.denovolab.com/admin/clients/list?page=2">2</a><a
 href="http://yht.demo.denovolab.com/admin/clients/list?page=3">3</a><a
 href="http://yht.demo.denovolab.com/admin/clients/list?page=2" 
class="page-next">&nbsp;»&nbsp;</a><a 
href="http://yht.demo.denovolab.com/admin/clients/list?page=3" 
class="page-last">&nbsp;»|&nbsp;</a></li>
    <li class="list-meta-pnum">Pages: <span>3</span></li>
    <li class="list-meta-pgo"><form>
                Go: <input class="input in-text" name="page" value="1" 
id="page" type="text">    </form></li>
    </ul>
<table class="list">
<col width="6%">
<col width="18%">
<col width="2%">
<col width="2%">
<col width="2%">
<col width="14%">
<col width="10%">
<col width="10%">
<col width="2%">
<col width="2%">
<col width="2%">
<col width="13%">
<col width="2%">
<col width="13%">
<col width="2%">
<thead>
<tr>
    <td rowspan="2"><a 
href="http://yht.demo.denovolab.com/admin/clients/list?orderby=id"><img
 src="<?php echo $this->webroot?>images/list-sort-asc.png" height="10" width="10"></a>&nbsp;<?php echo __('id',true);?>&nbsp;<a
 
href="http://yht.demo.denovolab.com/admin/clients/list?orderby=id_desc"><img
 src="<?php echo $this->webroot?>images/list-sort-desc.png" height="10" width="10"></a></td>
    <td rowspan="2"><img src="<?php echo $this->webroot?>images/list-sort-asc-x.png" 
height="10" width="10">&nbsp;Name&nbsp;<a 
href="http://yht.demo.denovolab.com/admin/clients/list?orderby=c.name_desc"><img
 src="<?php echo $this->webroot?>images/list-sort-desc.png" height="10" width="10"></a></td>
    <td rowspan="2">&nbsp;</td>
    <td rowspan="2">&nbsp;</td>
    <td rowspan="2">&nbsp;</td>
    <td rowspan="2"><a 
href="http://yht.demo.denovolab.com/admin/clients/list?orderby=com.name"><img
 src="<?php echo $this->webroot?>images/list-sort-asc.png" height="10" width="10"></a>&nbsp;Reseller&nbsp;<a
 
href="http://yht.demo.denovolab.com/admin/clients/list?orderby=com.name_desc"><img
 src="<?php echo $this->webroot?>images/list-sort-desc.png" height="10" width="10"></a></td>
    <td colspan="3"><?php echo __('Balance',true);?></td>
    <td rowspan="2">&nbsp;</td>
    <td colspan="4"><?php echo __('Rates',true);?></td>
    <td class="last" rowspan="2">&nbsp;</td>
</tr>
<tr>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/list?orderby=c.balance_accountant"><img
 src="<?php echo $this->webroot?>images/list-sort-asc.png" height="10" width="10"></a>&nbsp;<span
 id="ht-100001" class="helptip" rel="helptip"><?php echo __('Mutual',true);?></span><span 
id="ht-100001-tooltip" class="tooltip">Client's balance calculated on 
basis of the outstanding invoices and performed payments</span>&nbsp;<a 
href="http://yht.demo.denovolab.com/admin/clients/list?orderby=c.balance_accountant_desc"><img
 src="<?php echo $this->webroot?>images/list-sort-desc.png" height="10" width="10"></a></td>
    <td colspan="2"><a 
href="http://yht.demo.denovolab.com/admin/clients/list?orderby=c.balance"><img
 src="<?php echo $this->webroot?>images/list-sort-asc.png" height="10" width="10"></a>&nbsp;<span
 id="ht-100002" class="helptip" rel="helptip"><?php echo __('Current',true);?></span><span 
id="ht-100002-tooltip" class="tooltip">Client's balance calculated on 
basis of performed payments and processed calls</span>&nbsp;<a 
href="http://yht.demo.denovolab.com/admin/clients/list?orderby=c.balance_desc"><img
 src="<?php echo $this->webroot?>images/list-sort-desc.png" height="10" width="10"></a></td>
    <td colspan="2"><a 
href="http://yht.demo.denovolab.com/admin/clients/list?orderby=orig_rate_table"><img
 src="<?php echo $this->webroot?>images/list-sort-asc.png" height="10" width="10"></a>&nbsp;<span
 id="ht-100003" class="helptip" rel="helptip"><?php echo __('ORIG',true);?></span><span 
id="ht-100003-tooltip" class="tooltip">Base rate on incoming calls</span>&nbsp;<a
 
href="http://yht.demo.denovolab.com/admin/clients/list?orderby=orig_rate_table_desc"><img
 src="<?php echo $this->webroot?>images/list-sort-desc.png" height="10" width="10"></a></td>
    <td class="last" colspan="2"><a 
href="http://yht.demo.denovolab.com/admin/clients/list?orderby=term_rate_table"><img
 src="<?php echo $this->webroot?>images/list-sort-asc.png" height="10" width="10"></a>&nbsp;<span
 id="ht-100004" class="helptip" rel="helptip"><?php echo __('TERM',true);?></span><span 
id="ht-100004-tooltip" class="tooltip">Base rate on outgoing calls</span>&nbsp;<a
 
href="http://yht.demo.denovolab.com/admin/clients/list?orderby=term_rate_table_desc"><img
 src="<?php echo $this->webroot?>images/list-sort-desc.png" height="10" width="10"></a></td>
</tr>   
</thead>
<tbody>
<tr class="s-active nobalance row-1">
    <td align="right">32784</td>
    <td id="ci-32784" rel="tooltip"><b><a 
href="http://yht.demo.denovolab.com/admin/clients/editForm?id=32784"
 rel="popup">32432434</a></b></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/accountsList?id_clients=32784"
 rel="popup" title="Edit accounts"><img src="<?php echo $this->webroot?>images/bAccounts.gif" 
alt="Edit accounts" height="16" width="16"></a></td>    
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/routesList?id_clients=32784"
 rel="popup" title="Edit routing presets"><img src="<?php echo $this->webroot?>images/bDR.gif" 
alt="Edit routing presets" height="16" width="16"></a></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/clientLogin?id=32784"
 target="_blank" title="Login as user to client panel"><img 
src="<?php echo $this->webroot?>images/bLogins.gif" height="16" width="16"></a></td>
    <td><?php echo __('OK TEL',true);?></td>

    <td class="zero" align="right">0.00</td>
    <td class="zero" align="right">0.00</td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/payments/list?adv=1&amp;search[p.id_clients_eq]=32784&amp;search[client_name]=32432434"
 title="Balance operations"><img src="<?php echo $this->webroot?>images/balanceOperations.gif" 
height="16" width="16"></a></td>
    
    <td id="pi-32784" rel="tooltip"><a 
href="http://yht.demo.denovolab.com/admin/clients/productsList?id_clients=32784"
 rel="popup" title="This client's assigned products"><img 
src="<?php echo $this->webroot?>images/bProducts.gif" height="16" width="16"></a></td>

                        <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=109"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bOrigTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=109"
 rel="popup" title="Edit rate table">aabbb</a></small></td>
                
                    <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/addForm?id_clients=32784&amp;origin=term&amp;id_companies=5&amp;id_currencies=&amp;name=TERM+RT+-+32432434"
 rel="popup" title="Click here to add custom Termination rate table"><img
 src="<?php echo $this->webroot?>images/bTermTariffs_disabled.gif" alt="Click here to add 
custom Termination rate table" height="16" width="16"></a></td>
            <td align="left">&nbsp;</td>
                
    <td class="last"><a 
href="http://yht.demo.denovolab.com/admin/clients/delete?id=32784" 
rel="popup delete"><i class="icon-remove"></i></a></td>
</tr>
<tr class="s-active nobalance row-2">
    <td align="right">32747</td>
    <td id="ci-32747" rel="tooltip"><b><a 
href="http://yht.demo.denovolab.com/admin/clients/editForm?id=32747"
 rel="popup">A1 I am the boss</a></b></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/accountsList?id_clients=32747"
 rel="popup" title="Edit accounts"><img src="<?php echo $this->webroot?>images/bAccounts.gif" 
alt="Edit accounts" height="16" width="16"></a></td>    
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/routesList?id_clients=32747"
 rel="popup" title="Edit routing presets"><img src="<?php echo $this->webroot?>images/bDR.gif" 
alt="Edit routing presets" height="16" width="16"></a></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/clientLogin?id=32747"
 target="_blank" title="Login as user to client panel"><img 
src="<?php echo $this->webroot?>images/bLogins_disabled.gif" height="16" width="16"></a></td>
    <td>GaGa 123</td>

    <td class="neg" align="right">-7 342.00</td>
    <td class="neg" align="right">-6 666.00</td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/payments/list?adv=1&amp;search[p.id_clients_eq]=32747&amp;search[client_name]=A1%20I%20am%20the%20boss"
 title="Balance operations"><img src="<?php echo $this->webroot?>images/balanceOperations.gif" 
height="16" width="16"></a></td>
    
    <td id="pi-32747"><a 
href="http://yht.demo.denovolab.com/admin/clients/productsList?id_clients=32747"
 rel="popup" title="This client's assigned products"><img 
src="<?php echo $this->webroot?>images/bProducts_disabled.gif" height="16" width="16"></a></td>

                        <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=33"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bOrigTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=33"
 rel="popup" title="Edit rate table">Rate Table 25</a></small></td>
                
                    <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=33"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bTermTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=33"
 rel="popup" title="Edit rate table">Rate Table 25</a></small></td>
                
    <td class="last"><a 
href="http://yht.demo.denovolab.com/admin/clients/delete?id=32747" 
rel="popup delete"><i class="icon-remove"></i></a></td>
</tr>
<tr class="s-active nobalance row-1">
    <td align="right">21628</td>
    <td id="ci-21628" rel="tooltip"><b><a 
href="http://yht.demo.denovolab.com/admin/clients/editForm?id=21628"
 rel="popup">Ahlopan Kardebaletkin</a></b></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/accountsList?id_clients=21628"
 rel="popup" title="Edit accounts"><img src="<?php echo $this->webroot?>images/bAccounts.gif" 
alt="Edit accounts" height="16" width="16"></a></td>    
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/routesList?id_clients=21628"
 rel="popup" title="Edit routing presets"><img src="<?php echo $this->webroot?>images/bDR.gif" 
alt="Edit routing presets" height="16" width="16"></a></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/clientLogin?id=21628"
 target="_blank" title="Login as user to client panel"><img 
src="<?php echo $this->webroot?>images/bLogins.gif" height="16" width="16"></a></td>
    <td>OK TEL</td>

    <td class="zero" align="right">0.00</td>
    <td class="zero" align="right">0.00</td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/payments/list?adv=1&amp;search[p.id_clients_eq]=21628&amp;search[client_name]=Ahlopan%20Kardebaletkin"
 title="Balance operations"><img src="<?php echo $this->webroot?>images/balanceOperations.gif" 
height="16" width="16"></a></td>
    
    <td id="pi-21628" rel="tooltip"><a 
href="http://yht.demo.denovolab.com/admin/clients/productsList?id_clients=21628"
 rel="popup" title="This client's assigned products"><img 
src="<?php echo $this->webroot?>images/bProducts.gif" height="16" width="16"></a></td>

                        <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=87"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bOrigTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=87"
 rel="popup" title="Edit rate table">ORIG RT for Ahlopan Kardebaletkin</a></small></td>
                
                    <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=88"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bTermTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=88"
 rel="popup" title="Edit rate table">TERM RT for Ahlopan Kardebaletkin</a></small></td>
                
    <td class="last"><a 
href="http://yht.demo.denovolab.com/admin/clients/delete?id=21628" 
rel="popup delete"><i class="icon-remove"></i></a></td>
</tr>
<tr class="s-active nobalance row-2 row-hover">
    <td align="right">21634</td>
    <td id="ci-21634" rel="tooltip"><b><a 
href="http://yht.demo.denovolab.com/admin/clients/editForm?id=21634"
 rel="popup">Alex Zhuk Navozni</a></b></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/accountsList?id_clients=21634"
 rel="popup" title="Edit accounts"><img src="<?php echo $this->webroot?>images/bAccounts.gif" 
alt="Edit accounts" height="16" width="16"></a></td>    
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/routesList?id_clients=21634"
 rel="popup" title="Edit routing presets"><img src="<?php echo $this->webroot?>images/bDR.gif" 
alt="Edit routing presets" height="16" width="16"></a></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/clientLogin?id=21634"
 target="_blank" title="Login as user to client panel"><img 
src="<?php echo $this->webroot?>images/bLogins.gif" height="16" width="16"></a></td>
    <td>test12345</td>

    <td class="neg" align="right">-22 120.00</td>
    <td class="zero" align="right">0.00</td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/payments/list?adv=1&amp;search[p.id_clients_eq]=21634&amp;search[client_name]=Alex%20Zhuk%20Navozni"
 title="Balance operations"><img src="<?php echo $this->webroot?>images/balanceOperations.gif" 
height="16" width="16"></a></td>
    
    <td id="pi-21634"><a 
href="http://yht.demo.denovolab.com/admin/clients/productsList?id_clients=21634"
 rel="popup" title="This client's assigned products"><img 
src="<?php echo $this->webroot?>images/bProducts_disabled.gif" height="16" width="16"></a></td>

                        <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/addForm?id_clients=21634&amp;origin=orig&amp;id_companies=21&amp;id_currencies=&amp;name=ORIG+RT+-+Alex+Zhuk+Navozni"
 rel="popup" title="Click here to add custom Origination rate table"><img
 src="<?php echo $this->webroot?>images/bOrigTariffs_disabled.gif" alt="Click here to add 
custom Origination rate table" height="16" width="16"></a></td>
            <td>&nbsp;</td>
                
                    <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=85"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bTermTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=85"
 rel="popup" title="Edit rate table">TERM RT for Alex Zhuk Navozni</a></small></td>
                
    <td class="last"><a 
href="http://yht.demo.denovolab.com/admin/clients/delete?id=21634" 
rel="popup delete"><i class="icon-remove"></i></a></td>
</tr>
<tr class="s-active row-1">
    <td align="right">21626</td>
    <td id="ci-21626" rel="tooltip"><b><a 
href="http://yht.demo.denovolab.com/admin/clients/editForm?id=21626"
 rel="popup">Babah Armagedon</a></b></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/accountsList?id_clients=21626"
 rel="popup" title="Edit accounts"><img src="<?php echo $this->webroot?>images/bAccounts.gif" 
alt="Edit accounts" height="16" width="16"></a></td>    
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/routesList?id_clients=21626"
 rel="popup" title="Edit routing presets"><img src="<?php echo $this->webroot?>images/bDR.gif" 
alt="Edit routing presets" height="16" width="16"></a></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/clientLogin?id=21626"
 target="_blank" title="Login as user to client panel"><img 
src="<?php echo $this->webroot?>images/bLogins_disabled.gif" height="16" width="16"></a></td>
    <td>GaGa 123</td>

    <td class="pos" align="right">1 742.00</td>
    <td class="pos" align="right">975.00</td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/payments/list?adv=1&amp;search[p.id_clients_eq]=21626&amp;search[client_name]=Babah%20Armagedon"
 title="Balance operations"><img src="<?php echo $this->webroot?>images/balanceOperations.gif" 
height="16" width="16"></a></td>
    
    <td id="pi-21626" rel="tooltip"><a 
href="http://yht.demo.denovolab.com/admin/clients/productsList?id_clients=21626"
 rel="popup" title="This client's assigned products"><img 
src="<?php echo $this->webroot?>images/bProducts.gif" height="16" width="16"></a></td>

                        <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/addForm?id_clients=21626&amp;origin=orig&amp;id_companies=14&amp;id_currencies=&amp;name=ORIG+RT+-+Babah+Armagedon"
 rel="popup" title="Click here to add custom Origination rate table"><img
 src="<?php echo $this->webroot?>images/bOrigTariffs_disabled.gif" alt="Click here to add 
custom Origination rate table" height="16" width="16"></a></td>
            <td>&nbsp;</td>
                
                    <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=89"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bTermTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=89"
 rel="popup" title="Edit rate table">TERM RT for Babah Armagedon</a></small></td>
                
    <td class="last"><a 
href="http://yht.demo.denovolab.com/admin/clients/delete?id=21626" 
rel="popup delete"><i class="icon-remove"></i></a></td>
</tr>
<tr class="s-active row-2">
    <td align="right">32</td>
    <td id="ci-32" rel="tooltip"><b><a 
href="http://yht.demo.denovolab.com/admin/clients/editForm?id=32" 
rel="popup">Bungary idiots</a></b></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/accountsList?id_clients=32"
 rel="popup" title="Edit accounts"><img src="<?php echo $this->webroot?>images/bAccounts.gif" 
alt="Edit accounts" height="16" width="16"></a></td>    
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/routesList?id_clients=32"
 rel="popup" title="Edit routing presets"><img src="<?php echo $this->webroot?>images/bDR.gif" 
alt="Edit routing presets" height="16" width="16"></a></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/clientLogin?id=32"
 target="_blank" title="Login as user to client panel"><img 
src="<?php echo $this->webroot?>images/bLogins_disabled.gif" height="16" width="16"></a></td>
    <td>TGA Corporation</td>

    <td class="neg" align="right">-10 408.84</td>
    <td class="neg" align="right">-10 408.80</td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/payments/list?adv=1&amp;search[p.id_clients_eq]=32&amp;search[client_name]=Bungary%20idiots"
 title="Balance operations"><img src="<?php echo $this->webroot?>images/balanceOperations.gif" 
height="16" width="16"></a></td>
    
    <td id="pi-32" rel="tooltip"><a 
href="http://yht.demo.denovolab.com/admin/clients/productsList?id_clients=32"
 rel="popup" title="This client's assigned products"><img 
src="<?php echo $this->webroot?>images/bProducts.gif" height="16" width="16"></a></td>

                        <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=43"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bOrigTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=43"
 rel="popup" title="Edit rate table">Rate Table 45</a></small></td>
                
                    <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/addForm?id_clients=32&amp;origin=term&amp;id_companies=1&amp;id_currencies=&amp;name=TERM+RT+-+Bungary+idiots"
 rel="popup" title="Click here to add custom Termination rate table"><img
 src="<?php echo $this->webroot?>images/bTermTariffs_disabled.gif" alt="Click here to add 
custom Termination rate table" height="16" width="16"></a></td>
            <td align="left">&nbsp;</td>
                
    <td class="last"><a 
href="http://yht.demo.denovolab.com/admin/clients/delete?id=32" 
rel="popup delete"><i class="icon-remove"></i></a></td>
</tr>
<tr class="s-active row-1">
    <td align="right">55</td>
    <td id="ci-55" rel="tooltip"><b><a 
href="http://yht.demo.denovolab.com/admin/clients/editForm?id=55" 
rel="popup">CW CORan</a></b></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/accountsList?id_clients=55"
 rel="popup" title="Edit accounts"><img src="<?php echo $this->webroot?>images/bAccounts.gif" 
alt="Edit accounts" height="16" width="16"></a></td>    
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/routesList?id_clients=55"
 rel="popup" title="Edit routing presets"><img src="<?php echo $this->webroot?>images/bDR.gif" 
alt="Edit routing presets" height="16" width="16"></a></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/clientLogin?id=55"
 target="_blank" title="Login as user to client panel"><img 
src="<?php echo $this->webroot?>images/bLogins.gif" height="16" width="16"></a></td>
    <td>TGA Corporation</td>

    <td class="pos" align="right">465.00</td>
    <td class="pos" align="right">2 000.00</td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/payments/list?adv=1&amp;search[p.id_clients_eq]=55&amp;search[client_name]=CW%20CORan"
 title="Balance operations"><img src="<?php echo $this->webroot?>images/balanceOperations.gif" 
height="16" width="16"></a></td>
    
    <td id="pi-55"><a 
href="http://yht.demo.denovolab.com/admin/clients/productsList?id_clients=55"
 rel="popup" title="This client's assigned products"><img 
src="<?php echo $this->webroot?>images/bProducts_disabled.gif" height="16" width="16"></a></td>

                        <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=62"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bOrigTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=62"
 rel="popup" title="Edit rate table">ORIG RT for CW COR</a></small></td>
                
                    <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=9"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bTermTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=9"
 rel="popup" title="Edit rate table">TERM RT for CW COR</a></small></td>
                
    <td class="last"><a 
href="http://yht.demo.denovolab.com/admin/clients/delete?id=55" 
rel="popup delete"><i class="icon-remove"></i></a></td>
</tr>
<tr class="s-active row-2">
    <td align="right">32760</td>
    <td id="ci-32760" rel="tooltip"><b><a 
href="http://yht.demo.denovolab.com/admin/clients/editForm?id=32760"
 rel="popup">Call Booth 1</a></b></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/accountsList?id_clients=32760"
 rel="popup" title="Edit accounts"><img src="<?php echo $this->webroot?>images/bAccounts.gif" 
alt="Edit accounts" height="16" width="16"></a></td>    
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/routesList?id_clients=32760"
 rel="popup" title="Edit routing presets"><img src="<?php echo $this->webroot?>images/bDR.gif" 
alt="Edit routing presets" height="16" width="16"></a></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/clientLogin?id=32760"
 target="_blank" title="Login as user to client panel"><img 
src="<?php echo $this->webroot?>images/bLogins.gif" height="16" width="16"></a></td>
    <td>COATEL S.A.</td>

    <td class="zero" align="right">0.00</td>
    <td class="zero" align="right">0.00</td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/payments/list?adv=1&amp;search[p.id_clients_eq]=32760&amp;search[client_name]=Call%20Booth%201"
 title="Balance operations"><img src="<?php echo $this->webroot?>images/balanceOperations.gif" 
height="16" width="16"></a></td>
    
    <td id="pi-32760" rel="tooltip"><a 
href="http://yht.demo.denovolab.com/admin/clients/productsList?id_clients=32760"
 rel="popup" title="This client's assigned products"><img 
src="<?php echo $this->webroot?>images/bProducts.gif" height="16" width="16"></a></td>

                        <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=17"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bOrigTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=17"
 rel="popup" title="Edit rate table">Rate Table 22</a></small></td>
                
                    <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/addForm?id_clients=32760&amp;origin=term&amp;id_companies=11&amp;id_currencies=&amp;name=TERM+RT+-+Call+Booth+1"
 rel="popup" title="Click here to add custom Termination rate table"><img
 src="<?php echo $this->webroot?>images/bTermTariffs_disabled.gif" alt="Click here to add 
custom Termination rate table" height="16" width="16"></a></td>
            <td align="left">&nbsp;</td>
                
    <td class="last"><a 
href="http://yht.demo.denovolab.com/admin/clients/delete?id=32760" 
rel="popup delete"><i class="icon-remove"></i></a></td>
</tr>
<tr class="s-active nobalance row-1">
    <td align="right">32761</td>
    <td id="ci-32761" rel="tooltip"><b><a 
href="http://yht.demo.denovolab.com/admin/clients/editForm?id=32761"
 rel="popup">Call Booth 2</a></b></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/accountsList?id_clients=32761"
 rel="popup" title="Edit accounts"><img src="<?php echo $this->webroot?>images/bAccounts.gif" 
alt="Edit accounts" height="16" width="16"></a></td>    
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/routesList?id_clients=32761"
 rel="popup" title="Edit routing presets"><img src="<?php echo $this->webroot?>images/bDR.gif" 
alt="Edit routing presets" height="16" width="16"></a></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/clientLogin?id=32761"
 target="_blank" title="Login as user to client panel"><img 
src="<?php echo $this->webroot?>images/bLogins_disabled.gif" height="16" width="16"></a></td>
    <td>COATEL S.A.</td>

    <td class="zero" align="right">0.00</td>
    <td class="zero" align="right">0.00</td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/payments/list?adv=1&amp;search[p.id_clients_eq]=32761&amp;search[client_name]=Call%20Booth%202"
 title="Balance operations"><img src="<?php echo $this->webroot?>images/balanceOperations.gif" 
height="16" width="16"></a></td>
    
    <td id="pi-32761"><a 
href="http://yht.demo.denovolab.com/admin/clients/productsList?id_clients=32761"
 rel="popup" title="This client's assigned products"><img 
src="<?php echo $this->webroot?>images/bProducts_disabled.gif" height="16" width="16"></a></td>

                        <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=25"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bOrigTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=25"
 rel="popup" title="Edit rate table">Rate Table 24</a></small></td>
                
                    <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/addForm?id_clients=32761&amp;origin=term&amp;id_companies=11&amp;id_currencies=&amp;name=TERM+RT+-+Call+Booth+2"
 rel="popup" title="Click here to add custom Termination rate table"><img
 src="<?php echo $this->webroot?>images/bTermTariffs_disabled.gif" alt="Click here to add 
custom Termination rate table" height="16" width="16"></a></td>
            <td align="left">&nbsp;</td>
                
    <td class="last"><a 
href="http://yht.demo.denovolab.com/admin/clients/delete?id=32761" 
rel="popup delete"><i class="icon-remove"></i></a></td>
</tr>
<tr class="s-active row-2">
    <td align="right">32762</td>
    <td id="ci-32762" rel="tooltip"><b><a 
href="http://yht.demo.denovolab.com/admin/clients/editForm?id=32762"
 rel="popup">Call Booth 3</a></b></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/accountsList?id_clients=32762"
 rel="popup" title="Edit accounts"><img src="<?php echo $this->webroot?>images/bAccounts.gif" 
alt="Edit accounts" height="16" width="16"></a></td>    
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/routesList?id_clients=32762"
 rel="popup" title="Edit routing presets"><img src="<?php echo $this->webroot?>images/bDR.gif" 
alt="Edit routing presets" height="16" width="16"></a></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/clientLogin?id=32762"
 target="_blank" title="Login as user to client panel"><img 
src="<?php echo $this->webroot?>images/bLogins.gif" height="16" width="16"></a></td>
    <td>COATEL S.A.</td>

    <td class="zero" align="right">0.00</td>
    <td class="zero" align="right">0.00</td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/payments/list?adv=1&amp;search[p.id_clients_eq]=32762&amp;search[client_name]=Call%20Booth%203"
 title="Balance operations"><img src="<?php echo $this->webroot?>images/balanceOperations.gif" 
height="16" width="16"></a></td>
    
    <td id="pi-32762"><a 
href="http://yht.demo.denovolab.com/admin/clients/productsList?id_clients=32762"
 rel="popup" title="This client's assigned products"><img 
src="<?php echo $this->webroot?>images/bProducts_disabled.gif" height="16" width="16"></a></td>

                        <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=25"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bOrigTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=25"
 rel="popup" title="Edit rate table">Rate Table 24</a></small></td>
                
                    <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/addForm?id_clients=32762&amp;origin=term&amp;id_companies=11&amp;id_currencies=&amp;name=TERM+RT+-+Call+Booth+3"
 rel="popup" title="Click here to add custom Termination rate table"><img
 src="<?php echo $this->webroot?>images/bTermTariffs_disabled.gif" alt="Click here to add 
custom Termination rate table" height="16" width="16"></a></td>
            <td align="left">&nbsp;</td>
                
    <td class="last"><a 
href="http://yht.demo.denovolab.com/admin/clients/delete?id=32762" 
rel="popup delete"><i class="icon-remove"></i></a></td>
</tr>
<tr class="s-active row-1">
    <td align="right">32763</td>
    <td id="ci-32763" rel="tooltip"><b><a 
href="http://yht.demo.denovolab.com/admin/clients/editForm?id=32763"
 rel="popup">Call Booth 4</a></b></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/accountsList?id_clients=32763"
 rel="popup" title="Edit accounts"><img src="<?php echo $this->webroot?>images/bAccounts.gif" 
alt="Edit accounts" height="16" width="16"></a></td>    
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/routesList?id_clients=32763"
 rel="popup" title="Edit routing presets"><img src="<?php echo $this->webroot?>images/bDR.gif" 
alt="Edit routing presets" height="16" width="16"></a></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/clientLogin?id=32763"
 target="_blank" title="Login as user to client panel"><img 
src="<?php echo $this->webroot?>images/bLogins_disabled.gif" height="16" width="16"></a></td>
    <td>COATEL S.A.</td>

    <td class="zero" align="right">0.00</td>
    <td class="zero" align="right">0.00</td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/payments/list?adv=1&amp;search[p.id_clients_eq]=32763&amp;search[client_name]=Call%20Booth%204"
 title="Balance operations"><img src="<?php echo $this->webroot?>images/balanceOperations.gif" 
height="16" width="16"></a></td>
    
    <td id="pi-32763"><a 
href="http://yht.demo.denovolab.com/admin/clients/productsList?id_clients=32763"
 rel="popup" title="This client's assigned products"><img 
src="<?php echo $this->webroot?>images/bProducts_disabled.gif" height="16" width="16"></a></td>

                        <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=16"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bOrigTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=16"
 rel="popup" title="Edit rate table">Rate Table 34</a></small></td>
                
                    <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/addForm?id_clients=32763&amp;origin=term&amp;id_companies=11&amp;id_currencies=&amp;name=TERM+RT+-+Call+Booth+4"
 rel="popup" title="Click here to add custom Termination rate table"><img
 src="<?php echo $this->webroot?>images/bTermTariffs_disabled.gif" alt="Click here to add 
custom Termination rate table" height="16" width="16"></a></td>
            <td align="left">&nbsp;</td>
                
    <td class="last"><a 
href="http://yht.demo.denovolab.com/admin/clients/delete?id=32763" 
rel="popup delete"><i class="icon-remove"></i> 
</a></td>
</tr>
<tr class="s-active nobalance row-2">
    <td align="right">32764</td>
    <td id="ci-32764" rel="tooltip"><b><a 
href="http://yht.demo.denovolab.com/admin/clients/editForm?id=32764"
 rel="popup">Call Booth 5</a></b></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/accountsList?id_clients=32764"
 rel="popup" title="Edit accounts"><img src="<?php echo $this->webroot?>images/bAccounts.gif" 
alt="Edit accounts" height="16" width="16"></a></td>    
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/routesList?id_clients=32764"
 rel="popup" title="Edit routing presets"><img src="<?php echo $this->webroot?>images/bDR.gif" 
alt="Edit routing presets" height="16" width="16"></a></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/clientLogin?id=32764"
 target="_blank" title="Login as user to client panel"><img 
src="<?php echo $this->webroot?>images/bLogins_disabled.gif" height="16" width="16"></a></td>
    <td>COATEL S.A.</td>

    <td class="zero" align="right">0.00</td>
    <td class="zero" align="right">0.00</td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/payments/list?adv=1&amp;search[p.id_clients_eq]=32764&amp;search[client_name]=Call%20Booth%205"
 title="Balance operations"><img src="<?php echo $this->webroot?>images/balanceOperations.gif" 
height="16" width="16"></a></td>
    
    <td id="pi-32764"><a 
href="http://yht.demo.denovolab.com/admin/clients/productsList?id_clients=32764"
 rel="popup" title="This client's assigned products"><img 
src="<?php echo $this->webroot?>images/bProducts_disabled.gif" height="16" width="16"></a></td>

                        <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=39"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bOrigTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=39"
 rel="popup" title="Edit rate table">Rate Table 35</a></small></td>
                
                    <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/addForm?id_clients=32764&amp;origin=term&amp;id_companies=11&amp;id_currencies=&amp;name=TERM+RT+-+Call+Booth+5"
 rel="popup" title="Click here to add custom Termination rate table"><img
 src="<?php echo $this->webroot?>images/bTermTariffs_disabled.gif" alt="Click here to add 
custom Termination rate table" height="16" width="16"></a></td>
            <td align="left">&nbsp;</td>
                
    <td class="last"><a 
href="http://yht.demo.denovolab.com/admin/clients/delete?id=32764" 
rel="popup delete"><i class="icon-remove"></i> 
</a></td>
</tr>
<tr class="s-active row-1">
    <td align="right">3</td>
    <td id="ci-3" rel="tooltip"><b><a 
href="http://yht.demo.denovolab.com/admin/clients/editForm?id=3" 
rel="popup">Customer 3</a></b></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/accountsList?id_clients=3"
 rel="popup" title="Edit accounts"><img src="<?php echo $this->webroot?>images/bAccounts.gif" 
alt="Edit accounts" height="16" width="16"></a></td>    
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/routesList?id_clients=3"
 rel="popup" title="Edit routing presets"><img src="<?php echo $this->webroot?>images/bDR.gif" 
alt="Edit routing presets" height="16" width="16"></a></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/clientLogin?id=3" 
target="_blank" title="Login as user to client panel"><img 
src="<?php echo $this->webroot?>images/bLogins.gif" height="16" width="16"></a></td>
    <td>TGA Corporation</td>

    <td class="neg" align="right">-1 392 994.03</td>
    <td class="neg" align="right">-233 666.17</td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/payments/list?adv=1&amp;search[p.id_clients_eq]=3&amp;search[client_name]=Customer%203"
 title="Balance operations"><img src="<?php echo $this->webroot?>images/balanceOperations.gif" 
height="16" width="16"></a></td>
    
    <td id="pi-3"><a 
href="http://yht.demo.denovolab.com/admin/clients/productsList?id_clients=3"
 rel="popup" title="This client's assigned products"><img 
src="<?php echo $this->webroot?>images/bProducts_disabled.gif" height="16" width="16"></a></td>

                        <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=24"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bOrigTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=24"
 rel="popup" title="Edit rate table">Rate Table 63</a></small></td>
                
                    <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/addForm?id_clients=3&amp;origin=term&amp;id_companies=1&amp;id_currencies=27&amp;name=TERM+RT+-+Customer+3"
 rel="popup" title="Click here to add custom Termination rate table"><img
 src="<?php echo $this->webroot?>images/bTermTariffs_disabled.gif" alt="Click here to add 
custom Termination rate table" height="16" width="16"></a></td>
            <td align="left">&nbsp;</td>
                
    <td class="last"><a 
href="http://yht.demo.denovolab.com/admin/clients/delete?id=3" 
rel="popup delete"><i class="icon-remove"></i> 
</a></td>
</tr>
<tr class="s-active row-2">
    <td align="right">11510</td>
    <td id="ci-11510" rel="tooltip"><b><a 
href="http://yht.demo.denovolab.com/admin/clients/editForm?id=11510"
 rel="popup">Debil Voisan</a></b></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/accountsList?id_clients=11510"
 rel="popup" title="Edit accounts"><img src="<?php echo $this->webroot?>images/bAccounts.gif" 
alt="Edit accounts" height="16" width="16"></a></td>    
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/routesList?id_clients=11510"
 rel="popup" title="Edit routing presets"><img src="<?php echo $this->webroot?>images/bDR.gif" 
alt="Edit routing presets" height="16" width="16"></a></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/clientLogin?id=11510"
 target="_blank" title="Login as user to client panel"><img 
src="<?php echo $this->webroot?>images/bLogins.gif" height="16" width="16"></a></td>
    <td>COATEL S.A.</td>

    <td class="pos" align="right">100.00</td>
    <td class="pos" align="right">100.00</td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/payments/list?adv=1&amp;search[p.id_clients_eq]=11510&amp;search[client_name]=Debil%20Voisan"
 title="Balance operations"><img src="<?php echo $this->webroot?>images/balanceOperations.gif" 
height="16" width="16"></a></td>
    
    <td id="pi-11510" rel="tooltip"><a 
href="http://yht.demo.denovolab.com/admin/clients/productsList?id_clients=11510"
 rel="popup" title="This client's assigned products"><img 
src="<?php echo $this->webroot?>images/bProducts.gif" height="16" width="16"></a></td>

                        <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=2"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bOrigTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=2"
 rel="popup" title="Edit rate table">ORIG RT for 1234</a></small></td>
                
                    <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=6"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bTermTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=6"
 rel="popup" title="Edit rate table">vipcall</a></small></td>
                
    <td class="last"><a 
href="http://yht.demo.denovolab.com/admin/clients/delete?id=11510" 
rel="popup delete"><i class="icon-remove"></i> 
</a></td>
</tr>

<tr class="s-active row-2">
    <td align="right">50</td>
    <td id="ci-50" rel="tooltip"><b><a 
href="http://yht.demo.denovolab.com/admin/clients/editForm?id=50" 
rel="popup">Hanuka Lolita</a></b></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/accountsList?id_clients=50"
 rel="popup" title="Edit accounts"><img src="<?php echo $this->webroot?>images/bAccounts.gif" 
alt="Edit accounts" height="16" width="16"></a></td>    
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/routesList?id_clients=50"
 rel="popup" title="Edit routing presets"><img src="<?php echo $this->webroot?>images/bDR.gif" 
alt="Edit routing presets" height="16" width="16"></a></td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/clients/clientLogin?id=50"
 target="_blank" title="Login as user to client panel"><img 
src="<?php echo $this->webroot?>images/bLogins.gif" height="16" width="16"></a></td>
    <td>Ephraim</td>

    <td class="zero" align="right">0.00</td>
    <td class="zero" align="right">0.00</td>
    <td><a 
href="http://yht.demo.denovolab.com/admin/payments/list?adv=1&amp;search[p.id_clients_eq]=50&amp;search[client_name]=Hanuka%20Lolita"
 title="Balance operations"><img src="<?php echo $this->webroot?>images/balanceOperations.gif" 
height="16" width="16"></a></td>
    
    <td id="pi-50"><a 
href="http://yht.demo.denovolab.com/admin/clients/productsList?id_clients=50"
 rel="popup" title="This client's assigned products"><img 
src="<?php echo $this->webroot?>images/bProducts_disabled.gif" height="16" width="16"></a></td>

                        <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=65"
 rel="popup" title="Edit rate table"><img 
src="<?php echo $this->webroot?>images/bOrigTariffs.gif" alt="Edit rate table" height="16" 
width="16"></a></td>
            <td><small><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/ratesList?id=65"
 rel="popup" title="Edit rate table">TGA BLUE</a></small></td>
                
                    <td><a 
href="http://yht.demo.denovolab.com/admin/rate_tables/addForm?id_clients=50&amp;origin=term&amp;id_companies=7&amp;id_currencies=27&amp;name=TERM+RT+-+Hanuka+Lolita"
 rel="popup" title="Click here to add custom Termination rate table"><img
 src="<?php echo $this->webroot?>images/bTermTariffs_disabled.gif" alt="Click here to add 
custom Termination rate table" height="16" width="16"></a></td>
            <td align="left">&nbsp;</td>
                
    <td class="last"><a 
href="http://yht.demo.denovolab.com/admin/clients/delete?id=50" 
rel="popup delete"><i class="icon-remove"></i> 
</a></td>
</tr>
</tbody><tbody>
</tbody></table>
<ul class="list-meta">
        <li class="list-meta-ipp">Rows per page: <span>20</span></li>
    <li class="list-meta-ippa"><form>
                Rows per page: <select class="input in-select" 
name="itemsPerPage" id="itemsPerPage"><option value="10">10</option><option
 value="20" selected="selected">20</option><option value="50">50</option><option
 value="100">100</option><option value="1000">1000</option></select>    </form></li>
    
        <li class="list-meta-plist"><b>1</b><a 
href="http://yht.demo.denovolab.com/admin/clients/list?page=2">2</a><a
 href="http://yht.demo.denovolab.com/admin/clients/list?page=3">3</a><a
 href="http://yht.demo.denovolab.com/admin/clients/list?page=2" 
class="page-next">&nbsp;»&nbsp;</a><a 
href="http://yht.demo.denovolab.com/admin/clients/list?page=3" 
class="page-last">&nbsp;»|&nbsp;</a></li>
    <li class="list-meta-pnum">Pages: <span>3</span></li>
    <li class="list-meta-pgo"><form>
                Go: <input class="input in-text" name="page" value="1" 
id="page" type="text">    </form></li>
    </ul>

<dl id="ci-32784-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="zero">0.00</span> / 
			<span class="zero">0.00</span> /
			<span class="zero">0.00</span> 
			EUR			</dd>
	
    	
            <dt>Client login:</dt><dd>aaabbb</dd>
    <dt>Created on:</dt><dd>05/02/2010 15:17:31 +0300</dd>
</dl>
<dl id="pi-32784-tooltip" class="tooltip">
			<dt>Not Active:</dt>
			    <dd>test me</dd>
	    	    <dd>test me</dd>
	        		    
</dl>
<dl id="ci-32747-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="neg">-6 666.00</span> / 
			<span class="neg">-6 666.00</span> /
			<span class="neg">-7 342.00</span> 
			USD			</dd>
	
        <dt>DR Policy / Profit Margin:</dt>
	<dd>
		—		/ 100	</dd>
    	
    <dt>Groups:</dt><dd>Providers</dd>    <dt>Payment Terms:</dt><dd>7/7</dd>
    
    <dt>Created on:</dt><dd>02/12/2010 22:57:00 +0300</dd>
</dl>
<dl id="pi-32747-tooltip" class="tooltip">
			    
</dl>
<dl id="ci-21628-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="zero">0.00</span> / 
			<span class="zero">0.00</span> /
			<span class="zero">0.00</span> 
			EUR			</dd>
	
        <dt>DR Policy / Profit Margin:</dt>
	<dd>
		—		/ 10	</dd>
    	
    <dt>Groups:</dt><dd>Customers, Providers, Cards</dd>    <dt>Payment 
Terms:</dt><dd>1/1</dd>    <dt>Client login:</dt><dd>aaaddd</dd>
    <dt>Created on:</dt><dd>11/27/2009 23:36:29 +0300</dd>
</dl>
<dl id="pi-21628-tooltip" class="tooltip">
			<dt>Not Active:</dt>
			    <dd>USA1</dd>
	    	    <dd>test me</dd>
	        		    
</dl>
<dl id="ci-21634-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="zero">0.00</span> / 
			<span class="zero">0.00</span> /
			<span class="neg">-22 120.00</span> 
			EUR			</dd>
	
    	
    <dt>Groups:</dt><dd>Call Shop</dd>        <dt>Client login:</dt><dd>test001</dd>
    <dt>Created on:</dt><dd>01/05/2010 21:25:13 +0300</dd>
</dl>
<dl id="pi-21634-tooltip" class="tooltip">
			    
</dl>
<dl id="ci-21626-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="pos">975.00</span> / 
			<span class="pos">200 975.00</span> /
			<span class="pos">1 742.00</span> 
			EUR			</dd>
	
    	
    <dt>Groups:</dt><dd>Call Shop, Customers, Providers, Cards</dd>    <dt>Payment
 Terms:</dt><dd>1/1</dd>    
    <dt>Created on:</dt><dd>11/27/2009 23:34:30 +0300</dd>
</dl>
<dl id="pi-21626-tooltip" class="tooltip">
			<dt>Not Active:</dt>
			    <dd>USA1</dd>
	        		    
</dl>
<dl id="ci-32-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="neg">-10 408.80</span> / 
			<span class="neg">-10 408.80</span> /
			<span class="neg">-10 408.84</span> 
			EUR			</dd>
	
    	
    <dt>Groups:</dt><dd>Customers</dd>    <dt>Payment Terms:</dt><dd>30/8</dd>
    
    <dt>Created on:</dt><dd>04/27/2008 20:43:42 +0300</dd>
</dl>
<dl id="pi-32-tooltip" class="tooltip">
			<dt>Not Active:</dt>
			    <dd>test me</dd>
	        		    
</dl>
<dl id="ci-55-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="pos">2 000.00</span> / 
			<span class="pos">2 350.00</span> /
			<span class="pos">465.00</span> 
			EUR			</dd>
	
    	
        <dt>Payment Terms:</dt><dd>PRE</dd>    <dt>Client login:</dt><dd>test1</dd>
    <dt>Created on:</dt><dd>06/23/2008 19:55:38 +0300</dd>
</dl>
<dl id="pi-55-tooltip" class="tooltip">
			    
</dl>
<dl id="ci-32760-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="zero">0.00</span> / 
			<span class="pos">1.00</span> /
			<span class="zero">0.00</span> 
			EUR			</dd>
	
    	
    <dt>Groups:</dt><dd>Call Shop, Customers</dd>        <dt>Client 
login:</dt><dd>457103</dd>
    <dt>Created on:</dt><dd>02/24/2010 18:05:18 +0300</dd>
</dl>
<dl id="pi-32760-tooltip" class="tooltip">
			<dt>Not Active:</dt>
			    <dd>DID-Spain-Madrid</dd>
	        		    
</dl>
<dl id="ci-32761-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="zero">0.00</span> / 
			<span class="zero">0.00</span> /
			<span class="zero">0.00</span> 
			EUR			</dd>
	
    	
    <dt>Groups:</dt><dd>Call Shop</dd>        
    <dt>Created on:</dt><dd>02/24/2010 18:14:48 +0300</dd>
</dl>
<dl id="pi-32761-tooltip" class="tooltip">
			    
</dl>
<dl id="ci-32762-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="zero">0.00</span> / 
			<span class="pos">5.00</span> /
			<span class="zero">0.00</span> 
			EUR			</dd>
	
    	
    <dt>Groups:</dt><dd>Call Shop</dd>        <dt>Client login:</dt><dd>westbrook</dd>
    <dt>Created on:</dt><dd>02/24/2010 18:15:14 +0300</dd>
</dl>
<dl id="pi-32762-tooltip" class="tooltip">
			    
</dl>
<dl id="ci-32763-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="zero">0.00</span> / 
			<span class="pos">35.00</span> /
			<span class="zero">0.00</span> 
			EUR			</dd>
	
    	
    <dt>Groups:</dt><dd>Call Shop</dd>        
    <dt>Created on:</dt><dd>02/24/2010 18:25:25 +0300</dd>
</dl>
<dl id="pi-32763-tooltip" class="tooltip">
			    
</dl>
<dl id="ci-32764-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="zero">0.00</span> / 
			<span class="zero">0.00</span> /
			<span class="zero">0.00</span> 
			EUR			</dd>
	
    	
    <dt>Groups:</dt><dd>Call Shop</dd>        
    <dt>Created on:</dt><dd>02/24/2010 18:25:45 +0300</dd>
</dl>
<dl id="pi-32764-tooltip" class="tooltip">
			    
</dl>
<dl id="ci-3-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="neg">-140.20</span> / 
			<span class="neg">-140.20</span> /
			<span class="neg">-835.80</span> 
			JOD			</dd>
	
        <dt>DR Policy / Profit Margin:</dt>
	<dd>
		Simple LCR		/ 16%	</dd>
    	
    <dt>Groups:</dt><dd>Customers</dd>        <dt>Client login:</dt><dd>customer3</dd>
    <dt>Created on:</dt><dd>04/14/2008 21:29:31 +0300</dd>
</dl>
<dl id="pi-3-tooltip" class="tooltip">
			    
</dl>
<dl id="ci-11510-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="pos">100.00</span> / 
			<span class="pos">100.00</span> /
			<span class="pos">100.00</span> 
			EUR			</dd>
	
    	
    <dt>Groups:</dt><dd>Cards</dd>        <dt>Client login:</dt><dd>1234</dd>
    <dt>Created on:</dt><dd>08/30/2009 03:22:30 +0300</dd>
</dl>
<dl id="pi-11510-tooltip" class="tooltip">
			<dt>Not Active:</dt>
			    <dd>DID-Spain-Madrid</dd>
	    	    <dd>Testprodukt</dd>
	        		    
</dl>
<dl id="ci-10290-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="zero">0.00</span> / 
			<span class="zero">0.00</span> /
			<span class="neg">-121.00</span> 
			Dinar Natura			</dd>
	
        <dt>DR Policy / Profit Margin:</dt>
	<dd>
		Proportional		/ 3	</dd>
    	
        <dt>Payment Terms:</dt><dd>30/8</dd>    
    <dt>Created on:</dt><dd>06/07/2009 14:15:23 +0300</dd>
</dl>
<dl id="pi-10290-tooltip" class="tooltip">
			    
</dl>
<dl id="ci-18-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="pos">16.01</span> / 
			<span class="pos">16.01</span> /
			<span class="pos">1.19</span> 
			JOD			</dd>
	
        <dt>DR Policy / Profit Margin:</dt>
	<dd>
		Simple LCR		/ 20%	</dd>
    	
            
    <dt>Created on:</dt><dd>04/18/2008 22:31:43 +0300</dd>
</dl>
<dl id="pi-18-tooltip" class="tooltip">
			    
</dl>
<dl id="ci-32766-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="zero">0.00</span> / 
			<span class="zero">0.00</span> /
			<span class="zero">0.00</span> 
			EUR			</dd>
	
    	
            
    <dt>Created on:</dt><dd>03/01/2010 20:23:51 +0300</dd>
</dl>
<dl id="pi-32766-tooltip" class="tooltip">
			    
		<dt>To Cancel:</dt>
			    <dd>Test Product</dd>
	            
</dl>
<dl id="ci-32769-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="zero">0.00</span> / 
			<span class="pos">50.00</span> /
			<span class="zero">0.00</span> 
			EUR			</dd>
	
        <dt>DR Policy / Profit Margin:</dt>
	<dd>
		—		/ 10	</dd>
    	
    <dt>Groups:</dt><dd>Customers</dd>    <dt>Payment Terms:</dt><dd>1/1</dd>
    
    <dt>Created on:</dt><dd>03/23/2010 12:16:48 +0300</dd>
</dl>
<dl id="pi-32769-tooltip" class="tooltip">
			<dt>Not Active:</dt>
			    <dd>DID-Spain-Madrid</dd>
	    	    <dd>Testprodukt</dd>
	    	    <dd>Broadband</dd>
	        		    
</dl>
<dl id="ci-32770-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="zero">0.00</span> / 
			<span class="zero">0.00</span> /
			<span class="zero">0.00</span> 
			EUR			</dd>
	
    	
    <dt>Groups:</dt><dd>Call Shop</dd>        
    <dt>Created on:</dt><dd>04/12/2010 03:43:36 +0300</dd>
</dl>
<dl id="pi-32770-tooltip" class="tooltip">
			    
</dl>
<dl id="ci-50-tooltip" class="tooltip">
		
	<dt>Balance <small style="font-weight: normal;">(Current / Available / 
Mutual)</small>:</dt>
	<dd>
					<span class="zero">0.00</span> / 
			<span class="pos">0.60</span> /
			<span class="zero">0.00</span> 
			JOD			</dd>
	
        <dt>DR Policy / Profit Margin:</dt>
	<dd>
		—		/ 3%	</dd>
    	
    <dt>Groups:</dt><dd>Customers</dd>    <dt>Payment Terms:</dt><dd>7/7</dd>
    <dt>Client login:</dt><dd>Hanuka</dd>
    <dt>Created on:</dt><dd>05/14/2008 15:58:35 +0300</dd>
</dl>
<dl id="pi-50-tooltip" class="tooltip">
			    
</dl>
    <!-- DYNAMIC -->
</div>

<div id="footer">
    <span><strong>yht</strong> © 2004-2010 <a 
href="http://denovolab.com/" target="_blank">JeraSoft</a> development. 
All Rights Reserved.</span>
    <ul>
        <li><a href="http://yht.demo.denovolab.com/admin/about">About...</a></li>
        <li><a href="http://yht.demo.denovolab.com/admin/support">Get
 Support</a></li>
    </ul>
</div>
<script type="text/javascript">
//<![CDATA[
showMessages([]);
//]]>
</script>
<div class="  viewport-bottom" style="display: none; top: 409px; left: 
915px; right: auto;" id="tooltip"><h3 style="display: none;"></h3><div 
class="body"><dl id="pi-11510-tooltip" class=" ">
			<dt>Not Active:</dt>
			    <dd>DID-Spain-Madrid</dd>
	    	    <dd>Testprodukt</dd>
	        		    
</dl></div><div style="display: none;" class="url"></div></div>
