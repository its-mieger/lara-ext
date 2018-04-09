<?php
	use Illuminate\Support\Str;

	if (!defined('ARRAY_PAD_LEFT'))
		define('ARRAY_PAD_LEFT', 0);
	if (!defined('ARRAY_PAD_RIGHT'))
		define('ARRAY_PAD_RIGHT', 1);


	Str::macro('extract', function($haystack, $delimiter, $length, $padType = ARRAY_PAD_LEFT, $defaultValue = null) {
		$sp = explode($delimiter, $haystack);
		$numParts = count($sp);

		if ($numParts == $length) {
			return $sp;
		}
		elseif ($numParts > $length) {
			return array_slice($sp, 0, $length);
		}
		else {
			switch ($padType) {

				case ARRAY_PAD_LEFT:
					return array_pad($sp, $length, $defaultValue);

				case ARRAY_PAD_RIGHT:
					return array_merge(array_fill(0, $length - $numParts, $defaultValue), $sp);

				default:
					throw new \InvalidArgumentException('Invalid array pad type "' . $padType . '"');
			}
		}
	});