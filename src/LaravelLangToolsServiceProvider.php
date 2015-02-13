<?php namespace Tlr\LaravelLangTools;

use Illuminate\Support\ServiceProvider;

class LaravelLangToolsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->commands(['Tlr\LaravelLangTools\ImportCommand', 'Tlr\LaravelLangTools\ExportCommand']);
	}

	/**
	 * Boot the package
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->addViewNamespace();
	}

	/**
	 * Register the view namespace
	 *
	 * @return void
	 */
	public function addViewNamespace()
	{
		$namespace = 'laravel-lang-tools';
		$package = 'tlr/laravel-lang-tools';

		// Add the application view path first
		$appViewPath = $this->getAppViewPath($package);

		if ($this->app['files']->isDirectory($appViewPath))
		{
			$this->app['view']->addNamespace($namespace, $appViewPath);
		}

		// Register the package view path
		if ( $viewPath = realpath(__DIR__ . '/../resources/views') )
		{

			$this->app['view']->addNamespace($namespace, $viewPath);
		}
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
