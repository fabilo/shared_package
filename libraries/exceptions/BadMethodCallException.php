<?php
/**
 * Trying to execute a method that doesn't exist for the object
 */
if (!class_exists('BadMethodCallException')) {
	class BadMethodCallException extends Exception {}
}