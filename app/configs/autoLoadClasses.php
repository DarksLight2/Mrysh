<?php

spl_autoload_register(function ($class_name) {
	
	$PATH = $_SERVER['DOCUMENT_ROOT'].'/'.$class_name.'.php';
	
    $fn = str_replace('\\', '/', $PATH);
	
	if(file_exists($fn))
	{
		require_once $fn;
	}
    
});