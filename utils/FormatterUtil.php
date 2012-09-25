<?php
/**
 *	This file contains a class for performing formatting on input, eg: strings, dates, numbers etc
 */
class FormatterUtil {
	/**
	 * Format a string so it's a valid css selector
	 */
	public static function stringForCssSelector($string) {
		return preg_replace('/![a-zA-Z\-_0-9]/', '', $string);
	}
}