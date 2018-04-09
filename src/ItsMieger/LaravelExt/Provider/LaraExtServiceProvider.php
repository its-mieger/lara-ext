<?php


	namespace ItsMieger\LaravelExt\Provider;


	use Illuminate\Support\ServiceProvider;

	class LaraExtServiceProvider extends ServiceProvider
	{
		const PACKAGE_NAME = 'laraExt';

		protected $packageRoot = __DIR__ . '/../../../..';


		/**
		 * Bootstrap the application services.
		 *
		 * @return void
		 */
		public function boot() {

			$this->publishes([
				$this->packageRoot . '/config/config.php' => config_path(self::PACKAGE_NAME . '.php'),
			]);

			// include collection macros
			if (config(self::PACKAGE_NAME . '.collectionMacros', true))
				include dirname(__DIR__) . '/collectionMacros.php';

			// include string macros
			if (config(self::PACKAGE_NAME . '.strMacros', true))
				include dirname(__DIR__) . '/strMacros.php';
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
