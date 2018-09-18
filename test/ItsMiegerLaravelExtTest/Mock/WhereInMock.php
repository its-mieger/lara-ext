<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 18.09.18
	 * Time: 10:56
	 */

	namespace ItsMiegerLaravelExtTest\Mock;


	class WhereInMock
	{
		protected static $closure;

		public static function prepare(\Closure $c) {
			static::$closure = $c;
		}

		public static function whereIn() {
			return call_user_func_array(static::$closure, func_get_args());
		}
	}