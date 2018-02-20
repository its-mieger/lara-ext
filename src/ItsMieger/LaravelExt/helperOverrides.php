<?php
	use Illuminate\Support\Arr;
	use Illuminate\Support\Collection;

	if (!function_exists('data_get')) {
		/**
		 * Get an item from an array or object using "dot" notation. This function implements getter usage
		 *
		 * @param  mixed $target
		 * @param  string|array $key
		 * @param  mixed $default
		 * @return mixed
		 */
		function data_get($target, $key, $default = null) {
			if (is_null($key)) {
				return $target;
			}

			$key = is_array($key) ? $key : explode('.', $key);

			while (!is_null($segment = array_shift($key))) {
				if ($segment === '*') {
					if ($target instanceof Collection) {
						$target = $target->all();
					}
					elseif (!is_array($target)) {
						return value($default);
					}

					$result = Arr::pluck($target, $key);

					return in_array('*', $key) ? Arr::collapse($result) : $result;
				}


				if (Arr::accessible($target) && Arr::exists($target, $segment)) {
					$target = $target[$segment];
				}
				elseif (is_object($target) && isset($target->{$segment})) {
					$target = $target->{$segment};
				}
				// extension to call getter functions
				elseif (substr($segment, 0, 3) == 'get' && substr($segment, -2) == '()') {
					$getterFn = substr($segment, 0, -2);
					if (is_callable([$target, $getterFn]))
						$target = $target->{$getterFn}();
					else
						return value($default);
				}
				else {
					return value($default);
				}
			}

			return $target;
		}
	}