<?php
/**
 *	Bootstrap shared package
 *	Mainly just setting up include directories
 */

// define root path - this file should be sitting in the root path of the shared package
$root = dirname(__FILE__); 

// define include paths as an array
$includes = array();
$includes[]= 'base';
$includes[]= 'domain_models';
$includes[]= 'factories';
$includes[]= 'interfaces';
$includes[]= 'libraries';
$includes[]= 'libraries/exceptions';
$includes[]= 'utils';
$includes[]= 'views';
$includes[]= 'third_party/code-igniter';

// define include path string - append to currently set
$includePathString = get_include_path();

// loop include paths and add to includePathString 
foreach ($includes AS $includePath) {
	$includePathString .= PATH_SEPARATOR.$root.'/'.$includePath;
}

// update php include path
set_include_path($includePathString);