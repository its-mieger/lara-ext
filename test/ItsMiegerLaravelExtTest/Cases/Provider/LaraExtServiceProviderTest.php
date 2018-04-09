<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 19.02.18
	 * Time: 19:40
	 */

	namespace ItsMiegerLaravelExtTest\Cases\Provider;


	use Illuminate\Support\Str;
	use ItsMiegerLaravelExtTest\Cases\TestCase;

	class LaraExtServiceProviderTest extends TestCase
	{
		public function testCollectionMacrosRegistered() {
			$this->assertEquals([], collect()->diffBy([], function() {return 0;})->all());
		}

		public function testStringMacrosRegistered() {
			$this->assertEquals([], Str::extract('', ':', 0));
		}
	}