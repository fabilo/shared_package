<?php
/**
 * Invalid parameter for a method call
 */
if (!class_exists('InvalidArgumentException')) {
	class InvalidArgumentException extends Exception {}
}
