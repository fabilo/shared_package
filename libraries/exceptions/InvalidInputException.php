<?php 
/**
 * Invalid input for a variable, use for validating and reporting on user input
 */
if (!class_exists('InvalidInputException')) {
	class InvalidInputException extends Exception {}
}