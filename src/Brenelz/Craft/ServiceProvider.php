<?php namespace Brenelz\Craft;

use Exception;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ServiceProvider extends BaseServiceProvider {

	protected $defer = false;

	/**
	 * Bootstrap any necessary services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('brenelz/craft');

		
		$this->app->missing(function(Exception $e) { $this->runCraft(); });
		$this->app->error(function(MethodNotAllowedHttpException $e){ $this->runCraft(); });

		AliasLoader::getInstance()->alias('Craft','Brenelz\Craft\Facade');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bindShared('craft', function($app){
			
			$path = $app['config']->get('craft::craft.path');

			require $path.'/app/bootstrap.php';

			// Initialize Craft\WebApp this way so it doesn't cause a syntax error on PHP < 5.3
			$appClass = '\Craft\WebApp';
			$app = new $appClass($config);

			return $app;
		});
	}

	private function runCraft() {
		$craft = $this->app->make('craft');
		$craft->run();
	}

	public function provides()
	{
		return array(
			'Craft\WebApp'
		);
	}


}
