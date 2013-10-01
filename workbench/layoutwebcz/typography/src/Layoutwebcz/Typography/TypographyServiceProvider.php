<?php namespace Layoutwebcz\Typography;

use Illuminate\Support\ServiceProvider;
use View;

class TypographyServiceProvider extends ServiceProvider {

    /**
     * Package root directory
     * @var string
     */
    private $root_dir = '';

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->root_dir = dirname(dirname(dirname(__FILE__)));
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->package('layoutwebcz/typography');

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Typography', 'Layoutwebcz\Typography\Facades\Typography');
	}

    public function boot()
    {
        View::addNamespace('typography', $this->root_dir . DIRECTORY_SEPARATOR . 'views');

        $this->app['typography'] = $this->app->share(function($app)
        {
            return new Typography($app);
        });
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('typography');
	}

}