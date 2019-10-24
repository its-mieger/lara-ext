<?php
	use Illuminate\Support\Str;

	Str::macro('extract', function($haystack, $delimiter, $length, $defaultValue = null, $appendOverflowing = false) {
		$absLength = abs($length);

		// explode string
		if ($appendOverflowing)
			$sp = explode($delimiter, $haystack, $absLength);
		else
			$sp = explode($delimiter, $haystack);

		$numParts = count($sp);

		if ($numParts == $absLength) {
			return $sp;
		}
		elseif ($numParts > $absLength) {
			return array_slice($sp, 0, $absLength);
		}
		elseif ($length > 0) {
			// pad right
			return array_pad($sp, $absLength, $defaultValue);
		}
		else {
			// pad left
			return array_merge(array_fill(0, $absLength - $numParts, $defaultValue), $sp);
		}
	});

	Str::macro('replaceLineBreaks', function(?string $subject, ?string $replace = ' ') {

		if ($subject === null)
			return null;

		return str_replace(["\r\n", "\r", "\n"], $replace, $subject);

	});