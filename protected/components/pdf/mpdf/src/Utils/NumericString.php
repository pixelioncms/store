<?php



class NumericString
{

	public static function containsPercentChar($string)
	{
		return stristr($string, '%');
	}

	public static function removePercentChar($string)
	{
		return str_replace('%', '', $string);
	}

}
