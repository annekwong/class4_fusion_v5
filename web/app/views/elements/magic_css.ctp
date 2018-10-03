<!--导入所有reoprt页面的input和select样式文件-->
<?php //echo $this->element('magic_css');?>

<style type="text/css">
.form .label, .list-form .label{width:110px;}
.form .value, .list-form .value img {
	_float:left;	
}
.form .value, .list-form .value {
	width:auto;
	text-align:left;
}
.in-text, .value select, .value .in-text, .value .in-select {
	width:160px;
}
/*右边多选项的select样式*/
.in-select{font-size:14px;}



.label {
	text-align: right;
}
.value {
	text-align: left;
}
.list td.in-decimal {
	text-align:center;
}
.list td.last {
	border-right:1px solid #809DBA;
	text-align:center;
}
</style>