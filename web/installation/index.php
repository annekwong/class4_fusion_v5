<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/functions.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/templates',
));
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SerializerServiceProvider());

$app['session.storage.handler'] = null;

$app['debug'] = true;

$C4_BASEPATH = realpath(__DIR__ . '/../');

$app->get('/', function() use ($app) {
	return $app['twig']->render('welcome.html');
});



$app->get('/permissions', function() use ($app, $C4_BASEPATH) {
	/*
	 * Show current permissions
	 */

	$index_file_path = $C4_BASEPATH . '/app/webroot/index.php';
	$index_file_content = file_get_contents($index_file_path);
	preg_match("/define\('CONF_PATH', (.*)\);/", $index_file_content, $matches);

	if (count($matches) !== 2)
	{

	}

	$ini_file_path = $matches[1];
	if (strpos($ini_file_path, 'ROOT') !== FALSE)
	{
		$ini_file_path = $C4_BASEPATH . '/dnl_softswitch.ini';
	}
	else
	{
		$ini_file_path = trim($ini_file_path, "' ");
	}

	$file_path_list = array(
		'app/webroot/upload',
		'app/binexec/active_call/active_call_api',
		'app/binexec/dnl_import_rate/dnl_import_rate',
		'app/binexec/wkhtmltopdf/wkhtmltopdf-amd64',
		'app/webroot/index.php',
		'app/webroot/favicon.ico',
		'favicon.ico',
		'app/tmp/',
		'app/upload/',
	);

	array_walk($file_path_list, function(&$item, $key) use ($C4_BASEPATH) {
		$item = $C4_BASEPATH . '/' . $item;
	});

	$config_file_path = $ini_file_path;

	$app['session']->set('path', array("config_file_path" => $config_file_path));

	array_push($file_path_list, $config_file_path);


	$permissions_result = array();
	$all_success = true;

	foreach ($file_path_list as $file_path_item) {

		if (0777 === (@fileperms($file_path_item) & 0777)) {
			$is777 = true;
		} else {
			$is777 = false;
			$all_success = false;
		}
		array_push($permissions_result, array($file_path_item, $is777));
	}

	return $app['twig']->render('permissions.html', array('permissions' => $permissions_result, 'all_success' => $all_success));
});

$app->match('/configurations', function(Request $request) use ($app, $C4_BASEPATH) {
	/*
	 * display configurations form
	 */
	if ($request->server->get('REQUEST_METHOD') == 'POST')
	{
		$error_messages = array();
		$dbHost = $request->request->get('dbHost');
		$dbPort = $request->request->get('dbPort');
		$dbName = $request->request->get('dbName');
		$dbUser = $request->request->get('dbUser');
		$dbPassword = $request->request->get('dbPassword');

		$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
			'db.options' => array (
				'driver'    => 'pdo_pgsql',
				'host'      => $dbHost,
				'port'		=> $dbPort,
				'dbname'    => $dbName,
				'user'      => $dbUser,
				'password'  => $dbPassword,
			),
		));

		$sql = "select CURRENT_TIMESTAMP";

		$dbConn = true;

		try {
			$app['db']->fetchAssoc($sql);
		} catch (PDOException $e) {
			$dbConn = false;
			$error_messages[] = "Can not connect the database.";
		}


		$switchIP = $request->request->get('switchIP');
		$switchPort = $request->request->get('switchPort');

		$switchConn = true;

		$socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		$result = @socket_connect($socket, $switchIP, (int)$switchPort);
		if ($result == false) {
			$switchConn = false;
			socket_close($socket);
			$error_messages[] = "Can not connect the switch event socket.";
		} else {

			$msg = "help\n\n";
			socket_write($socket, $msg, strlen($msg));

			$out = socket_read($socket, 2048);
			socket_close($socket);
			if (strpos($out, 'help') === false)
				$switchConn = false;

		}


		$scriptPath = $request->request->get('scriptPath');
		$actualDBPath = $request->request->get('actualDBPath');
		$webDBPath = $request->request->get('webDBPath');
		$phpPath = $request->request->get('phpPath');
		$byNFS = $request->request->get('byNFS');



		if (!file_exists($scriptPath))
		{
			$rightScriptPath = false;
			$error_messages[] = "The script path does not exists.";
		}
		else
			$rightScriptPath = true;


		if (!file_exists($webDBPath)) {
			$rightWebDBPath  = false;
			$error_messages[] = "The web database path does not exists.";
		}
		else
			$rightWebDBPath = true;

		if (!file_exists($phpPath)) {
			$rightPhpPath = false;
			$error_messages[] = "The php interpreter path does not exists.";
		} else
			$rightPhpPath = true;

		$mount_list = shell_exec('mount -l');

		if (empty($webDBPath) or (strpos($mount_list, $webDBPath) === false and !is_null($byNFS))) {
			$rightWebDBPath = false;
			$error_messages[] = "The web database path is not in `mount -l`.";
		}

		$rightActualDBPath = true;

		if (empty($actualDBPath) or (strpos($mount_list, $actualDBPath) === false and !is_null($byNFS)))
		{
			$error_messages[] = "The database export path is not in `mount -l`.";
			$rightActualDBPath = false;
		}

		if ($dbConn && $switchConn && $rightWebDBPath && $rightPhpPath && $rightScriptPath && $rightActualDBPath)
		{
			$sessions = $app['session']->get('path');
			$config_file_path = $sessions['config_file_path'];

			// put config ini to app/webroot/index.php
			$webroot_index =  $C4_BASEPATH . '/app/webroot/index.php';
			$webroot_index_content = file_get_contents($webroot_index);
			$webroot_index_content = preg_replace("/define\('CONF_PATH', '(.*)'\);/", "define('CONF_PATH', '{$config_file_path}');", $webroot_index_content);
			file_put_contents($webroot_index, $webroot_index_content);

			// begin configure config ini
            $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
			$sections['db']['host'] = $dbHost;
			$sections['db']['port'] = $dbPort;
			$sections['db']['dbname'] = $dbName;
			$sections['db']['user'] = $dbUser;
			$sections['db']['password'] = $dbPassword;

			$sections['web_switch']['event_ip'] = $switchIP;
			$sections['web_switch']['event_port'] = $switchPort;

			$sections['web_path']['db_export_path'] = $actualDBPath;
			$sections['web_path']['web_export_path'] = $webDBPath;
			$sections['web_path']['php_interpreter_path'] = $phpPath;

			$sections['web_script']['path'] = $scriptPath;

			write_ini_file($sections, $config_file_path, TRUE);

			return $app->redirect('requirements');
		}
		else
		{
			return $app['twig']->render('configurations.html', array(
				'request' => $request,
				'dbConn'  => $dbConn,
				'switchConn' => $switchConn,
				'rightWebDBPath' => $rightWebDBPath,
				'rightPhpPath' => $rightPhpPath,
				'rightScriptPath' => $rightScriptPath,
				'rightActualDBPath' => $rightActualDBPath,
				'error_messages' => $error_messages,
			));
		}

	}

	$sessions = $app['session']->get('path');


	if (is_null($sessions)) {
		$index_file_path = $C4_BASEPATH . '/app/webroot/index.php';
		$index_file_content = file_get_contents($index_file_path);
		preg_match("/define\('CONF_PATH', (.*)\);/", $index_file_content, $matches);

		if (count($matches) !== 2)
		{

		}

		$ini_file_path = $matches[1];
		if (strpos($ini_file_path, 'ROOT') !== FALSE)
		{
			$ini_file_path = $C4_BASEPATH . '/dnl_softswitch.ini';
		}
		else
		{
			$ini_file_path = trim($ini_file_path, "' ");
		}
		$config_file_path = $ini_file_path;

	} else {
		$config_file_path = $sessions['config_file_path'];
	}

    $sections = parse_ini_file($config_file_path, TRUE, INI_SCANNER_RAW);
	$request->request->set('dbHost', $sections['db']['hostaddr']);
	$request->request->set('dbPort', $sections['db']['port']);
	$request->request->set('dbName', $sections['db']['dbname']);
	$request->request->set('dbUser', $sections['db']['user']);
	$request->request->set('dbPassword', $sections['db']['password']);

	$request->request->set('switchIP', $sections['web_switch']['event_ip']);
	$request->request->set('switchPort', $sections['web_switch']['event_port']);

	$request->request->set('scriptPath', $sections['web_script']['path']);
	$request->request->set('actualDBPath', $sections['web_path']['db_export_path']);
	$request->request->set('webDBPath', $sections['web_path']['web_export_path']);
	$request->request->set('phpPath', $sections['web_path']['php_interpreter_path']);


	return $app['twig']->render('configurations.html', array('request' => $request));
});


$app->get('/requirements', function() use ($app) {
	$modules = array(
		'zlib',
		'openssl',
		'redis',
		'pdo_pgsql',
		'libxml',
		'curl',
		'ftp',
		'gd',
		'mcrypt',
		'SimpleXML',
		'sockets',
		'zip',
	);

	$requirements = array();

	foreach ($modules as $module) {
		$requirements[] = array('module' => $module, 'supported' => extension_loaded($module));
	}

	$apache_modules = apache_get_modules();

	$requirements[] = array('module' => 'mod_xsendfile', 'supported' => in_array('mod_xsendfile', $apache_modules));


	return $app['twig']->render('requirements.html', array('requirements' => $requirements));
});



$app->get('/schema', function() use ($app, $C4_BASEPATH) {

	$sessions = $app['session']->get('path');


	if (is_null($sessions)) {
		$webroot_index =  $C4_BASEPATH . '/app/webroot/index.php';
		$webroot_index_content = file_get_contents($webroot_index);
		preg_match("/define\('CONF_PATH', '(.*)'\);/", $webroot_index_content, $matches);
		$config_file_path = $matches[1];
	} else {
		$config_file_path = $sessions['config_file_path'];
	}

	$sections = parse_ini_file($config_file_path, TRUE);

	$dbHost = $sections['db']['host'];
	$dbPort = $sections['db']['port'];
	$dbName = $sections['db']['dbname'];
	$dbUser = $sections['db']['user'];
	$dbPassword = $sections['db']['password'];


	$schema_file = $C4_BASEPATH . '/schema.sql';

	putenv('PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin');
	putenv('LC_ALL=en_US.UTF-8');
	putenv('LANG=en_US.UTF-8');
	putenv("PGPASSWORD=$dbPassword");

	$cmd = "psql -h $dbHost $dbName -U $dbUser -p $dbPort < $schema_file";

	$result = shell_exec($cmd);

	$url =  dirname($app['request']->getBaseUrl());

	return $app['twig']->render('schema.html', array('url' => $url));
});


$app->run();