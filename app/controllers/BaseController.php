<?php

class BaseController extends Controller {

    protected $page = '';

    /**
     * General template
     * @var string
     */
    protected $layout = 'shared.template';

    public function __construct()
    {
        // check if app is installed
        $this->beforeFilter('installed');

        if (Request::segment(2) === 'account' and Request::segment(3) === 'login')
        {
            $this->layout = 'shared.login';
        }
        else
        {
            // application is installed, check for authorization
            if(APP_INSTALLED)
            {
                // login for all users required
                $this->beforeFilter('auth');
            }
        }

        $this->page = (array)Session::get('page', array());

        $this->beforeFilter('csrf', array('on' => 'post')); // CSRF security on post requests
    }

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if (!is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}

        // defaults
        $this->layout->document_title_prefix = '';
        $this->layout->document_title_suffix = '';
        $this->layout->document_title = '';
        $this->layout->article_title = '';
        $this->layout->content = '';

        if($document_title_suffix = Config::get('shared.document.append_position'))
        {
            switch($document_title_suffix)
            {
                case 'before':
                    $this->layout->document_title_prefix = Lang::get('shared.document_title_suffix');
                    break;

                case 'after':
                    $this->layout->document_title_suffix = Lang::get('shared.document_title_suffix');
                    break;
            }
        }

        $MenuModel = new MenuModel();

        $this->layout->sidebar_menu = $MenuModel->getCategoryMenuItems();

        // must be "shared" because it is not visible in $context variable in shared/template.blade.php file
        View::share('headermenu', $MenuModel->getMenuItems('header'));

        // Typography package
        //View::share('Typography', App::make('Typography'));
	}

}