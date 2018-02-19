<?php
	/**
	 * Created by PhpStorm.
	 * User: chris
	 * Date: 19.02.18
	 * Time: 11:08
	 */

	namespace ItsMiegerLaravelExtTest\Cases;


	use ItsMieger\Obj\Obj;
	use Orchestra\Testbench\TestCase as OrchestraTestCase;
	use ItsMieger\LaravelExt\Provider\LaraExtServiceProvider;

	class TestCase extends OrchestraTestCase
	{
		/**
		 * Setup the test environment.
		 */
		public function setUp() {
			parent::setUp();

			Obj::resetMock();
		}

		/**
		 * Load package service provider
		 * @param  \Illuminate\Foundation\Application $app
		 * @return array
		 */
		protected function getPackageProviders($app) {
			return [
				LaraExtServiceProvider::class,
			];
		}
	}