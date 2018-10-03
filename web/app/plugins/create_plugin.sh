#!/bin/sh

PN=
CN=
function format_plugin_name(){
	NAMEF=`echo $1 | cut -b 1 | tr a-z A-Z`
	NAMES=`echo $1 | cut -b 2-`
	PN=$NAMEF$NAMES	
}

function format_controller_name(){
	NAMEF=`echo $1 | cut -b 1 | tr a-z A-Z`
	NAMES=`echo $1 | cut -b 2-`
	CN=$NAMEF$NAMES	
}
function create_dir(){
if [ ! -e $1 ]
then 
	mkdir -p $1
fi
}

function create_file(){
if [  ! -e $1 ]
then
	touch $1
	echo "$2" > $1
fi
}

function create_controller(){
	format_plugin_name $1
	format_controller_name $2
	create_dir $1'/controllers' 
	create_file $1'/controllers/'$1'_'$2's_controller.php' "<?php
class "$PN$CN"sController extends "$PN"AppController{
	var \$name = '"$PN$CN"s';
	var \$uses = array();
	var \$helpers = array('"$PN".App"$PN$CN"s');
	function index(){

	}
}"
}

function create_model(){
	format_plugin_name $1
	format_controller_name $2
	create_dir $1'/models' 
	create_file $1'/models/'$1'_'$2'_model.php' "<?php
class "$PN$CN"Model extends "$PN"AppModel{
	var \$name = '"$PN$CN"s';
}"
}

function create_view(){
	format_plugin_name $1
	format_controller_name $2
	create_dir $1'/views/'
	create_dir $1'/views/'$1'_'$2's'
	create_dir $1'/views/helpers'
	create_dir $1'/views/elements/'$1'_'$2's'
	create_file $1'/views/'$1'_'$2's/index.ctp' ""
	create_file $1'/views/helpers/app_'$1'.php' "<?php
class App"$PN"Helper extends AppHelper{
}"
	create_file $1'/views/helpers/app_'$1'_'$2's.php' "<?php
App::import('Helper','$PN.App$PN');
class App"$PN$CN"sHelper extends App$PN""Helper{
}"
}

function create_plugin(){
	create_dir $1
	create_dir $1'/controllers'
	create_dir $1'/controllers/components'
	create_dir $1'/models'
	create_dir $1'/models/behaviors'
	create_dir $1'/models/datasources'
	create_dir $1'/views'
	create_dir $1'/views/helpers'
	create_dir $1'/views/elements'

	format_plugin_name $1
	create_file $1'/'$1'_app_controller.php' "<?php
class "$PN"AppController extends AppController {

}"
	create_file $1'/'$1'_app_model.php' "<?php
class "$PN"AppModel extends AppModel {

}"
}
if [ $# == 1 ]
then
	create_plugin $1
else
	if [ $# == 2 ]
	then
		create_plugin $1
		create_controller $1 $2 
		create_view $1 $2 
	#	create_model $1 $2 
	else
		echo $0" pluginname [controller_name]
"
	fi
fi
