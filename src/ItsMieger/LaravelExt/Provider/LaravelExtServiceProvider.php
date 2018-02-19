<?php


	namespace ItsMieger\LaravelExt\Provider;


	use Illuminate\Support\ServiceProvider;

	class LaravelExtServiceProvider extends ServiceProvider
	{
		const PACKAGE_NAME = 'itsMiegerLaravelExt';

		protected $packageRoot = __DIR__ . '/../../../..';


		/**
		 * Bootstrap the application services.
		 *
		 * @return void
		 */
		public function boot() {
			// include helpers
			if (config(self::PACKAGE_NAME . '.collectionMacros', true))
				include dirname(__DIR__) . '/collectionMacros.php';
		}

		/**
		 * Register the application services.
		 *
		 * @return void
		 */
		public function register() {
			$this->mergeConfigFrom($this->packageRoot . '/config/config.php', self::PACKAGE_NAME);
		}
	}
