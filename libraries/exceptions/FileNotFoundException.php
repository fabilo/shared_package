<?php
/**
 * Trying to include a file that doesn't exist
 */
if (!class_exists('FileNotFoundException')) {
	class FileNotFoundException extends Exception {}
}