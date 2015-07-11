<?php
require_once('core/BLW_CORE_ClassLoader.php');
BLW_CORE_ClassLoader::import('app.core.BLW_CORE_IniParser');
BLW_CORE_ClassLoader::import('app.lib.HTTP.BLW_HTTP_Request');
BLW_CORE_ClassLoader::import('app.lib.codegen.BLW_CODEGEN_CodeCompiler');

$ini_file = new BLW_CORE_IniParser('blw.ini');

$request = new BLW_HTTP_Request();
$path_info = $request->getPathInfo();

if(strlen($path_info) > 0) {
	$regex = '=\/([^\/]+)=';
	$matches = array();
	preg_match($regex, $path_info, $matches);
	$page_name = $matches[1];
	
	$class_file_name = $ini_file->getValue('template', 'class_path').DIRECTORY_SEPARATOR.$page_name.".php";
	$template_file_name = $ini_file->getValue('template', 'template_path').DIRECTORY_SEPARATOR.$page_name.".psp";
	
	if(!file_exists($class_file_name) || (filemtime($class_file_name) < filemtime($template_file_name)) ) {
		$compiler = new BLW_CODEGEN_CodeCompiler();
		
		$src = $compiler->process(file_get_contents($template_file_name));
		$class_file = fopen($class_file_name, "w");
		fwrite($class_file, $src, strlen($src));
		fclose($class_file);
	}
	require_once($class_file_name);
}
?>