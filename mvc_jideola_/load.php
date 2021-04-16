<?php
set_time_limit(120);
ob_end_clean();

$request_link = $_SERVER['REQUEST_URI'];
$request_link = explode('/',$request_link);
unset($request_link[0]);

$scope = $jideola->antiHacking($request_link[3]);
$version = $jideola->antiHacking($request_link[4]);
$call = (isset($request_link[3]))? $request_link[5] : '';

$scope = strtolower($scope);
$version = strtolower($version);
$call = strtolower($call);

/* Avoid directory traversal attack */
$call = str_replace('../', '', $call);
$scope = str_replace('../', '', $scope);
$version = str_replace('../', '', $version);

if (!is_dir(MVC .$scope)) die(json_encode(['error' => 'Application does not exist']));
if (!is_dir(MVC .$scope.'/'. $version)) die(json_encode(['error' => 'Specified version does not exist']));
$file_path = MVC.$scope."/".$version."/endpoints/".$call.".php";

$_SERVER['ENDPOINT'] = $call;

$autoload_path = MVC.$scope."/".$version."/autoload_index.php";
if(file_exists($autoload_path)) include_once $autoload_path;
else echo die(json_encode(['error' => 'Application could not load']));

if(file_exists($file_path)) include_once $file_path;
else die(json_encode(['error' => 'Silence is Golden']));
