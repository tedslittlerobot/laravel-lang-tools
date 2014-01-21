<?php namespace Tlr\LaravelLangUtils;

use Illuminate\Support\ServiceProvider;

class LaravelLangsServiceProvider extends ServiceProvider {

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
		$this->commands(['Tlr\LaravelLangUtils\ImportCommand', 'Tlr\LaravelLangUtils\ExportCommand']);
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
